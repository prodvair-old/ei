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
use common\models\Query\Bankrupt\Lots;
use common\models\Query\Arrest\LotsArrest;

use frontend\models\WishListEdit;

use common\models\Query\LotsCategory;
use common\models\Query\Regions;

use frontend\models\SearchLot;
use frontend\models\SortLot;
use frontend\models\ServiceLotForm;

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
    public function beforeAction($action)
    {
        if (in_array($action->id, ['load_category']) || in_array($action->id, ['wish_list'])) {
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
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

    public function actionRedirect($category, $subcategory = null, $id = null)
    {
        if ($category && $subcategory && $id) {
            $url = "$category/$subcategory/$id";
        } else if ($category && $subcategory) {
            $url = "$category/$subcategory";
        } else {
            $url = $category;
        }

        return $this->redirect("/bankrupt/$url");
    }

    public function actionRedirectRegion($category, $subcategory = null, $region = null)
    {
        if ($category && $subcategory && $region) {
            $url = "$category/$subcategory/$region";
        } else if ($category && $subcategory) {
            $url = "$category/$subcategory";
        } else {
            $url = $category;
        }

        return $this->redirect("/bankrupt/$url");
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
                $lots = LotsBankrupt::find()->limit(3)->where('lot_timeend >= NOW()')->orderBy('lot_image DESC, lot_timepublication DESC')->all();
                $lotsFovarit = LotsBankrupt::find()->limit(3)->where('lot_timeend >= NOW()')->orderBy('wish_count DESC')->all();

                $title = 'Банкротное имущество';
                break;
            case 'arrest':
                $lots = LotsArrest::find()->joinWith('torgs')->where('torgs."trgExpireDate" >= NOW()')->limit(3)->orderBy('torgs."trgPublished" DESC')->all();
                $lotsFovarit = LotsArrest::find()->joinWith('torgs')->where('torgs."trgExpireDate" >= NOW()')->limit(3)->orderBy('torgs."trgPublished" DESC')->all();

                $title = 'Арестованное имущество';
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
        Yii::$app->params['title'] = ($metaData->mdTitle)? $metaData->mdTitle : $title;
        Yii::$app->params['h1'] = ($metaData->mdH1)? $metaData->mdH1 : $title;
        // Мета данные <-End 

        return $this->render('index', compact('type', 'lots', 'lotsFovarit'));
    }

    // Ссылка на категории лотов
    public function actionSearch($type, $category, $subcategory = null, $region = null)
    {
        $model = new SearchLot();
        $modelSort = new SortLot();
        $urlParamServer = $_SERVER['REQUEST_URI'];
        $lot_list_cache = 'lot_list_cache__'.$urlParamServer;
        $lot_price_cache = 'lot_price_cache__'.$urlParamServer;
        
        // Проверка ссылок ЧПУ и подставление типа лотов Strat->
        if ($category == 'lot-list' && $subcategory == null) {
            $queryCategory = '0';
            $title = 'Все лоты';
            $url = "$type/$category";
        } else if (!empty($items = LotsCategory::find()->where(['translit_name'=>$category])->one())) {
            $queryCategory = $items->id;
            $model->category = $items->id;
            $titleCategory = $items->name;
            $title = $items->name;
            $url = "$type/$category";
        } else {
            Yii::$app->response->statusCode = 404;
            throw new \yii\web\NotFoundHttpException;
        }

        $model->type = $type;
        $modelSort->type = $type;
        switch ($type) {
            case 'bankrupt':
                $lotsQuery = LotsBankrupt::find()->joinWith('category');
                $lotsPrice = LotsBankrupt::find()->joinWith('category');

                $metaDataType = MetaDate::find()->where(['mdName' => $type])->one();
                $titleType = ($metaDataType->mdH1)? $metaDataType->mdH1 : 'Банкротное имущество';

                if ($subcategory != null) {
                    foreach ($items->bankrupt_categorys_translit as $key => $value) {
                        if ($key == $subcategory) {
                            $querySubcategory = $value['id'];    
                            $model->subCategory[0] = $value['id'];    
                            $titleSubcategory = $value['name'];
                            $title = $value['name'];
                            $url = "$type/$category/$subcategory";
                        }
                    }
                    if (empty($querySubcategory)) {
                        Yii::$app->response->statusCode = 404;
                        throw new \yii\web\NotFoundHttpException;
                    }
                }
                break;
            case 'arrest':
                $lotsQuery = LotsArrest::find()->joinWith('torgs');
                $lotsPrice = LotsArrest::find()->joinWith('torgs');

                $metaDataType = MetaDate::find()->where(['mdName' => $type])->one();
                $titleType = ($metaDataType->mdH1)? $metaDataType->mdH1 : 'Арестованное имущество';

                if ($subcategory != null) {
                    foreach ($items->arrest_categorys_translit as $key => $value) {
                        if ($key == $subcategory) {
                            $querySubcategory = $value['id'];        
                            $model->subCategory[0] = $value['id']; 
                            $titleSubcategory = $value['name'];
                            $title = $value['name'];
                            $url = "$type/$category/$subcategory";
                        }
                    }
                    if (empty($querySubcategory)) {
                        Yii::$app->response->statusCode = 404;
                        throw new \yii\web\NotFoundHttpException;
                    }
                }
                break;
            default:
                Yii::$app->response->statusCode = 404;
                throw new \yii\web\NotFoundHttpException;
                break;
        }
        
        if ($region != null) {
            try {
                $regionItem = Regions::findOne(['name_translit' => $region]);
                $queryRegion = $regionItem->id;
                $model->region[0] = $regionItem->id;
                $title = $regionItem->name;
                $url = "$type/$category/$subcategory/$region";
            } catch (\Throwable $th) {
                Yii::$app->response->statusCode = 404;
                throw new \yii\web\NotFoundHttpException;
            }
        }
        // Проверка ссылок ЧПУ и подставление типа лотов <-End 

        // Мета данные Strat-> 
        $metaData = MetaDate::find()->where(['mdName' => $url])->one();

        Yii::$app->params['description'] = $metaData->mdDescription;
        Yii::$app->params['text'] = $metaData->mdText;
        Yii::$app->params['title'] = ($metaData->mdTitle)? $metaData->mdTitle : $title;
        Yii::$app->params['h1'] = ($metaData->mdH1)? $metaData->mdH1 : $title;
        // Мета данные <-End 
        
        // Фильтрация лотов Start->
        $model->load(Yii::$app->request->get());
        $query = $model->search($lotsQuery, $url);

        if ($url != $query['url']) {
            return $this->redirect([$query['url'],Yii::$app->request->get()]);
        }

        $lotsQuery = Clone $query['lots'];
        $lotsPrice = Clone $query['lotsPrice'];
        $lotsCount = Clone $lotsQuery;

        if (!$price = Yii::$app->cache->get($lot_price_cache)) {
            switch ($type) {
                case 'bankrupt':
                        $price = $lotsPrice->select(['min(lot_startprice)','max(lot_startprice)'])->asArray()->one();
                    break;
                case 'arrest':
                        $price = $lotsPrice->select(['min(lots."lotStartPrice")','max(lots."lotStartPrice")'])->asArray()->one();
                    break;
            }
            Yii::$app->cache->set($lot_price_cache, $price, 3600*12);
        }
        $count = $lotsCount->count();

        $modelSort->load(Yii::$app->request->get());
        $lotsQuery = $modelSort->sortBy($lotsQuery, $type);

        $pages = new Pagination(['totalCount' => $count, 'pageSize'=> 10]);

        if (!$lots = Yii::$app->cache->get($lot_list_cache)) {

            $lots = $lotsQuery->offset($pages->offset)
                ->limit($pages->limit)
                ->all();

            Yii::$app->cache->set($lot_list_cache, $lots, 3600*12);
        }
        // Фильтрация лотов <-End 

        // Хлебные крошки Start->
        Yii::$app->params['breadcrumbs'][] = [
            'label' => ' '.$titleType,
            'template' => '<li class="breadcrumb-item active" aria-current="page">{link}</li>',
            'url' => ["/$type"]
        ];
        if ($subcategory != null) {
            Yii::$app->params['breadcrumbs'][] = [
                'label' => ' '.$titleCategory,
                'template' => '<li class="breadcrumb-item active" aria-current="page">{link}</li>',
                'url' => ["/$type/$category"]
            ];
        }
        if ($region != null) {
            Yii::$app->params['breadcrumbs'][] = [
                'label' => ' '.$titleSubcategory,
                'template' => '<li class="breadcrumb-item active" aria-current="page">{link}</li>',
                'url' => ["/$type/$category/$subcategory"]
            ];
        }
        Yii::$app->params['breadcrumbs'][] = [
            'label' => ' '.Yii::$app->params['h1'],
            'template' => '<li class="breadcrumb-item active" aria-current="page">{link}</li>',
            'url' => [$url]
        ];
        // Хлебные крошки <-End
        
        $offset = $pages->offset;
        $limit = $pages->limit;
        return $this->render('search', compact('model', 'modelSort', 'type', 'queryCategory', 'lots', 'pages', 'count', 'offset', 'limit', 'price', 'url'));
    }
    public function actionPage($type, $category, $subcategory, $id)
    {
        // Проверка ссылок ЧПУ и подставление типа лотов Strat->
        if (!empty($items = LotsCategory::find()->where(['translit_name'=>$category])->one())) {
            $queryCategory = $items->id;
            $titleCategory = $items->name;
        } else {
            Yii::$app->response->statusCode = 404;
            throw new \yii\web\NotFoundHttpException;
        }

        switch ($type) {
            case 'bankrupt':
                $lot = Lots::findOne($id);
                
                $search  = [
                    '${lotTitle}', 
                    '${lotAddress}', 
                    '${lotStatus}', 
                    '${bnkrName}',
                    '${arbitrName}',
                    '${sroName}',
                    '${etp}',
                    '${tradeType}',
                    '${caseId}', 
                    '${category}',
                    '${subCategory}',
                    '${startPrice}',
                    '${lotPrice}',
                    '${stepPrice}',
                    '${advance}',
                    '${priceType}',
                    '${timeEnd}',
                    '${timeBegin}'
                ];
                $replace = [
                    str_replace('"',"'",$lot->lotTitle),
                    str_replace('"',"'",$lot->lotAddress),
                    str_replace('"',"'",$lot->lotStatus),
                    str_replace('"',"'",$lot->lotBnkrName),
                    str_replace('"',"'",$lot->lotArbtrName),
                    str_replace('"',"'",$lot->lotSroTitle),
                    str_replace('"',"'",$lot->lotEtp),
                    (($lot->lotTradeType != 'PublicOffer')? 'публичное предложение': 'открытый аукцион'),
                    $lot->torgy->case->caseid, 
                    $category_title,
                    $subCategory,
                    Yii::$app->formatter->asCurrency($lot->startprice),
                    Yii::$app->formatter->asCurrency($lot->lotPrice),
                    (($lot->auctionstepunit == 'Percent')? $lot->stepprice.'% ('.Yii::$app->formatter->asCurrency((($lot->lotPrice / 100) * $lot->stepprice)).')' : Yii::$app->formatter->asCurrency($lot->stepprice)),
                    (($lot->advancestepunit == 'Percent')? $lot->advance.'% ('.Yii::$app->formatter->asCurrency((($lot->lotPrice / 100) * $lot->advance)).')' : Yii::$app->formatter->asCurrency($lot->advance)),
                    (($lot->torgy->pricetype == 'Public')? 'Открытая' : 'Закрытая'),
                    Yii::$app->formatter->asDate($lot->torgy->timeend, 'long'),
                    Yii::$app->formatter->asDate($lot->torgy->timebegin, 'long')
                ];

                $metaType = 'lot-page';

                $metaDataType = MetaDate::find()->where(['mdName' => $type])->one();
                $titleType = ($metaDataType->mdH1)? $metaDataType->mdH1 : 'Банкротное имущество';

                if ($subcategory != null) {
                    foreach ($items->bankrupt_categorys_translit as $key => $value) {
                        if ($key == $subcategory) {
                            $querySubcategory = $value['id'];    
                            $titleSubcategory = $value['name'];
                        }
                    }
                    if (empty($querySubcategory)) {
                        Yii::$app->response->statusCode = 404;
                        throw new \yii\web\NotFoundHttpException;
                    }
                }
                break;
            case 'arrest':
                $lot = LotsArrest::findOne($id);

                if ($lot->lotCancelReason != null) {
                    $lotStatus = $lot->lotCancelReason;
                } else if ($lot->lotSuspendReason != null) {
                    $lotStatus = $lot->lotSuspendReason;
                } else {
                    $lotStatus = $lot->lotBidStatusName;
                }

                $search = [
                    '${lotTitle}', 
                    '${lotAddress}', 
                    '${lotStatus}', 
                    '${trgFullName}',
                    '${trgHeadOrg}',
                    '${trgEtpName}',
                    '${trgBidFormName}',
                    '${trgLotCount}', 
                    '${lotCategory}',
                    '${lotStartPrice}',
                    '${lotPriceStep}',
                    '${lotMinPrice}',
                    '${lotDepositSize}',
                    '${trgPublished}',
                    '${trgExpireDate}',
                    '${trgStartDateRequest}',
                    '${trgOpeningDate}'
                ];

                $replace = [
                    str_replace('"',"'",$lot->lotTitle),
                    str_replace('"',"'",$lot->lotKladrLocationName),
                    str_replace('"',"'",$lotStatus),
                    str_replace('"',"'",$lot->torgs->trgFullName),
                    str_replace('"',"'",$lot->torgs->trgHeadOrg),
                    str_replace('"',"'",$lot->torgs->trgEtpName),
                    $lot->torgs->trgBidFormName,
                    $lot->torgs->trgLotCount, 
                    $lot->lotPropKindName,
                    Yii::$app->formatter->asCurrency($lot->lotStartPrice),
                    Yii::$app->formatter->asCurrency($lot->lotPriceStep),
                    Yii::$app->formatter->asCurrency($lot->lotMinPrice),
                    Yii::$app->formatter->asCurrency($lot->lotDepositSize),
                    Yii::$app->formatter->asDate($lot->torgs->trgPublished, 'long'),
                    Yii::$app->formatter->asDate($lot->torgs->trgExpireDate, 'long'),
                    Yii::$app->formatter->asDate($lot->torgs->trgStartDateRequest, 'long'),
                    Yii::$app->formatter->asDate($lot->torgs->trgOpeningDate, 'long')
                ];

                $metaType = 'lot-arrest-page';

                $metaDataType = MetaDate::find()->where(['mdName' => $type])->one();
                $titleType = ($metaDataType->mdH1)? $metaDataType->mdH1 : 'Арестованное имущество';

                if ($subcategory != null) {
                    foreach ($items->arrest_categorys_translit as $key => $value) {
                        if ($key == $subcategory) {
                            $querySubcategory = $value['id'];
                            $titleSubcategory = $value['name'];
                        }
                    }
                    if (empty($querySubcategory)) {
                        Yii::$app->response->statusCode = 404;
                        throw new \yii\web\NotFoundHttpException;
                    }
                }
                break;
            default:
                Yii::$app->response->statusCode = 404;
                throw new \yii\web\NotFoundHttpException;
                break;
        }
        // Проверка ссылок ЧПУ и подставление типа лотов <-End 

        // Мета данные Strat-> 
        $metaData = MetaDate::find()->where(['mdName' => $metaType])->one();
        
        Yii::$app->params['description'] = str_replace($search, $replace, $metaData->mdDescription);
        Yii::$app->params['title'] = str_replace($search, $replace, $metaData->mdTitle);
        Yii::$app->params['h1'] = str_replace($search, $replace, $metaData->mdH1);
        Yii::$app->params['text'] = $metaData->mdText;
        // Мета данные <-End 

        // Хлебные крошки Start->
        Yii::$app->params['breadcrumbs'][] = [
            'label' => ' '.$titleType,
            'template' => '<li class="breadcrumb-item active" aria-current="page">{link}</li>',
            'url' => ["/$type"]
        ];
        Yii::$app->params['breadcrumbs'][] = [
            'label' => ' '.$titleCategory,
            'template' => '<li class="breadcrumb-item active" aria-current="page">{link}</li>',
            'url' => ["/$type/$category"]
        ];
        Yii::$app->params['breadcrumbs'][] = [
            'label' => ' '.$titleSubcategory,
            'template' => '<li class="breadcrumb-item active" aria-current="page">{link}</li>',
            'url' => ["/$type/$category/$subcategory"]
        ];
        Yii::$app->params['breadcrumbs'][] = [
            'label' => ' '.Yii::$app->params['h1'],
            'template' => '<li class="breadcrumb-item active" aria-current="page">{link}</li>',
            'url' => ["$type/$category/$subcategory/$id"]
        ];
        // Хлебные крошки <-End

        return $this->render("page-$type", ['lot'=>$lot, 'type'=>$type]);
    }
    public function actionLoad_category()
    {
        $post = Yii::$app->request->post();
        
        switch ($post['type']) {
            case 'bankrupt':
                    if ($post['get'] == 'category') {
                        $lotsCategory = LotsCategory::find()->where(['not', ['bankrupt_categorys' => null]])->orderBy('id ASC')->all();
                        $categorys = '<option value="0">Все категории</option>';
                        foreach ($lotsCategory as $key => $value) {
                            $categorys .= '<option value="'.$key.'">'.$value['name'].'</option>';
                        }
                        return $categorys;
                    } else {
                        $lotsCategory = LotsCategory::findOne($post['id']);
                        $lotsSubcategory = '<option value="0">Все подкатегории</option>';
                        if ($lotsCategory->bankrupt_categorys != null) {
                            foreach ($lotsCategory->bankrupt_categorys as $key => $value) {
                                $lotsSubcategory .= '<option value="'.$key.'">'.$value['name'].'</option>';
                            }
                        }
                    }
                break;
            case 'arrest':
                    if ($post['get'] == 'category') {
                        $lotsCategory = LotsCategory::find()->where(['not', ['bankrupt_categorys' => null]])->orderBy('id ASC')->all();
                        $categorys = '<option value="0">Все категории</option>';
                        foreach ($lotsCategory as $key => $value) {
                            $categorys .= '<option value="'.$key.'">'.$value['name'].'</option>';
                        }
                        return $categorys;
                    } else {
                        $lotsCategory = LotsCategory::findOne($post['id']);
                        $lotsSubcategory = '<option value="0">Все подкатегории</option>';
                        if ($lotsCategory->arrest_categorys != null) {
                            foreach ($lotsCategory->arrest_categorys as $key => $value) {
                                $lotsSubcategory .= '<option value="'.$key.'">'.$value['name'].'</option>';
                            }
                        }
                    }
                break;
            default:
                    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    return ['error'=>'не задан параметр type'];
                break;
        }
        
        return $lotsSubcategory;
    }

    public function actionWish_list()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        if (!Yii::$app->user->isGuest) {

            $post = Yii::$app->request->post();

            $model = new WishListEdit();

            $model->lotId = $post['lotId'];
            $model->type = $post['type'];
    
            return $model->wishEdit();
        } else {
            return false;
        }
        
    }

    public function actionLot_service()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        
        if (!Yii::$app->user->isGuest) {

            $post = Yii::$app->request->post();

            $model = new ServiceLotForm();

            if ($model->load(Yii::$app->request->post())) {
                return $model->send();
            }
        } else {
            return false;
        }
        
    }
}
