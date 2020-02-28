<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\data\Pagination;

use common\models\Query\MetaDate;
use common\models\Query\Bankrupt\Arbitrs;
use common\models\Query\Bankrupt\LotsBankrupt;
use common\models\Query\Lot\Lots;
use common\models\Query\Lot\Managers;
use common\models\Query\Lot\Cases;

use frontend\models\ArbitrSearch;

/**
 * Arbitr controller
 */
class ArbitrController extends Controller
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

    public function actionRedirect($arb_id = null)
    {
        
        return $this->redirect('/arbitrazhnye-upravlyayushchie'.($arb_id)? '/'.$arb_id : '');
    }
    
    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionList()
    {
        $title = 'Список арбитражных управляющих';
        $model = new ArbitrSearch();
        $arbitrQuery = Managers::find();

        // Мета данные Strat-> 
        $metaData = MetaDate::find()->where(['mdName' => 'arbitr-list'])->one();

        Yii::$app->params['description'] = $metaData->mdDescription;
        Yii::$app->params['text'] = $metaData->mdText;
        Yii::$app->params['title'] = ($metaData->mdTitle)? $metaData->mdTitle : $title;
        Yii::$app->params['h1'] = ($metaData->mdH1)? $metaData->mdH1 : $title;
        // Мета данные <-End

        // Фильтрация лотов Start->
        $model->load(Yii::$app->request->get());
        $arbitrsQuery = $model->search($arbitrQuery, $url);

        $arbitrCount = Clone $arbitrQuery;

        $count = $arbitrCount->count();
        $pages = new Pagination(['totalCount' => $count, 'pageSize'=> 10]);

        $arbitrs = $arbitrsQuery
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->all();
        // Фильтрация лотов <-End

        // Хлебные крошки Start->
        Yii::$app->params['breadcrumbs'][] = [
            'label' => ' '.$title,
            'template' => '<li class="breadcrumb-item active" aria-current="page">{link}</li>',
            'url' => ['javascript:void(0);']
        ];
        // Хлебные крошки <-End

        $offset = $pages->offset;
        $limit = $pages->limit;
        return $this->render('list', compact('model', 'pages', 'arbitrs', 'offset', 'count'));
    }
    public function actionArbitr_page($arb_id)
    {
        // Сбор информации из бд Start->
        $arbitr = Managers::findOne($arb_id);
        $title = 'Арбитражный управляющий - '.$arbitr->fullName;
        $countCases = 0;

        $lots_bankrupt = Lots::find()->joinWith('torg')->where(['torg.publisherId'=>$arb_id])->andWhere(['torg.typeId' => 1])->limit(20)->orderBy('images DESC, torg.publishedDate DESC')->all();

        foreach ($lots_bankrupt as $key => $lot) {
            $countCases = $countCases + Cases::find()->where(['id'=>$lot->torg->case->id])->count();
        }
        // Сбор информации из бд <-End

        // Мета данные Strat->
        $metaData = MetaDate::find()->where(['mdName' => "arbitr-page"])->one();

        $search  = [
            '${arbitrName}',
            '${arbitrAddress}',
            '${sroName}',
            '${regNumber}',
            '${arbitrInn}',
            '${arbitrOgrn}',
            '${countCase}',
            '${countLot}'
        ];
        $replace = [
            $arbitr->fullName,
            $arbitr->address,
            str_replace('"',"'",$arbitr->sro->title),
            $arbitr->regnum,
            $arbitr->inn,
            $countCases,
            count($lots_bankrupt)
        ];
        Yii::$app->params['description'] = str_replace($search, $replace, $metaData->mdDescription);
        Yii::$app->params['title'] = str_replace($search, $replace, $metaData->mdTitle);
        Yii::$app->params['h1'] = $arbitr->fullName;
        Yii::$app->params['text'] = $metaData->mdText;
        // Мета данные <-End

        // Хлебные крошки Start->
        Yii::$app->params['breadcrumbs'][] = [
            'label' => ' Список арбитражных управляющих',
            'template' => '<li class="breadcrumb-item active" aria-current="page">{link}</li>',
            'url' => ['arbitr/list']
        ];
        ;
        Yii::$app->params['breadcrumbs'][] = [
            'label' => ' '.$arbitr->fullName,
            'template' => '<li class="breadcrumb-item active" aria-current="page">{link}</li>',
            'url' => ["javascript:void(0);"]
        ];
        // Хлебные крошки <-End

        return $this->render('arbitr_page', ['arbitr' => $arbitr, 'lots_bankrupt' => $lots_bankrupt, 'countCases' => $countCases]);
    }

}
