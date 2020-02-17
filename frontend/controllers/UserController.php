<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;
use yii\data\Pagination;
use moonland\phpexcel\Excel;

use common\models\Query\Zalog\LotsZalog;
use common\models\Query\Lot\Lots;
use common\models\Query\Lot\LotsAll;
use common\models\Query\Zalog\LotsZalogUpdate;
use common\models\Query\Arrest\LotsArrest;

use arogachev\excel\import\advanced\Importer;

use frontend\models\UserSetting;
use frontend\models\UploadZalogLotImage;
use frontend\models\ZalogLotCategorySet;
use frontend\models\ImportZalog;
use frontend\models\zalog\FilterLots;
use frontend\models\arrestBankrupt\importFIleForm;

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

      $lotsQuerys = LotsAll::find()->joinWith('torg')->where(['torg.publisherId' => Yii::$app->user->id, 'torg.ownerId' => Yii::$app->user->identity->ownerId]);

      $modelFilter = new FilterLots();

      // $modelFilter->load(Yii::$app->request->get());
      // $lotsQuery = $modelFilter->search($lotsQuerys);

      $lotsCountQuery = clone $lotsQuerys;

      $lotsCount = $lotsCountQuery->count();
      $pages = new Pagination(['totalCount' => $lotsCount, 'pageSize' => 20]);

      $lots = $lotsQuerys->offset($pages->offset)->limit($pages->limit)->all();

      return $this->render('lots', [
        'lots' => $lots,
        'lotsCount' => $lotsCount,
        'pages' => $pages,
      ]);
    } else {
      return $this->goHome();
    }
  }
  public function actionGetArrestBankrupt()
  {
    if (!Yii::$app->user->isGuest && Yii::$app->user->identity->role == 'agent') {
      $modelImport = new importFIleForm();

      if(Yii::$app->request->post()){
        ini_set('memory_limit', '1024M');
        $modelImport->fileImport = \yii\web\UploadedFile::getInstance($modelImport,'fileImport');
        
        if($modelImport->fileImport && $modelImport->validate()){
          $where = $modelImport->excel();

          $lots = LotsArrest::find()->where($where)->all();

          if ($lots[0] != null) {
          
            header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

            Excel::export([
                'models' => $lots,
                'columns' => [
                    'lotId:text',
                    [
                        'attribute' => 'lotUrl',
                        'header' => 'Ссылка на лот',
                        'format' => 'text',
                        'value' => function($model) {
                            return 'https://ei.ru/'.$model->lotUrl;
                        },
                    ],
                    'torgs.trgNotificationUrl:text',
                    'lotPropName:text',
                    'lotTorgReason:text',
                    'torgs.trgBidAuctionDate:datetime',
                    'torgs.trgBidFormName:text',
                    'torgs.trgPublished:datetime',
                    'lotWinnerName:text',
                    'lotContractPrice:text',
                    'lotStartPrice:text',
                    'lotCadastre:text',
                    'lotVin:text',
                    'lotKladrLocationName:text',
                    [
                        'attribute' => 'lotCategory',
                        'header' => 'Категория лота',
                        'format' => 'text',
                        'value' => function($model) {
                            return $model->lotCategory[0];
                        },
                    ],
                    'lot_archive:boolean',
                ],
                'headers' => [
                    'lotId' => 'ID лота',
                    'torgs.trgNotificationUrl' => 'Ссылка на извещение',
                    'lotPropName' => 'Описание',
                    'lotTorgReason' => 'Основания реализации торгов',
                    'torgs.trgBidAuctionDate' => 'Дата и время проведения торгов',
                    'torgs.trgBidFormName' => 'Форма торгов',
                    'torgs.trgPublished' => 'Дата публикации',
                    'lotWinnerName' => 'Победитель',
                    'lotContractPrice' => 'Цена предложенное победителем',
                    'lotCadastre' => 'Кадастровый номер',
                    'lotVin' => 'VIN номер',
                    'lotKladrLocationName' => 'Адрес',
                    'lot_archive' => 'В архиве',
                ],
            ]);
          } else {
            Yii::$app->params['exelParseResult']['status'] = 'Не удалось найти данные';
          }

        } else {
          Yii::$app->getSession()->setFlash('error','Error');
        }
      }

      return $this->render('get-arrest', [
        'modelImport' => $modelImport,
      ]);

    } else {
      return $this->goHome();
    }
  }
  public function actionImportLots()
  {
      if (!Yii::$app->user->isGuest && Yii::$app->user->identity->role == 'agent') {
        $modelImport = new ImportZalog();

        if(Yii::$app->request->post()){
          $modelImport->fileImport = \yii\web\UploadedFile::getInstance($modelImport,'fileImport');
          if($modelImport->fileImport && $modelImport->validate()){
            // try {
              if ($modelImport->fileImport->getExtension() === 'xml') {
                $result = $modelImport->xml();
              } else {
                $result = $modelImport->excel();
              }
            // } catch (\Throwable $th) {
            //   var_dump()
            //   Yii::$app->getSession()->setFlash('error','Error');
            // }
          } else {
            Yii::$app->getSession()->setFlash('error','Error');
          }
        }

        if ($result['check']) {
          $lotsQuery = LotsAll::find()->where($result['where']);
    
          $lotsCount = clone $lotsQuery;
          $pages = new Pagination(['totalCount' => $lotsCount->count(), 'pageSize' => 20]);

          $lots = $lotsQuery->offset($pages->offset)->limit($pages->limit)->all();
        }


      return $this->render('import-lots', [
        'modelImport' => $modelImport,
        'loadCount' => $result['loadCount'],
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
        $lot = LotsAll::findOne((int)$get['lotId']);
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
          $lot = LotsAll::findOne((int)$get['lotId']);

          if ($lot->category != null) {
              $lot->published = !$lot->published;
              $lot->update();
              return ['status' => $lot->published, 'url' => $lot->url];
          }
      }

      return false;
    } else {
      return $this->goHome();
    }
  }
}
