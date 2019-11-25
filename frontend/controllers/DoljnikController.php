<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\data\Pagination;

use common\models\Query\MetaDate;
use common\models\Query\Bankrupt\Bankrupts;
use common\models\Query\Bankrupt\LotsBankrupt;

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
        return $this->redirect('/dolzhniki'.($bnkr_id)? '/'.$bnkr_id : '');
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
        $model->type = 'person';
        $bankruptQuery = Bankrupts::find();
        
        // Мета данные Strat-> 
        $metaData = MetaDate::find()->where(['mdName' => 'doljnik-list'])->one();

        Yii::$app->params['description'] = $metaData->mdDescription;
        Yii::$app->params['text'] = $metaData->mdText;
        Yii::$app->params['title'] = ($metaData->mdTitle)? $metaData->mdTitle : $title;
        Yii::$app->params['h1'] = ($metaData->mdH1)? $metaData->mdH1 : $title;
        // Мета данные <-End

        // Фильтрация лотов Start->
        $model->load(Yii::$app->request->get());
        $bankruptsQuery = $model->search($bankruptQuery, $url);

        $bankruptCount = Clone $bankruptsQuery;

        $count = $bankruptCount->count();
        $pages = new Pagination(['totalCount' => $count, 'pageSize'=> 20]);

        $bankrupts = $bankruptsQuery->offset($pages->offset)
            ->limit($pages->limit)
            ->all();
        // Фильтрация лотов <-End

        // Хлебные крошки Start->
        Yii::$app->params['breadcrumbs'][] = [
            'label' => ' '.$title,
            'template' => '<li class="breadcrumb-item active" aria-current="page">{link}</li>',
            'url' => ['doljnik/list']
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
        $title = 'Должник - '.(($bankrupt->bankrupttype == 'Organization')? $bankrupt->company->shortname : $bankrupt->person->lname.' '.$bankrupt->person->fname.' '.$bankrupt->person->mname);

        switch ($bankrupt->bankrupttype) {
            case 'Organization':
                    $name = $bankrupt->company->shortname;
                    $address = $bankrupt->company->legaladdress;
                    $inn = $bankrupt->company->inn;
                break;
            case 'Person':
                    $name = $bankrupt->person->lname.' '.$bankrupt->person->fname.' '.$bankrupt->person->mname;
                    $address = $bankrupt->person->address;
                    $inn = $bankrupt->person->inn;
                break;
        }

        $lots_bankrupt = LotsBankrupt::find()->where(['bnkr__id' => $bnkr_id])->andWhere('lot_timeend >= NOW()')->orderBy('lot_image DESC, lot_timepublication DESC')->all();
        // Сбор информации из бд <-End

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
        Yii::$app->params['h1'] = str_replace($search, $replace, $metaData->mdH1);
        // Мета данные <-End

        // Хлебные крошки Start->
        Yii::$app->params['breadcrumbs'][] = [
            'label' => ' Список должников',
            'template' => '<li class="breadcrumb-item active" aria-current="page">{link}</li>',
            'url' => ['doljnik/list']
        ];
        ;
        Yii::$app->params['breadcrumbs'][] = [
            'label' => ' '.$title,
            'template' => '<li class="breadcrumb-item active" aria-current="page">{link}</li>',
            'url' => [Url::to(['doljnik/list'])."/$bnkr_id"]
        ];
        // Хлебные крошки <-End

        return $this->render('doljnik_page', ['bankrupt' => $bankrupt, 'lots_bankrupt' => $lots_bankrupt]);
    }

}
