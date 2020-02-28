<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\data\Pagination;

use common\models\Query\MetaDate;
use common\models\Query\Lot\Lots;
use common\models\Query\Lot\Bankrupts;

use frontend\models\BankruptSearch;

/**
 * Doljnik controller
 */
class DoljnikController extends Controller
{
  /**
   * {@inheritdoc}
   */
  public function behaviors()
  {
    return [
      'access' => [
        'class' => AccessControl::className(),
        'only' => ['logout', 'signup'],
        'rules' => [
          [
            'actions' => ['signup'],
            'allow' => true,
            'roles' => ['?'],
          ],
          [
            'actions' => ['logout'],
            'allow' => true,
            'roles' => ['@'],
          ],
        ],
      ],
      'verbs' => [
        'class' => VerbFilter::className(),
        'actions' => [
          'logout' => ['post'],
        ],
      ],
    ];
  }
  /**
   * {@inheritdoc}
   */
  public function actions()
  {
    return [
      'error' => [
        'class' => 'yii\web\ErrorAction',
      ],
      'captcha' => [
        'class' => 'yii\captcha\CaptchaAction',
        'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
      ],
    ];
  }
  public function actionRedirect($bnkr_id = null)
  {
    return $this->redirect('/dolzhniki' . ($bnkr_id) ? '/' . $bnkr_id : '');
  }

  /**
   * Displays homepage.
   *
   * @return mixed
   */
  public function actionList()
  {
    $title = 'Список должников';
    $model = new BankruptSearch();
    $model->type = 'company';
    $bankruptQuery = Bankrupts::find();

    // Мета данные Strat-> 
    $metaData = MetaDate::find()->where(['mdName' => 'doljnik-list'])->one();

    Yii::$app->params['description'] = $metaData->mdDescription;
    Yii::$app->params['text'] = $metaData->mdText;
    Yii::$app->params['title'] = ($metaData->mdTitle) ? $metaData->mdTitle : $title;
    Yii::$app->params['h1'] = ($metaData->mdH1) ? $metaData->mdH1 : $title;
    // Мета данные <-End

    // Фильтрация лотов Start->
    $model->load(Yii::$app->request->get());
    $bankruptsQuery = $model->search($bankruptQuery, $url);

    $bankruptCount = clone $bankruptsQuery;

    $count = $bankruptCount->count();
    $pages = new Pagination(['totalCount' => $count, 'pageSize' => 20]);

    $bankrupts = $bankruptsQuery->offset($pages->offset)
      ->limit($pages->limit)
      ->all();
    // Фильтрация лотов <-End

    // Хлебные крошки Start->
    Yii::$app->params['breadcrumbs'][] = [
      'label' => ' ' . $title,
      'template' => '<li class="breadcrumb-item active" aria-current="page">{link}</li>',
      'url' => "javascript:void(0);"
    ];
    // Хлебные крошки <-End

    $offset = $pages->offset;
    $limit = $pages->limit;
    return $this->render('list', compact('model', 'pages', 'bankrupts', 'offset', 'count'));
  }
  public function actionDoljnik_page($bnkr_id)
  {
    // Сбор информации из бд Start->
    $bankrupt = Bankrupts::findOne($bnkr_id);
    $title = 'Должник - ' . $bankrupt->name;

    switch ($bankrupt->typeId) {
      case 1:
        $name = $bankrupt->name;
        $address = $bankrupt->address;
        $inn = $bankrupt->inn;
        break;
      case 2:
        $name = $bankrupt->name;
        $address = $bankrupt->address;
        $inn = $bankrupt->inn;
        break;
    }

    $lots_bankrupt = Lots::find()->joinWith('torg')->where(['torg.bankruptId'=>$bnkr_id])->andWhere(['torg.typeId' => 1])->limit(20)->orderBy('images DESC, torg.publishedDate DESC')->all();
    // $lots_bankrupt = LotsBankrupt::find()->where(['bankrupt.oldId' => $bnkr_id])->orderBy('lot_image DESC, lot_timepublication DESC')->all();
    // Сбор информации из бд <-End
    // $lots_bankrupt = Lots::find()->joinWith('torgy.case.bnkr')->where(['bnkr.id' => $bnkr_id])->orderBy('lotid DESC')->limit(5)->all();
    // ->andWhere('lot_timeend >= NOW()')
    // Мета данные Strat-> 
    $metaData = MetaDate::find()->where(['mdName' => "doljnik-page"])->one();

    $search  = [
      '${bnkrName}',
      '${bnkrAddress}',
      '${bnkrInn}'
    ];
    $replace = [
      $name,
      $address,
      $inn
    ];

    Yii::$app->params['description'] = str_replace($search, $replace, $metaData->mdDescription);
    Yii::$app->params['text'] = $metaData->mdText;
    Yii::$app->params['title'] = str_replace($search, $replace, $metaData->mdTitle);
    Yii::$app->params['h1'] = $bankrupt->name;
    // Мета данные <-End

    // Хлебные крошки Start->
    Yii::$app->params['breadcrumbs'][] = [
      'label' => ' Список должников',
      'template' => '<li class="breadcrumb-item active" aria-current="page">{link}</li>',
      'url' => ['doljnik/list']
    ];;
    Yii::$app->params['breadcrumbs'][] = [
      'label' => ' ' . $title,
      'template' => '<li class="breadcrumb-item active" aria-current="page">{link}</li>',
      'url' => "javascript:void(0);"
    ];
    // Хлебные крошки <-End

    return $this->render('doljnik_page', ['bankrupt' => $bankrupt, 'lots_bankrupt' => $lots_bankrupt]);
  }
}
