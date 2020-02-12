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
use common\models\Query\Zalog\LotsZalog;
use common\models\Query\Zalog\OwnerProperty;
use common\models\Query\Lot\Lots;
use common\models\Query\WishList;

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

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    // Главная страница лотов

    public function actionMap()
    {
        $get = Yii::$app->request->get();
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $lotsSearch = Lots::find()
                ->joinWith(['torg'])
                ->alias('lot')
                ->where([
                    'and',
                    "lot.info->'address'->>'geo_lat' >= '".$get['northWest']['lat']."'",
                    "lot.info->'address'->>'geo_lat' <= '".$get['southEast']['lat']."'",
                    "lot.info->'address'->>'geo_lon' >= '".$get['northWest']['lng']."'",
                    "lot.info->'address'->>'geo_lon' <= '".$get['southEast']['lng']."'",
                    '(torg."endDate" >= NOW() OR torg."endDate" IS NULL) OR (torg."completeDate" >= NOW() OR torg."completeDate" IS NULL)'
                ])
                ->all();

        foreach ($lotsSearch as $key => $lotSearch) {
            $lots[] = [
                'id'        => $lotSearch->id,
                'title'     => $lotSearch->title,
                'address'   => $lotSearch->info['address']['district'].', '.$lotSearch->info['address']['region'].', '.$lotSearch->info['address']['city'].', '.$lotSearch->info['address']['street'],
                'price'     => $lotSearch->price,
                'link'      => Yii::$app->request->hostInfo.'/'.$lotSearch->url,
                'position'  => [
                    'lat'  => $lotSearch->info['address']['geo_lat'],
                    'lng'  => $lotSearch->info['address']['geo_lon'],
                ]
            ];
        }

        return $lots;
    }

    public function actionIndex($type)
    {
        $start = microtime(true);

        $owner = null;

        switch ($type) {
            case 'bankrupt':
                $title = 'Банкротное имущество';
                $queryType = $type;
                break;
            case 'arrest':
                $title = 'Арестованное имущество';
                $queryType = $type;
                break;
            case 'zalog':
                $title = 'Имущество организаций';
                $queryType = $type;
                break;
            default:
                $owner = OwnerProperty::find()->where(['linkForEi' => $type])->one();
                if (!empty($owner)) {
                    $lots = LotsZalog::find()->where(['status'=>true, 'ownerId' => $owner->id])->limit(3)->orderBy('"publicationDate" DESC')->all();
                    $lotsFovarit = LotsZalog::find()->where(['status'=>true, 'ownerId' => $owner->id])->andWhere('"completionDate" >= NOW()')->limit(3)->orderBy('"publicationDate" DESC')->all();

                    $title = $owner->name;
                } else {
                    Yii::$app->response->statusCode = 404;
                    throw new \yii\web\NotFoundHttpException;
                }
                break;
        }

        // Проверка ссылок ЧПУ и подставление типа лотов Strat->
        $lots = Lots::find()
                ->joinWith(['torg'])
                ->alias('lot')
                ->where(['and', ['torg.type' => $queryType, 'published' => true], ['>=', 'torg.completeDate', 'NOW()']])
                ->orderBy('torg.publishedDate DESC')
                ->limit(3)
                ->all();

        $lotsFovarit = Lots::find()
                ->select([
                    '*',
                    'whishCount' => WishList::find()
                        ->select(['COUNT(id)'])
                        ->alias('wl')
                        ->where('wl."lotId" = lot.id OR wl."lotId" = lot."oldId"')
                        ->andWhere(['type' => $queryType])
                ])
                ->joinWith(['torg'])
                ->alias('lot')
                ->where(['and', ['torg.type' => $queryType, 'published' => true], ['>=', 'torg.completeDate', 'NOW()']])
                ->limit(3)
                ->orderBy('whishCount DESC')
                ->all();

        switch ($type) {
            case 'bankrupt':
                $title = 'Банкротное имущество';
                break;
            case 'arrest':
                $title = 'Арестованное имущество';
                break;
            case 'zalog':
                $title = 'Имущество организаций';
                break;
            default:
                $owner = OwnerProperty::find()->where(['linkForEi' => $type])->one();
                if (!empty($owner)) {
                    $lots = LotsZalog::find()->where(['status'=>true, 'ownerId' => $owner->id])->limit(3)->orderBy('"publicationDate" DESC')->all();
                    $lotsFovarit = LotsZalog::find()->where(['status'=>true, 'ownerId' => $owner->id])->andWhere('"completionDate" >= NOW()')->limit(3)->orderBy('"publicationDate" DESC')->all();

                    $title = $owner->name;
                } else {
                    Yii::$app->response->statusCode = 404;
                    throw new \yii\web\NotFoundHttpException;
                }
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
        // var_dump('Время генерации: ' . ( microtime(true) - $start ) . ' сек.');

        return $this->render('index', compact('type', 'lots', 'lotsFovarit', 'owner'));
    }

    // Ссылка на категории лотов
    public function actionSearch($type, $category, $subcategory = null, $region = null)
    {
        $start = microtime(true);
        $model = new SearchLot();
        $modelSort = new SortLot();
        $urlParamServer = $_SERVER['REQUEST_URI'];
        
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

        $model->type = ($type == 'bankrupt' || $type == 'arrest' || $type == 'zalog' || $type == 'all')? $type : 'zalog';
        
        $lotsQuery = Lots::find()->alias('lot')->joinWith(['categorys', 'torg', 'thisPriceHistorys']);

        $metaDataType = MetaDate::find()->where(['mdName' => $type])->one();

        switch ($type) {
            case 'all':
                $titleType = ($metaDataType->mdH1)? $metaDataType->mdH1 : 'Все виды иммущества`';

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
                    foreach ($items->arrest_categorys_translit as $key => $value) {
                        if ($key == $subcategory) {
                            $querySubcategory = $value['id'];    
                            $model->subCategory[0] = $value['id'];    
                            $titleSubcategory = $value['name'];
                            $title = $value['name'];
                            $url = "$type/$category/$subcategory";
                        }
                    }
                    foreach ($items->zalog_categorys_translit as $key => $value) {
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
            case 'bankrupt':
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
            case 'zalog':
                $titleType = ($metaDataType->mdH1)? $metaDataType->mdH1 : 'Имущество организаций';

                if ($subcategory != null) {
                    foreach ($items->zalog_categorys_translit as $key => $value) {
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
                $owner = OwnerProperty::find()->where(['linkForEi' => $type])->one();
                if (!empty($owner)) {
                    $lotsQuery = LotsZalog::find()->joinWith('categorys');
                    $lotsPrice = LotsZalog::find()->joinWith('categorys');

                    $metaDataType = MetaDate::find()->where(['mdName' => $type])->one();
                    $titleType = ($metaDataType->mdH1)? $metaDataType->mdH1 : $owner->name;

                    $model->etp[0] = $owner->id; 

                    if ($subcategory != null) {
                        foreach ($items->zalog_categorys_translit as $key => $value) {
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
                } else {
                    Yii::$app->response->statusCode = 404;
                    throw new \yii\web\NotFoundHttpException;
                }
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
        $modelSort->load(Yii::$app->request->get());
        
        $model->load(Yii::$app->request->get());
        $query = $model->search($lotsQuery, $url, (($type !== 'bankrupt' || $type !== 'arrest' || $type !== 'zalog' || $type == 'all')? $type : null), $modelSort->sortBy());

        /* http://dev.ei.ru/rosselkhozbank/lot-list */
        $urlArray = explode('/', $url);

        if ($urlArray[0] != $model->type && ($urlArray[0] == 'all' || $urlArray[0] == 'bankrupt' || $urlArray[0] == 'arrest' || $urlArray[0] == 'zalog')) {
            $urlArray[0] = $model->type;
            $url = implode('/',$urlArray);
            return $this->redirect([$url,Yii::$app->request->get()]);
        } else if ($url != $query['url']) {
            return $this->redirect([$query['url'],Yii::$app->request->get()]);
        }

        $lotsQuery = Clone $query['lots'];
        $lotsPrice = Clone $query['lotsPrice'];
        $lotsCount = Clone $lotsQuery;

        $price = $lotsPrice->select([
                'intervalMax' => 'max("thisPriceHistorys".price)', 
                'max' => 'max("startPrice")',
                'intervalMin' => 'min("thisPriceHistorys".price)', 
                'min' => 'min("startPrice")',
            ])->asArray()->one();

        // switch ($type) {
        //     case 'bankrupt':
        //             $price = $lotsPrice->select(['min(lot_startprice)','max(lot_startprice)'])->asArray()->one();
        //         break;
        //     case 'arrest':
        //             $price = $lotsPrice->select(['min(lots."lotStartPrice")','max(lots."lotStartPrice")'])->asArray()->one();
        //         break;
        //     default:
        //             $price = $lotsPrice->select(['min("startingPrice")','max("startingPrice")'])->asArray()->one();
        //         break;
        // }

        $count = $lotsCount->count();

        $pages = new Pagination(['totalCount' => $count, 'pageSize'=> 10]);

        // var_dump($lotsQuery
        //     ->offset($pages->offset)
        //     ->limit($pages->limit)->createCommand()->getRawSql()
        // );
        
        $lots = $lotsQuery->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        // Фильтрация лотов <-End 
        // var_dump('Время генерации: ' . ( microtime(true) - $start ) . ' сек.');
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
        $type = ($type == 'bankrupt' || $type == 'arrest' || $type == 'zalog')? $type : 'zalog';
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

        $lot = Lots::findOne($id);

        if (!$lot) {
            Yii::$app->response->statusCode = 404;
            throw new \yii\web\NotFoundHttpException;
        }

        if ($subcategory != null) {
            foreach ($items->bankrupt_categorys_translit as $key => $value) {
                if ($key == $subcategory) {
                    $querySubcategory = $value['id'];
                    $titleSubcategory = $value['name'];
                }
            }
            foreach ($items->arrest_categorys_translit as $key => $value) {
                if ($key == $subcategory) {
                    $querySubcategory = $value['id'];
                    $titleSubcategory = $value['name'];
                }
            }
            foreach ($items->zalog_categorys_translit as $key => $value) {
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
            str_replace('"',"'",$lot->title),
            str_replace('"',"'",$lot->district.''.$lot->info['address']['region'].''.$lot->city.''.$lot->info['address']['street']),
            str_replace('"',"'",$lot->status),
            str_replace('"',"'",$lot->torg->bankrupt->name),
            str_replace('"',"'",$lot->torg->publisher->fullName),
            str_replace('"',"'",$lot->torg->publisher->sro->title),
            str_replace('"',"'",$lot->torg->etp->title),
            (($lot->torg->tradeType == 0)? 'публичное предложение': 'открытый аукцион'),
            $lot->torg->case->number, 
            $titleCategory,
            $titleSubcategory,
            Yii::$app->formatter->asCurrency($lot->startPrice),
            Yii::$app->formatter->asCurrency($lot->price),
            (($lot->stepTypeId == 1)? $lot->step.'% ('.Yii::$app->formatter->asCurrency((($lot->price / 100) * $lot->step)).')' : Yii::$app->formatter->asCurrency($lot->step)),
            (($lot->depositTypeId == 1)? $lot->deposit.'% ('.Yii::$app->formatter->asCurrency((($lot->price / 100) * $lot->deposit)).')' : Yii::$app->formatter->asCurrency($lot->deposit)),
            (($lot->torg->info['priceType'] == 'Public')? 'Открытая' : 'Закрытая'),
            Yii::$app->formatter->asDate($lot->torg->startDate, 'long'),
            Yii::$app->formatter->asDate($lot->torg->endDate, 'long')
        ];

        switch ($type) {
            case 'bankrupt':

                $metaType = 'lot-page';

                $metaDataType = MetaDate::find()->where(['mdName' => $type])->one();
                $titleType = ($metaDataType->mdH1)? $metaDataType->mdH1 : 'Банкротное имущество';

                // Хлебные крошки Start->
                Yii::$app->params['breadcrumbs'][] = [
                    'label' => ' '.$titleType,
                    'template' => '<li class="breadcrumb-item active" aria-current="page">{link}</li>',
                    'url' => ["/$type"]
                ];
                // Хлебные крошки <-End

                break;
            case 'arrest':
                $metaType = 'lot-arrest-page';

                $metaDataType = MetaDate::find()->where(['mdName' => $type])->one();
                $titleType = ($metaDataType->mdH1)? $metaDataType->mdH1 : 'Арестованное имущество';

                // Хлебные крошки Start->
                Yii::$app->params['breadcrumbs'][] = [
                    'label' => ' '.$titleType,
                    'template' => '<li class="breadcrumb-item active" aria-current="page">{link}</li>',
                    'url' => ["/$type"]
                ];
                // Хлебные крошки <-End
                break;
            case 'zalog':
                $metaType = 'lot-zalog-page';

                $metaDataType = MetaDate::find()->where(['mdName' => $type])->one();
                $titleType = ($metaDataType->mdH1)? $metaDataType->mdH1 : 'Имущество организаций';

                // Хлебные крошки Start->
                Yii::$app->params['breadcrumbs'][] = [
                    'label' => ' '.$titleType,
                    'template' => '<li class="breadcrumb-item active" aria-current="page">{link}</li>',
                    'url' => ["/$type"]
                ];
                // Хлебные крошки <-End
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
            'label' => ' '.((Yii::$app->params['h1'])? Yii::$app->params['h1'] : $lot->title),
            'template' => '<li class="breadcrumb-item active" aria-current="page">{link}</li>',
            'url' => ["$type/$category/$subcategory/$id"]
        ];
        // Хлебные крошки <-End

        return $this->render("page-lot", ['lot'=>$lot, 'type'=>$type]);
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
                        $lotsCategory = LotsCategory::find()->where(['not', ['arrest_categorys' => null]])->orderBy('id ASC')->all();
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
            case 'zalog':
                if ($post['get'] == 'category') {
                    $lotsCategory = LotsCategory::find()->where(['not', ['zalog_categorys' => null]])->orderBy('id ASC')->all();
                    $categorys = '<option value="0">Все категории</option>';
                    foreach ($lotsCategory as $key => $value) {
                        $categorys .= '<option value="'.$key.'">'.$value['name'].'</option>';
                    }
                    return $categorys;
                } else {
                    $lotsCategory = LotsCategory::findOne($post['id']);
                    $lotsSubcategory = '<option value="0">Все подкатегории</option>';
                    if ($lotsCategory->zalog_categorys != null) {
                        foreach ($lotsCategory->zalog_categorys as $key => $value) {
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
