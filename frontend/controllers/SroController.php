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
use common\models\Query\Lot\Lots;
use common\models\Query\Lot\Sro;
use common\models\Query\Lot\Managers;
use common\models\Query\Lot\Cases;

use frontend\models\SroSearch;

/**
 * SRO controller
 */
class SroController extends Controller
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

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionList()
    {
        $title = 'Список СРО';
        $model = new SroSearch();
        $sroQuery = Sro::find()->where(['not', ['title' => null]]);

        // Мета данные Strat-> 
        $metaData = MetaDate::find()->where(['mdName' => 'sro-list'])->one();

        Yii::$app->params['description'] = $metaData->mdDescription;
        Yii::$app->params['text'] = $metaData->mdText;
        Yii::$app->params['title'] = ($metaData->mdTitle)? $metaData->mdTitle : $title;
        Yii::$app->params['h1'] = ($metaData->mdH1)? $metaData->mdH1 : $title;
        // Мета данные <-End

        // Фильтрация сро Start->
        $model->load(Yii::$app->request->get());
        $srosQuery = $model->search($sroQuery, $url);

        $sroCount = Clone $srosQuery;

        $count = $sroCount->count();
        $pages = new Pagination(['totalCount' => $count, 'pageSize'=> 10]);

        $sros = $srosQuery
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->all();
        // Фильтрация сро <-End

        // Хлебные крошки Start->
        Yii::$app->params['breadcrumbs'][] = [
            'label' => ' '.$title,
            'template' => '<li class="breadcrumb-item active" aria-current="page">{link}</li>',
            'url' => "javascript:void(0);"
        ];
        // Хлебные крошки <-End

        $offset = $pages->offset;
        $limit = $pages->limit;
        return $this->render('list', compact('model', 'pages', 'sros', 'offset', 'count'));
    }
    public function actionSroPage($sro_id)
    {
        // Сбор информации из бд Start->
        $sro = Sro::findOne($sro_id);
        $title = 'СРО — '.$sro->title;
        
        $arbitrs = Managers::find()->where(['sroId'=>$sro_id])->orderBy('"fullName" ASC')->all();

        $countCases = 0;
        $lotsBankruptCount = 0;

        foreach ($arbitrs as $arbitr) {
            foreach ($arbitr->torgs as $torg) {
                $countCases = $countCases + Cases::find()->where(['id'=>$torg->caseId])->count();
                $lotsBankruptCount = $lotsBankruptCount + Lots::find()->joinWith('torg')->where(['torg.id'=>$torg->id])->andWhere(['torg.typeId' => 1])->count();
            }

        }
        // Сбор информации из бд <-End

        // Мета данные Strat-> 
        $metaData = MetaDate::find()->where(['mdName' => "sro-page"])->one();

        $search  = [
            '${sroName}',
            '${sroAddress}',
            '${regNumber}',
            '${sroInn}',
            '${sroOgrn}',
            '${arbitrCount}',
        ];
        $replace = [
            $sro->title,
            $sro->address,
            $sro->regnum,
            $sro->inn,
            $sro->info['ogrn'],
            count($arbitrs)
        ];
        Yii::$app->params['description'] = str_replace($search, $replace, $metaData->mdDescription);
        Yii::$app->params['title'] = ($metaData)? str_replace($search, $replace, $metaData->mdTitle) : $title;
        Yii::$app->params['h1'] = ($metaData)? str_replace($search, $replace, $metaData->mdH1) : $title;
        Yii::$app->params['text'] = $metaData->mdText;
        // Мета данные <-End

        // Хлебные крошки Start->
        Yii::$app->params['breadcrumbs'][] = [
            'label' => ' Список СРО',
            'template' => '<li class="breadcrumb-item active" aria-current="page">{link}</li>',
            'url' => ['sro/list']
        ];
        ;
        Yii::$app->params['breadcrumbs'][] = [
            'label' => ' '.$sro->title,
            'template' => '<li class="breadcrumb-item" aria-current="page">{link}</li>',
            'url' => "javascript:void(0);"
        ];
        // Хлебные крошки <-End

        return $this->render('sro_page', ['sro' => $sro, 'arbitrs' => $arbitrs, 'countCases' => $countCases, 'lotsBankruptCount' => $lotsBankruptCount]);
    }

}
