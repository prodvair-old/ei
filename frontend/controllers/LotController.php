<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\data\Pagination;

// Запросы
use common\models\Query\MetaDate;
use common\models\Query\Bankrupt\LotsBankrupt;
use common\models\Query\Arrest\LotsArrest;

use common\models\Query\LotsCategory;
use common\models\Query\Regions;

use frontend\models\SearchLot;
use frontend\models\SortLot;

/**
 * Lot controller
 */
class LotController extends Controller
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
    // Главная страница лотов
    public function actionIndex($type)
    {
        // Проверка ссылок ЧПУ и подставление типа лотов Strat->
        switch ($type) {
            case 'bankrupt':
                $lots = LotsBankrupt::find()->limit(10)->orderBy('lot_image DESC, lot_timepublication DESC')->all();
                break;
            case 'arrest':
                $lots = LotsArrest::find()->limit(10)->orderBy('lot_image DESC, lot_timepublication DESC')->all();
                break;
            default:
                Yii::$app->response->statusCode = 404;
                throw new \yii\web\NotFoundHttpException;
                break;
        }
        // Проверка ссылок ЧПУ и подставление типа лотов <-End 

        // Мета данные Strat-> 
        $metaData = MetaDate::find()->where(['mdName' => $type])->one();

        Yii::$app->params['description'] = $metaData->mdDescription;
        Yii::$app->params['text'] = $metaData->mdText;
        Yii::$app->params['title'] = $metaData->mdTitle;
        Yii::$app->params['h1'] = $metaData->mdH1;
        // Мета данные <-End 

        return $this->render('index', compact('type', 'lots'));
    }
    // Ссылка на категории лотов
    public function actionCategory($type, $category)
    {
        $model = new SearchLot();
        $modelSort = new SortLot();
        
        // Проверка ссылок ЧПУ и подставление типа лотов Strat->
        if ($category == 'lot-list') {
            $queryCategory = 'all';
        } else if (!empty($items = LotsCategory::find()->where(['translit_name'=>$category])->one())) {
            $queryCategory = $items->id;
            $model->category = $items->id;
        } else {
            Yii::$app->response->statusCode = 404;
            throw new \yii\web\NotFoundHttpException;
        }

        switch ($type) {
            case 'bankrupt':
                $lotsQuery = LotsBankrupt::find();
                $lotsPrice = LotsBankrupt::find();
                break;
            case 'arrest':
                $lotsQuery = LotsArrest::find();
                $lotsPrice = LotsArrest::find();

                break;
            default:
                Yii::$app->response->statusCode = 404;
                throw new \yii\web\NotFoundHttpException;
                break;
        }
        // Проверка ссылок ЧПУ и подставление типа лотов <-End 

        // Мета данные Strat-> 
        $metaData = MetaDate::find()->where(['mdName' => "$type/$category"])->one();

        Yii::$app->params['description'] = $metaData->mdDescription;
        Yii::$app->params['text'] = $metaData->mdText;
        Yii::$app->params['title'] = $metaData->mdTitle;
        Yii::$app->params['h1'] = $metaData->mdH1;
        // Мета данные <-End 

        // Фильтрация лотов Start->
        $model->load(Yii::$app->request->post());
        $query = $model->search($lotsQuery, $type);

        $lotsQuery = Clone $query['lots'];
        $lotsPrice = Clone $query['lotsPrice'];
        $lotsCount = Clone $lotsQuery;
        
        $price = $lotsPrice->select(['min(lot_startprice)','max(lot_startprice)'])->asArray()->one();
        $count = $lotsCount->count();

        $modelSort->load(Yii::$app->request->post());
        $lotsQuery = $modelSort->sortBy($lotsQuery, $type);

        $pages = new Pagination(['totalCount' => $count, 'pageSize'=> 10]);

        $lots = $lotsQuery->offset($pages->offset)
            ->limit($pages->limit)
            ->all();
        // Фильтрация лотов <-End 
        
        $offset = $pages->offset;
        $limit = $pages->limit;
        return $this->render('lot_find', compact('model', 'modelSort', 'type', 'lots', 'pages', 'count', 'offset', 'limit', 'price'));
    }
    // Ссылка на подкатегории лотов
    public function actionSubcategory($type, $category, $subcategory)
    {
        // Проверка ссылок ЧПУ и подставление типа лотов Strat->
        if (!empty($items = LotsCategory::findOne(['translit_name'=>$category]))) {
            $queryCategory = $items->id;
        } else {
            Yii::$app->response->statusCode = 404;
            throw new \yii\web\NotFoundHttpException;
        }
        
        switch ($type) {
            case 'bankrupt':
                foreach ($items->bankrupt_categorys_translit as $key => $value) {
                    if ($key == $subcategory) {
                        $querySubcategory = $value['id'];        
                    }
                }
                
                break;
            case 'arrest':
                foreach ($items->arrest_categorys_translit as $key => $value) {
                    if ($key == $subcategory) {
                        $querySubcategory = $value['id'];        
                    }
                }
                break;
            default:
                Yii::$app->response->statusCode = 404;
                throw new \yii\web\NotFoundHttpException;
                break;
        }

        if (empty($querySubcategory)) {
            Yii::$app->response->statusCode = 404;
            throw new \yii\web\NotFoundHttpException;
        }
        // Проверка ссылок ЧПУ и подставление типа лотов <-End 

        // Мета данные Strat-> 
        $metaData = MetaDate::find()->where(['mdName' => "$type/$category/$subcategory"])->one();

        Yii::$app->params['description'] = $metaData->mdDescription;
        Yii::$app->params['text'] = $metaData->mdText;
        Yii::$app->params['title'] = $metaData->mdTitle;
        Yii::$app->params['h1'] = $metaData->mdH1;
        // Мета данные <-End 

        return $this->render('lot_find');
    }
    // Ссылка на регионы
    public function actionRegion($type, $category, $subcategory, $region)
    {
        // Проверка ссылок ЧПУ и подставление типа лотов Strat->
        if (!empty($items = LotsCategory::findOne(['translit_name'=>$category]))) {
            $queryCategory = $items->id;
        } else {
            Yii::$app->response->statusCode = 404;
            throw new \yii\web\NotFoundHttpException;
        }

        if (!empty($regionItem = Regions::findOne(['name_translit' => $region]))) {
            $queryRegion = $regionItem->id;
        } else {
            Yii::$app->response->statusCode = 404;
            throw new \yii\web\NotFoundHttpException;
        }
        
        switch ($type) {
            case 'bankrupt':
                foreach ($items->bankrupt_categorys_translit as $key => $value) {
                    if ($key == $subcategory) {
                        $querySubcategory = $value['id'];        
                    }
                }
                break;
            case 'arrest':
                foreach ($items->arrest_categorys_translit as $key => $value) {
                    if ($key == $subcategory) {
                        $querySubcategory = $value['id'];        
                    }
                }
                break;
            default:
                Yii::$app->response->statusCode = 404;
                throw new \yii\web\NotFoundHttpException;
                break;
        }

        if (empty($querySubcategory)) {
            Yii::$app->response->statusCode = 404;
            throw new \yii\web\NotFoundHttpException;
        }
        // Проверка ссылок ЧПУ и подставление типа лотов <-End 

        // Мета данные Strat-> 
        $metaData = MetaDate::find()->where(['mdName' => "$type/$category/$subcategory/$region"])->one();

        Yii::$app->params['description'] = $metaData->mdDescription;
        Yii::$app->params['text'] = $metaData->mdText;
        Yii::$app->params['title'] = $metaData->mdTitle;
        Yii::$app->params['h1'] = $metaData->mdH1;
        // Мета данные <-End 

        return $this->render('lot_find');
    }
    public function actionLot_page($type, $category, $subcategory, $id)
    {
        // Проверка ссылок ЧПУ и подставление типа лотов Strat->
        switch ($type) {
            case 'bankrupt':
                $lots = LotsBankrupt::findOne(['lot_id'=>$id]);
                break;
            case 'arrest':
                $lots = LotsArrest::findOne(['lotId'=>$id]);
                break;
            default:
                Yii::$app->response->statusCode = 404;
                throw new \yii\web\NotFoundHttpException;
                break;
        }
        // Проверка ссылок ЧПУ и подставление типа лотов <-End 

        // Мета данные Strat-> 
        $metaData = MetaDate::find()->where(['mdName' => "$type/$category/$subcategory/$id"])->one();

        Yii::$app->params['description'] = $metaData->mdDescription;
        Yii::$app->params['text'] = $metaData->mdText;
        Yii::$app->params['title'] = $metaData->mdTitle;
        Yii::$app->params['h1'] = $metaData->mdH1;
        // Мета данные <-End 

        return $this->render('lot_page');
    }
}
