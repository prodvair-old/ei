<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;
use yii\data\Pagination;

use common\models\Query\Zalog\LotsZalog;
use common\models\Query\Zalog\LotsZalogUpdate;

use arogachev\excel\import\advanced\Importer;

use frontend\models\UserSetting;
use frontend\models\UploadZalogLotImage;
use frontend\models\ZalogLotCategorySet;
use frontend\models\zalog\FilterLots;

use common\models\Query\WishList;

/**
 * User controller
 */
class UserController extends Controller
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
  public function actionIndex()
  {
    if (!Yii::$app->user->isGuest) {
      return $this->render('index');
    } else {
      return $this->goHome();
    }
  }
  public function actionLots()
  {
      if (!Yii::$app->user->isGuest && Yii::$app->user->identity->role == 'agent') {
        $modelImport = new \yii\base\DynamicModel([
            'fileImport'=>'File Import',
        ]);
        $modelImport->addRule(['fileImport'],'required');
        $modelImport->addRule(['fileImport'],'file',['extensions'=>'xls,xlsx,xml'],['maxSize'=>1024*1024]);

        if(Yii::$app->request->post()){
          $modelImport->fileImport = \yii\web\UploadedFile::getInstance($modelImport,'fileImport');
          if($modelImport->fileImport && $modelImport->validate()){
            try {
            
            if ($modelImport->fileImport->getExtension() === 'xml') {
              $xml = simplexml_load_file($modelImport->fileImport->tempName);

              $model = new LotsZalog();

              foreach ($xml as $key => $value) {
                  if ($key = 'generation-date') {
                      $model->publicationDate = $value;
                  }
                  if ($key = 'offer') {
                      $model->internalId          = (string)$value['internal-id'];
                      $model->tradeType           = (string)$value->type;
                  }
              }

            } else {
              $inputFileType = \PHPExcel_IOFactory::identify($modelImport->fileImport->tempName);
              $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
              $objPHPExcel = $objReader->load($modelImport->fileImport->tempName);
              $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
              $baseRow = 3;
              $loadCount = 0;
              while(!empty($sheetData[$baseRow]['B'])){
                if (!LotsZalog::find()->where(['lotId'=>(string)$sheetData[$baseRow]['A'], 'contactPersonId' => Yii::$app->user->id])->one()) {
                  $model = new LotsZalog();
                  $model->lotId               = (string)$sheetData[$baseRow]['A'];
                  $model->title               = mb_substr((string)$sheetData[$baseRow]['B'], 0, 150, 'UTF-8');
                  $model->description         = (string)$sheetData[$baseRow]['C'];
                  $model->publicationDate     = Yii::$app->formatter->asDate(str_replace('/', '-',(string)$sheetData[$baseRow]['D']), 'php:Y-m-d H:i:s');
                  $model->startingDate        = Yii::$app->formatter->asDate(str_replace('/', '-',(string)$sheetData[$baseRow]['E']), 'php:Y-m-d H:i:s');
                  $model->endingDate          = Yii::$app->formatter->asDate(str_replace('/', '-',(string)$sheetData[$baseRow]['F']), 'php:Y-m-d H:i:s');
                  $model->completionDate      = Yii::$app->formatter->asDate(str_replace('/', '-',(string)$sheetData[$baseRow]['G']), 'php:Y-m-d H:i:s');
                  $model->startingPrice       = floatval(str_replace(' ', '',$sheetData[$baseRow]['H']));
                  $model->step                = floatval(str_replace(' ', '',$sheetData[$baseRow]['I']));
                  $model->stepCount           = (int)$sheetData[$baseRow]['J'];
                  $model->country             = (string)$sheetData[$baseRow]['K'];
                  $model->city                = (string)$sheetData[$baseRow]['L'];
                  $model->address             = (string)$sheetData[$baseRow]['M'];
                  $model->tradeType           = (string)$sheetData[$baseRow]['N'];
                  $model->tradeTipeId         = ((string)$sheetData[$baseRow]['N'] == 'Аукцион')? 0 : 1;
                  $model->procedureDate       = Yii::$app->formatter->asDate(str_replace('/', '-',(string)$sheetData[$baseRow]['O']), 'php:Y-m-d H:i:s');
                  $model->conclusionDate      = Yii::$app->formatter->asDate(str_replace('/', '-',(string)$sheetData[$baseRow]['P']), 'php:Y-m-d H:i:s');
                  $model->viewInfo            = (string)$sheetData[$baseRow]['Q'];
                  $model->collateralPrice     = floatval(str_replace(' ', '',$sheetData[$baseRow]['R']));
                  $model->paymentDetails      = (string)$sheetData[$baseRow]['S'];
                  $model->additionalConditions = (string)$sheetData[$baseRow]['T'];
                  $model->currentPeriod       = (string)$sheetData[$baseRow]['U'];
                  $model->contactPersonId     = Yii::$app->user->id;
                  $model->ownerId             = Yii::$app->user->identity->ownerId;

                  if (Yii::$app->params['exelParseResult'][$baseRow]['status'] = $model->save()) {
                      $loadCount++;
                  } else {
                      Yii::$app->params['exelParseResult'][$baseRow]['info'] = $model->errors;
                  }
                    
                }
                $baseRow++;
              }
              Yii::$app->getSession()->setFlash('success','Success');
            }

            } catch (\Throwable $th) {
              Yii::$app->getSession()->setFlash('error','Error');
              Yii::$app->params['exelParseResult'][$baseRow]['info'] = 'не верный тип файла';
            }
          } else {
              Yii::$app->getSession()->setFlash('error','Error');
          }
        }

      $lotsQuerys = LotsZalog::find()->joinWith('categorys');

      $modelFilter = new FilterLots();

      $modelFilter->load(Yii::$app->request->get());
      $lotsQuery = $modelFilter->search($lotsQuerys);

      $lotsCountQuery = clone $lotsQuery;

      $lotsCount = $lotsCountQuery->count();
      $pages = new Pagination(['totalCount' => $lotsCount, 'pageSize' => 20]);

      $lots = $lotsQuery->offset($pages->offset)->limit($pages->limit)->all();

      return $this->render('lots', [
        'modelImport' => $modelImport,
        'lots' => $lots,
        'lotsCount' => $lotsCount,
        'pages' => $pages,
        'loadCount' => $loadCount
      ]);
    } else {
      return $this->goHome();
    }
  }
  public function actionImportLots()
  {
      if (!Yii::$app->user->isGuest && Yii::$app->user->identity->role == 'agent') {
        $modelImport = new \yii\base\DynamicModel([
            'fileImport'=>'File Import',
        ]);
        $modelImport->addRule(['fileImport'],'required');
        $modelImport->addRule(['fileImport'],'file',['extensions'=>'xls,xlsx,xml'],['maxSize'=>1024*1024]);

        $check = false;
        $where = ['or'];
        if(Yii::$app->request->post()){
          $modelImport->fileImport = \yii\web\UploadedFile::getInstance($modelImport,'fileImport');
          if($modelImport->fileImport && $modelImport->validate()){
            try {

            if ($modelImport->fileImport->getExtension() === 'xml') {
              $xml = (array)simplexml_load_file($modelImport->fileImport->tempName);

              $i = 0;
              foreach ($xml as $key => $then) {
                if ($key == 'generation-date') {
                  $dateGenereate = $then;
                }
                if ($key == 'offer') {
                  if ($then[0] != null) {
                    foreach ($then as $value) {
                    // var_dump($i . ' = '. (string)$value['internal-id']);
                      $model = new LotsZalogUpdate();
                      $model->contactPersonId     = Yii::$app->user->id;
                      $model->ownerId             = Yii::$app->user->identity->ownerId;

                      if (!$lot = LotsZalog::find()->where(['internalId'=>(string)$value['internal-id'], 'contactPersonId' => Yii::$app->user->id])->one()) {
                        $model->internalId          = (string)$value['internal-id'];
                        $images = [];
                        if ($value->image) {
                          foreach ($value->image as $image) {
                            $images[] = ['max' => (string)$image, 'min' => (string)$image];
                          }
                        }
                        $value = (array)$value;
                        $info = [];
                      
                        switch ($value['category']) {
                          case 'commercial':
                              $info['category'] = 'Коммерческая';
                              switch ($value['commercial-building-type']) {
                                case 'business center':
                                    $info['category-building-type'] = 'Бизнес-центр';
                                  break;
                                case 'detached building':
                                    $info['category-building-type'] = 'Отдельно стоящее здание';
                                  break;
                                case 'residential building':
                                    $info['category-building-type'] = 'Встроенное помещение';
                                  break;
                                case 'shopping center':
                                    $info['category-building-type'] = 'Торговый центр';
                                  break;
                                case 'warehouse':
                                    $info['category-building-type'] = 'Складской комплекс';
                                  break;
                              }
                            break;
                          case 'cottage':
                              $info['category'] = 'Коттедж или дача';
                            break;
                          case 'house':
                              $info['category'] = 'Дом';
                            break;
                          case 'house with lot':
                              $info['category'] = 'Дом с участком';
                            break;
                          case 'lot':
                              $info['category'] = 'Участок';
                            break;
                          case 'flat':
                              $info['category'] = 'Квартира';
                            break;
                          case 'room':
                              $info['category'] = 'Комната';
                            break;
                          case 'townhouse':
                              $info['category'] = 'Таунхаус';
                            break;
                          case 'duplex':
                              $info['category'] = 'Дуплекс';
                            break;
                          case 'garage':
                              $info['category'] = 'Гараж';
                              switch ($value['garage-type']) {
                                case 'garage':
                                    $info['category-type'] = 'Гараж';
                                  break;
                                case 'parking place':
                                    $info['category-type'] = 'Машиноместо';
                                  break;
                                case 'box':
                                    $info['category-type'] = 'Бокс';
                                  break;
                              }
                            break;
                          default:
                              $info['category'] = 'Часть дома';
                            break;
                        }
  
                        foreach ($value as $type => $typeValue) {
                          if ($type == 'commercial-type') {
                            switch ($typeValue) {
                              case 'auto repair':
                                  $info['category-type'][] = 'Автосервис';
                                break;
                              case 'business':
                                  $info['category-type'][] = 'Готовый бизнес';
                                break;
                              case 'free purpose':
                                  $info['category-type'][] = 'Помещения свободного назначения';
                                break;
                              case 'hotel':
                                  $info['category-type'][] = 'Гостиница';
                                break;
                              case 'land':
                                  $info['category-type'][] = 'Земли коммерческого назначения';
                                break;
                              case 'legal address':
                                  $info['category-type'][] = 'Юридический адрес';
                                break;
                              case 'manufacturing':
                                  $info['category-type'][] = 'Производственное помещение';
                                break;
                              case 'office':
                                  $info['category-type'][] = 'Офисные помещения';
                                break;
                              case 'public catering':
                                  $info['category-type'][] = 'Общепит';
                                break;
                              case 'retail':
                                  $info['category-type'][] = 'Торговые помещения';
                                break;
                              case 'warehouse':
                                  $info['category-type'][] = 'Склад';
                                break;
                            }
                          }
                          if ($type == 'purpose') {
                            switch ($typeValue) {
                              case 'bank':
                                $info['purpose'][] = 'Помещение для банка';
                                break;
                              case 'beauty shop':
                                $info['purpose'][] = 'Салон красоты';
                                break;
                              case 'food store':
                                $info['purpose'][] = 'Продуктовый магазин';
                                break;
                              case 'medical center':
                                $info['purpose'][] = 'Медицинский центр';
                                break;
                              case 'show room':
                                $info['purpose'][] = 'Шоу-рум';
                                break;
                              case 'touragency':
                                $info['purpose'][] = 'Турагентство';
                                break;
                            }
                          }
                          if ($type == 'purpose-warehouse') {
                            switch ($typeValue) {
                              case 'alcohol':
                                $info['purpose'][] = 'Алкогольный склад';
                                break;
                              case 'pharmaceutical storehouse':
                                $info['purpose'][] = 'Фармацевтический склад';
                                break;
                              case 'vegetable storehouse':
                                $info['purpose'][] = 'Овощехранилище';
                                break;
                            }
                          }
                        }
                        
                        $model->tradeType           = (string)$value['type'];
                        $model->tradeTipeId         = ((string)$value['type'] == 'продажа')? 2 : 3;
                        $model->lotId               = (string)$value['lot-number'];
                        $info['url']                = (string)$value['url'];
                        $info['cadastrNumber']      = (string)$value['cadastral-number'];
                        $model->publicationDate     = (string)($value['creation-date'])? $value['creation-date'] : $dateGenereate ;
                        $model->updatedAt           = (string)$value['last-update-date'];
                        $model->startingPrice       = floatval($value['price']->value);
  
  
                        $location   = (array)$value['location'];
  
                        $model->country             = (string)$location['country'];
                        $model->region              = (string)$location['region'];
                        $model->city                = (string)$location['locality-name'];
                        $model->address             = (string)$location['address'];
                        $info['district']           = (string)$location['district'];
                        $info['sub-locality-name']  = (string)$location['sub-locality-name'];
                        
                        $info['sub-locality-name']  = (string)$location['sub-locality-name'];
                        
  
                        $info['floor']              = (string)$value['floor'];
                        $info['built-year']         = (string)$value['built-year'];
                        $info['area']               = (string)$value['area']->value.' '.(string)$value['area']->unit;
                        switch ($value['deal-status']) {
                          case 'direct rent':
                              $info['deal-status'] = 'Прямая аренда';
                            break;
                          case 'subrent':
                              $info['deal-status'] = 'Субаренда';
                            break;
                          case 'sale of lease rights':
                              $info['deal-status'] = 'Продажа права аренды';
                            break;
                          default:
                              $info['deal-status'] = 'Продажа';
                            break;
                        }
                        $model->info              = $info;
                        $model->images            = $images;
                        $model->description       = (string)$value['description'];
                        $model->title             = (strlen((string)$value['description']) < 80)? (string)$value['description'] : mb_substr((string)$value['description'], 0, 80, 'UTF-8');
                        
                        if (!Yii::$app->params['exelParseResult'][$baseRow]['status'] = $model->save()) {
                          Yii::$app->params['exelParseResult'][$baseRow]['info'] = $model->errors;
                        } else {
                          $check = true;
                          $where[] = ['id' => $model->id];
                          $i++;
                        }
                      }
                    }
                  } else {
                    $value = $then;
                    $model = new LotsZalogUpdate();
                    $model->contactPersonId     = Yii::$app->user->id;
                    $model->ownerId             = Yii::$app->user->identity->ownerId;

                    if (!$lot = LotsZalog::find()->where(['internalId'=>(string)$value['internal-id'], 'contactPersonId' => Yii::$app->user->id])->one()) {
                      $model->internalId          = (string)$value['internal-id'];
                      $images = [];
                      if ($value->image) {
                        foreach ($value->image as $image) {
                          $images[] = ['max' => (string)$image, 'min' => (string)$image];
                        }
                      }
                      $value = (array)$value;
                      $info = [];
                    
                      switch ($value['category']) {
                        case 'commercial':
                            $info['category'] = 'Коммерческая';
                            switch ($value['commercial-building-type']) {
                              case 'business center':
                                  $info['category-building-type'] = 'Бизнес-центр';
                                break;
                              case 'detached building':
                                  $info['category-building-type'] = 'Отдельно стоящее здание';
                                break;
                              case 'residential building':
                                  $info['category-building-type'] = 'Встроенное помещение';
                                break;
                              case 'shopping center':
                                  $info['category-building-type'] = 'Торговый центр';
                                break;
                              case 'warehouse':
                                  $info['category-building-type'] = 'Складской комплекс';
                                break;
                            }
                          break;
                        case 'cottage':
                            $info['category'] = 'Коттедж или дача';
                          break;
                        case 'house':
                            $info['category'] = 'Дом';
                          break;
                        case 'house with lot':
                            $info['category'] = 'Дом с участком';
                          break;
                        case 'lot':
                            $info['category'] = 'Участок';
                          break;
                        case 'flat':
                            $info['category'] = 'Квартира';
                          break;
                        case 'room':
                            $info['category'] = 'Комната';
                          break;
                        case 'townhouse':
                            $info['category'] = 'Таунхаус';
                          break;
                        case 'duplex':
                            $info['category'] = 'Дуплекс';
                          break;
                        case 'garage':
                            $info['category'] = 'Гараж';
                            switch ($value['garage-type']) {
                              case 'garage':
                                  $info['category-type'] = 'Гараж';
                                break;
                              case 'parking place':
                                  $info['category-type'] = 'Машиноместо';
                                break;
                              case 'box':
                                  $info['category-type'] = 'Бокс';
                                break;
                            }
                          break;
                        default:
                            $info['category'] = 'Часть дома';
                          break;
                      }

                      foreach ($value as $type => $typeValue) {
                        if ($type == 'commercial-type') {
                          switch ($typeValue) {
                            case 'auto repair':
                                $info['category-type'][] = 'Автосервис';
                              break;
                            case 'business':
                                $info['category-type'][] = 'Готовый бизнес';
                              break;
                            case 'free purpose':
                                $info['category-type'][] = 'Помещения свободного назначения';
                              break;
                            case 'hotel':
                                $info['category-type'][] = 'Гостиница';
                              break;
                            case 'land':
                                $info['category-type'][] = 'Земли коммерческого назначения';
                              break;
                            case 'legal address':
                                $info['category-type'][] = 'Юридический адрес';
                              break;
                            case 'manufacturing':
                                $info['category-type'][] = 'Производственное помещение';
                              break;
                            case 'office':
                                $info['category-type'][] = 'Офисные помещения';
                              break;
                            case 'public catering':
                                $info['category-type'][] = 'Общепит';
                              break;
                            case 'retail':
                                $info['category-type'][] = 'Торговые помещения';
                              break;
                            case 'warehouse':
                                $info['category-type'][] = 'Склад';
                              break;
                          }
                        }
                        if ($type == 'purpose') {
                          switch ($typeValue) {
                            case 'bank':
                              $info['purpose'][] = 'Помещение для банка';
                              break;
                            case 'beauty shop':
                              $info['purpose'][] = 'Салон красоты';
                              break;
                            case 'food store':
                              $info['purpose'][] = 'Продуктовый магазин';
                              break;
                            case 'medical center':
                              $info['purpose'][] = 'Медицинский центр';
                              break;
                            case 'show room':
                              $info['purpose'][] = 'Шоу-рум';
                              break;
                            case 'touragency':
                              $info['purpose'][] = 'Турагентство';
                              break;
                          }
                        }
                        if ($type == 'purpose-warehouse') {
                          switch ($typeValue) {
                            case 'alcohol':
                              $info['purpose'][] = 'Алкогольный склад';
                              break;
                            case 'pharmaceutical storehouse':
                              $info['purpose'][] = 'Фармацевтический склад';
                              break;
                            case 'vegetable storehouse':
                              $info['purpose'][] = 'Овощехранилище';
                              break;
                          }
                        }
                      }
                      
                      $model->tradeType           = (string)$value['type'];
                      $model->tradeTipeId         = ((string)$value['type'] == 'продажа')? 2 : 3;
                      $model->lotId               = (string)$value['lot-number'];
                      $info['url']                = (string)$value['url'];
                      $info['cadastrNumber']      = (string)$value['cadastral-number'];
                      $model->publicationDate     = (string)($value['creation-date'])? $value['creation-date'] : $dateGenereate ;
                      $model->updatedAt           = (string)$value['last-update-date'];
                      $model->startingPrice       = floatval($value['price']->value);


                      $location   = (array)$value['location'];

                      $model->country             = (string)$location['country'];
                      $model->region              = (string)$location['region'];
                      $model->city                = (string)$location['locality-name'];
                      $model->address             = (string)$location['address'];
                      $info['district']           = (string)$location['district'];
                      $info['sub-locality-name']  = (string)$location['sub-locality-name'];
                      
                      $info['sub-locality-name']  = (string)$location['sub-locality-name'];
                      

                      $info['floor']              = (string)$value['floor'];
                      $info['built-year']         = (string)$value['built-year'];
                      $info['area']               = (string)$value['area']->value.' '.(string)$value['area']->unit;
                      switch ($value['deal-status']) {
                        case 'direct rent':
                            $info['deal-status'] = 'Прямая аренда';
                          break;
                        case 'subrent':
                            $info['deal-status'] = 'Субаренда';
                          break;
                        case 'sale of lease rights':
                            $info['deal-status'] = 'Продажа права аренды';
                          break;
                        default:
                            $info['deal-status'] = 'Продажа';
                          break;
                      }
                      $model->info              = $info;
                      $model->images            = $images;
                      $model->description       = (string)$value['description'];
                      $model->title             = (strlen((string)$value['description']) < 80)? (string)$value['description'] : mb_substr((string)$value['description'], 0, 80, 'UTF-8');
                      
                      if (!Yii::$app->params['exelParseResult'][$baseRow]['status'] = $model->save()) {
                        Yii::$app->params['exelParseResult'][$baseRow]['info'] = $model->errors;
                      } else {
                        $check = true;
                        $where[] = ['id' => $model->id];
                        $i++;
                      }
                    }
                  }
                  
                }
              }
              Yii::$app->params['exelParseResult'][$baseRow]['count'] = $i;

            } else {
              $inputFileType = \PHPExcel_IOFactory::identify($modelImport->fileImport->tempName);
              $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
              $objPHPExcel = $objReader->load($modelImport->fileImport->tempName);
              $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
              $baseRow = 3;
              $loadCount = 0;
              while(!empty($sheetData[$baseRow]['B'])){
                if (!$lot = LotsZalog::find()->where(['lotId'=>(string)$sheetData[$baseRow]['A'], 'contactPersonId' => Yii::$app->user->id])->one()) {
                  $model = new LotsZalogUpdate();
                  $model->lotId               = (string)$sheetData[$baseRow]['A'];
                  $model->title               = mb_substr((string)$sheetData[$baseRow]['B'], 0, 150, 'UTF-8');
                  $model->description         = (string)$sheetData[$baseRow]['C'];
                  $model->publicationDate     = Yii::$app->formatter->asDate(str_replace('/', '-',(string)$sheetData[$baseRow]['D']), 'php:Y-m-d H:i:s');
                  $model->startingDate        = Yii::$app->formatter->asDate(str_replace('/', '-',(string)$sheetData[$baseRow]['E']), 'php:Y-m-d H:i:s');
                  $model->endingDate          = Yii::$app->formatter->asDate(str_replace('/', '-',(string)$sheetData[$baseRow]['F']), 'php:Y-m-d H:i:s');
                  $model->completionDate      = Yii::$app->formatter->asDate(str_replace('/', '-',(string)$sheetData[$baseRow]['G']), 'php:Y-m-d H:i:s');
                  $model->startingPrice       = floatval(str_replace(' ', '',$sheetData[$baseRow]['H']));
                  $model->step                = floatval(str_replace(' ', '',$sheetData[$baseRow]['I']));
                  $model->stepCount           = (int)$sheetData[$baseRow]['J'];
                  $model->country             = (string)$sheetData[$baseRow]['K'];
                  $model->city                = (string)$sheetData[$baseRow]['L'];
                  $model->address             = (string)$sheetData[$baseRow]['M'];
                  $model->tradeType           = (string)$sheetData[$baseRow]['N'];
                  $model->tradeTipeId         = ((string)$sheetData[$baseRow]['N'] == 'Аукцион')? 0 : 1;
                  $model->procedureDate       = Yii::$app->formatter->asDate(str_replace('/', '-',(string)$sheetData[$baseRow]['O']), 'php:Y-m-d H:i:s');
                  $model->conclusionDate      = Yii::$app->formatter->asDate(str_replace('/', '-',(string)$sheetData[$baseRow]['P']), 'php:Y-m-d H:i:s');
                  $model->viewInfo            = (string)$sheetData[$baseRow]['Q'];
                  $model->collateralPrice     = floatval(str_replace(' ', '',$sheetData[$baseRow]['R']));
                  $model->paymentDetails      = (string)$sheetData[$baseRow]['S'];
                  $model->additionalConditions = (string)$sheetData[$baseRow]['T'];
                  $model->currentPeriod       = (string)$sheetData[$baseRow]['U'];
                  
                  $info = [
                    'basisBidding' => (string)$sheetData[$baseRow]['v'],
                    'dateAuction' => (string)$sheetData[$baseRow]['w'],
                  ];
                  $model->info = $info;
                  $model->contactPersonId     = Yii::$app->user->id;
                  $model->ownerId             = Yii::$app->user->identity->ownerId;

                  if (Yii::$app->params['exelParseResult'][$baseRow]['status'] = $model->save()) {
                      $check = true;
                      $where[] = ['id' => $model->id];
                      $loadCount++;
                  } else {
                      Yii::$app->params['exelParseResult'][$baseRow]['info'] = $model->errors;
                  }
                    
                }
                $baseRow++;
              }
              Yii::$app->getSession()->setFlash('success','Success');
            }
          } catch (\Throwable $th) {
            Yii::$app->getSession()->setFlash('error','Error');
            Yii::$app->params['exelParseResult'][$baseRow]['info'] = 'не верный тип файла';
          }
          } else {
              Yii::$app->getSession()->setFlash('error','Error');
          }
        }

        if ($check) {
          $lotsQuery = LotsZalog::find()->where($where);
    
          $lotsCount = clone $lotsQuery;
          $pages = new Pagination(['totalCount' => $lotsCount->count(), 'pageSize' => 20]);

          $lots = $lotsQuery->offset($pages->offset)->limit($pages->limit)->all();
        }


      return $this->render('import-lots', [
        'modelImport' => $modelImport,
        'loadCount' => $loadCount,
        'pages' => $pages,
        'lots' => $lots
      ]);
    } else {
      return $this->goHome();
    }
  }

  public function actionAddLot()
  {
    if (!Yii::$app->user->isGuest && Yii::$app->user->identity->role == 'agent') {
      $model = new LotsZalog();
      $modelImages = new UploadZalogLotImage();
      $modelCategorys = new ZalogLotCategorySet();

      if ($model->load(Yii::$app->request->post())) {

        $model->contactPersonId     = Yii::$app->user->id;
        $model->ownerId             = Yii::$app->user->identity->ownerId;
        var_dump($model->categoryIds);
        $modelCategorys->categorys    = $model->categoryIds;
        $modelCategorys->subCategorys = $model->subCategory;

        switch ($model->tradeType) {
          case 'Аукцион':
              $model->tradeTipeId = 0;
            break;
          case 'Публичное предложение':
              $model->tradeTipeId = 1;
            break;
          case 'продажа':
              $model->tradeTipeId = 2;
            break;
          default: 
              $model->tradeTipeId = 3;
            break;
        }

        if ($model->validate()) {

          if ($model->save()) {

            $files = UploadedFile::getInstances($model, 'images');

            if ($files) {
              $modelImages->images = $files;
              $modelImages->lotId  = $model->id;

              $modelImages->uploadImages();
            }
            // $modelImages->uploadImages();
          
            
            $modelCategorys->lotId        = $model->id;

            var_dump($modelCategorys->validate());
            var_dump($modelCategorys->errors);

            // $modelCategorys->setCategory();

            if ($modelCategorys->setCategory()) {
              return $this->redirect(['user/edit-lot', 'id'=> $model->id]);
            }

          }

          Yii::$app->getSession()->setFlash('success', 'Success');
        }
      }

      return $this->render('add-lot', [
        'model' => $model,
      ]);
    } else {
      return $this->goHome();
    }
  }

  public function actionEditLot($id)
  {
    if (!Yii::$app->user->isGuest && Yii::$app->user->identity->role == 'agent') {
      $model = LotsZalog::findOne($id);
      $modelImages = new UploadZalogLotImage();
      $modelCategorys = new ZalogLotCategorySet();

      if ($model->load(Yii::$app->request->post())) {

        $model->contactPersonId     = Yii::$app->user->id;
        $model->ownerId             = Yii::$app->user->identity->ownerId;
        var_dump($model->categoryIds);
        $modelCategorys->categorys    = $model->categoryIds;
        $modelCategorys->subCategorys = $model->subCategory;

        switch ($model->tradeType) {
          case 'Аукцион':
              $model->tradeTipeId = 0;
            break;
          case 'Публичное предложение':
              $model->tradeTipeId = 1;
            break;
          case 'продажа':
              $model->tradeTipeId = 2;
            break;
          default: 
              $model->tradeTipeId = 3;
            break;
        }

        if ($model->validate()) {

          if ($model->update()) {

            $files = UploadedFile::getInstances($model, 'images');

            if ($files) {
              $modelImages->images = $files;
              $modelImages->lotId  = $model->id;

              $modelImages->uploadImages();
            }
            // $modelImages->uploadImages();
          
            
            $modelCategorys->lotId        = $model->id;

            var_dump($modelCategorys->validate());
            var_dump($modelCategorys->errors);

            // $modelCategorys->setCategory();

            if ($modelCategorys->setCategory()) {
              return $this->redirect(['user/edit-lot', 'id'=> $model->id]);
            }

          }

          Yii::$app->getSession()->setFlash('success', 'Success');
        }
      }

      return $this->render('edit-lot', [
        'model' => $model,
      ]);
    } else {
      return $this->goHome();
    }
  }

  public function actionSetting()
  {
    if (!Yii::$app->user->isGuest) {

      $model = new UserSetting();
      $model_image = new UserSetting();

      if ($model->load(Yii::$app->request->post())) {
        $model->setting(Yii::$app->user->id);
      }

      return $this->render('setting', compact('model', 'model_image'));
    } else {
      return $this->goHome();
    }
  }
  public function actionSetting_image()
  {
    if (!Yii::$app->user->isGuest) {

      Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

      $model = new UserSetting();

      if ($model->load(Yii::$app->request->post())) {
        $model->photo = UploadedFile::getInstance($model, 'photo');
        $model->passport = UploadedFile::getInstance($model, 'passport');

        return $model->upload(Yii::$app->user->id);
      }

      return false;
    } else {
      return $this->goHome();
    }
  }
  public function actionWish_list()
  {
    if (!Yii::$app->user->isGuest) {

      $wishBankruptCount = WishList::find()->where(['userId' => Yii::$app->user->id, 'type' => 'bankrupt'])->count();
      $pagesBankrupt = new Pagination(['totalCount' => $wishBankruptCount, 'pageSize' => 6]);

      $wishBankruptList = WishList::find()->where(['userId' => Yii::$app->user->id, 'type' => 'bankrupt'])->offset($pagesBankrupt->offset)->limit($pagesBankrupt->limit)->all();


      $wishArrestCount = WishList::find()->where(['userId' => Yii::$app->user->id, 'type' => 'arrest'])->count();
      $pagesArrest = new Pagination(['totalCount' => $wishArrestCount, 'pageSize' => 6]);

      $wishArrestList = WishList::find()->where(['userId' => Yii::$app->user->id, 'type' => 'arrest'])->offset($pagesArrest->offset)->limit($pagesArrest->limit)->all();

      $wishZalogCount = WishList::find()->where(['userId' => Yii::$app->user->id, 'type' => 'zalog'])->count();
      $pagesZalog = new Pagination(['totalCount' => $wishZalogCount, 'pageSize' => 6]);

      $wishZalogList = WishList::find()->where(['userId' => Yii::$app->user->id, 'type' => 'zalog'])->offset($pagesZalog->offset)->limit($pagesZalog->limit)->orderBy('id DESC')->all();

      return $this->render('wish_list', ['wishBankruptList' => $wishBankruptList, 'wishArrestList' => $wishArrestList, 'wishZalogList' => $wishZalogList, 'pagesBankrupt' => $pagesBankrupt, 'pagesArrest' => $pagesArrest, 'pagesZalog' => $pagesZalog]);
    } else {
      return $this->goHome();
    }
  }

  public function actionLotImages()
  {
    if (!Yii::$app->user->isGuest) {

      Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

      $model = new UploadZalogLotImage();

      if (Yii::$app->request->isPost) {

        $files = UploadedFile::getInstances($model, 'images');

        if ($model->load(Yii::$app->request->post())) {
          $model->images = $files;

          return $model->uploadImages();
        }
      }

      return false;
    } else {
      return $this->goHome();
    }
  }
  public function actionLotImagesDel()
  {
    if (!Yii::$app->user->isGuest && Yii::$app->user->identity->role == 'agent') {

      Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

      $data = Yii::$app->request->get();

      $lot = LotsZalog::findOne($data['id']);

      $images = [];

      if ($lot->images) {
        foreach ($lot->images as $image) {
          if ($image['min'] != $data['image']['min'] || $image['max'] != $data['image']['max']) {
            $images[] = $image;
          }
        }

        $lot->images = $images;
        return $lot->save();
      }
      

      return false;
    } else {
      return $this->goHome();
    }
  }
  public function actionLotCategory()
  {
    if (!Yii::$app->user->isGuest) {

      Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

      $model = new ZalogLotCategorySet();

      if ($model->load(Yii::$app->request->post())) {
        return $model->setCategory();
      }

      return false;
    } else {
      return $this->goHome();
    }
  }
  public function actionLotRemove()
  {
    if (!Yii::$app->user->isGuest) {

      Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

      $get = Yii::$app->request->get();

      if ($get['lotId']) {
        $lot = LotsZalogUpdate::findOne((int) $get['lotId']);
        return $lot->delete();
      }

      return false;
    } else {
      return $this->goHome();
    }
  }
  public function actionLotStatus()
  {
        if (!Yii::$app->user->isGuest) {

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            
            $get = Yii::$app->request->get();

            if ($get['lotId']) {
                $lot = LotsZalogUpdate::findOne((int)$get['lotId']);

                if ($lot->categorys[0] != null) {
                    $lot->status = !$lot->status;
                    $lot->update();
                    return ['status' => $lot->status, 'url' => $lot->lotUrl];
                }
            }

      Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

      $get = Yii::$app->request->get();

      if ($get['lotId']) {
        $lot = LotsZalogUpdate::findOne((int) $get['lotId']);

        if ($lot->categorys[0] != null) {
          $lot->status = !$lot->status;
          $lot->update();
          return $lot->status;
        }
      }

      return null;
    } else {
      return $this->goHome();
    }
  }
}
