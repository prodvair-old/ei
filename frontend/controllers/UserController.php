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
        'fileImport' => 'File Import',
      ]);
      $modelImport->addRule(['fileImport'], 'required');
      $modelImport->addRule(['fileImport'], 'file', ['extensions' => 'ods,xls,xlsx'], ['maxSize' => 1024 * 1024 * 1024 * 1024]);

      if (Yii::$app->request->post()) {
        $modelImport->fileImport = \yii\web\UploadedFile::getInstance($modelImport, 'fileImport');
        if ($modelImport->fileImport && $modelImport->validate()) {
          $inputFileType = \PHPExcel_IOFactory::identify($modelImport->fileImport->tempName);
          $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
          $objPHPExcel = $objReader->load($modelImport->fileImport->tempName);
          $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
          $baseRow = 3;
          $loadCount = 0;
          while (!empty($sheetData[$baseRow]['B'])) {
            if (!LotsZalog::find()->where(['lotId' => (string) $sheetData[$baseRow]['A'], 'contactPersonId' => Yii::$app->user->id])->one()) {
              $model = new LotsZalog();
              $model->lotId               = (string) $sheetData[$baseRow]['A'];
              $model->title               = mb_substr((string) $sheetData[$baseRow]['B'], 0, 150, 'UTF-8');
              $model->description         = (string) $sheetData[$baseRow]['C'];
              $model->publicationDate     = Yii::$app->formatter->asDate((string) $sheetData[$baseRow]['D'], 'php:Y-m-d H:i:s');
              $model->startingDate        = Yii::$app->formatter->asDate((string) $sheetData[$baseRow]['E'], 'php:Y-m-d H:i:s');
              $model->endingDate          = Yii::$app->formatter->asDate((string) $sheetData[$baseRow]['F'], 'php:Y-m-d H:i:s');
              $model->completionDate      = Yii::$app->formatter->asDate((string) $sheetData[$baseRow]['G'], 'php:Y-m-d H:i:s');
              $model->startingPrice       = floatval($sheetData[$baseRow]['H']);
              $model->step                = floatval($sheetData[$baseRow]['I']);
              $model->stepCount           = (int) $sheetData[$baseRow]['J'];
              $model->country             = (string) $sheetData[$baseRow]['K'];
              $model->city                = (string) $sheetData[$baseRow]['L'];
              $model->address             = (string) $sheetData[$baseRow]['M'];
              $model->tradeType           = (string) $sheetData[$baseRow]['N'];
              $model->tradeTipeId         = ((string) $sheetData[$baseRow]['N'] == 'Аукцион') ? 0 : 1;
              $model->procedureDate       = Yii::$app->formatter->asDate((string) $sheetData[$baseRow]['O'], 'php:Y-m-d H:i:s');
              $model->conclusionDate      = Yii::$app->formatter->asDate((string) $sheetData[$baseRow]['P'], 'php:Y-m-d H:i:s');
              $model->viewInfo            = (string) $sheetData[$baseRow]['Q'];
              $model->collateralPrice     = floatval($sheetData[$baseRow]['R']);
              $model->paymentDetails      = (string) $sheetData[$baseRow]['S'];
              $model->additionalConditions    = (string) $sheetData[$baseRow]['T'];
              $model->currentPeriod       = (string) $sheetData[$baseRow]['U'];
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
          Yii::$app->getSession()->setFlash('success', 'Success');
        } else {
          Yii::$app->getSession()->setFlash('error', 'Error');
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

  public function actionAddlots()
  {
    if (!Yii::$app->user->isGuest && Yii::$app->user->identity->role == 'agent') {
      $modelImport = new \yii\base\DynamicModel([
        'fileImport' => 'File Import',
      ]);
      $modelImport->addRule(['fileImport'], 'required');
      $modelImport->addRule(['fileImport'], 'file', ['extensions' => 'ods,xls,xlsx'], ['maxSize' => 1024 * 1024 * 1024 * 1024]);

      if (Yii::$app->request->post()) {
        $modelImport->fileImport = \yii\web\UploadedFile::getInstance($modelImport, 'fileImport');
        if ($modelImport->fileImport && $modelImport->validate()) {
          $inputFileType = \PHPExcel_IOFactory::identify($modelImport->fileImport->tempName);
          $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
          $objPHPExcel = $objReader->load($modelImport->fileImport->tempName);
          $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
          $baseRow = 3;
          $loadCount = 0;
          while (!empty($sheetData[$baseRow]['B'])) {
            if (!LotsZalog::find()->where(['lotId' => (string) $sheetData[$baseRow]['A'], 'contactPersonId' => Yii::$app->user->id])->one()) {
              $model = new LotsZalog();
              $model->lotId               = (string) $sheetData[$baseRow]['A'];
              $model->title               = mb_substr((string) $sheetData[$baseRow]['B'], 0, 150, 'UTF-8');
              $model->description         = (string) $sheetData[$baseRow]['C'];
              $model->publicationDate     = Yii::$app->formatter->asDate((string) $sheetData[$baseRow]['D'], 'php:Y-m-d H:i:s');
              $model->startingDate        = Yii::$app->formatter->asDate((string) $sheetData[$baseRow]['E'], 'php:Y-m-d H:i:s');
              $model->endingDate          = Yii::$app->formatter->asDate((string) $sheetData[$baseRow]['F'], 'php:Y-m-d H:i:s');
              $model->completionDate      = Yii::$app->formatter->asDate((string) $sheetData[$baseRow]['G'], 'php:Y-m-d H:i:s');
              $model->startingPrice       = floatval($sheetData[$baseRow]['H']);
              $model->step                = floatval($sheetData[$baseRow]['I']);
              $model->stepCount           = (int) $sheetData[$baseRow]['J'];
              $model->country             = (string) $sheetData[$baseRow]['K'];
              $model->city                = (string) $sheetData[$baseRow]['L'];
              $model->address             = (string) $sheetData[$baseRow]['M'];
              $model->tradeType           = (string) $sheetData[$baseRow]['N'];
              $model->tradeTipeId         = ((string) $sheetData[$baseRow]['N'] == 'Аукцион') ? 0 : 1;
              $model->procedureDate       = Yii::$app->formatter->asDate((string) $sheetData[$baseRow]['O'], 'php:Y-m-d H:i:s');
              $model->conclusionDate      = Yii::$app->formatter->asDate((string) $sheetData[$baseRow]['P'], 'php:Y-m-d H:i:s');
              $model->viewInfo            = (string) $sheetData[$baseRow]['Q'];
              $model->collateralPrice     = floatval($sheetData[$baseRow]['R']);
              $model->paymentDetails      = (string) $sheetData[$baseRow]['S'];
              $model->additionalConditions    = (string) $sheetData[$baseRow]['T'];
              $model->currentPeriod       = (string) $sheetData[$baseRow]['U'];
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
          Yii::$app->getSession()->setFlash('success', 'Success');
        } else {
          Yii::$app->getSession()->setFlash('error', 'Error');
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

      return $this->render('addlots', [
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


  public function actionEditlot()
  {
    if (!Yii::$app->user->isGuest && Yii::$app->user->identity->role == 'agent') {
      $modelImport = new \yii\base\DynamicModel([
        'fileImport' => 'File Import',
      ]);
      $modelImport->addRule(['fileImport'], 'required');
      $modelImport->addRule(['fileImport'], 'file', ['extensions' => 'ods,xls,xlsx'], ['maxSize' => 1024 * 1024 * 1024 * 1024]);

      if (Yii::$app->request->post()) {
        $modelImport->fileImport = \yii\web\UploadedFile::getInstance($modelImport, 'fileImport');
        if ($modelImport->fileImport && $modelImport->validate()) {
          $inputFileType = \PHPExcel_IOFactory::identify($modelImport->fileImport->tempName);
          $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
          $objPHPExcel = $objReader->load($modelImport->fileImport->tempName);
          $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);
          $baseRow = 3;
          $loadCount = 0;
          while (!empty($sheetData[$baseRow]['B'])) {
            if (!LotsZalog::find()->where(['lotId' => (string) $sheetData[$baseRow]['A'], 'contactPersonId' => Yii::$app->user->id])->one()) {
              $model = new LotsZalog();
              $model->lotId               = (string) $sheetData[$baseRow]['A'];
              $model->title               = mb_substr((string) $sheetData[$baseRow]['B'], 0, 150, 'UTF-8');
              $model->description         = (string) $sheetData[$baseRow]['C'];
              $model->publicationDate     = Yii::$app->formatter->asDate((string) $sheetData[$baseRow]['D'], 'php:Y-m-d H:i:s');
              $model->startingDate        = Yii::$app->formatter->asDate((string) $sheetData[$baseRow]['E'], 'php:Y-m-d H:i:s');
              $model->endingDate          = Yii::$app->formatter->asDate((string) $sheetData[$baseRow]['F'], 'php:Y-m-d H:i:s');
              $model->completionDate      = Yii::$app->formatter->asDate((string) $sheetData[$baseRow]['G'], 'php:Y-m-d H:i:s');
              $model->startingPrice       = floatval($sheetData[$baseRow]['H']);
              $model->step                = floatval($sheetData[$baseRow]['I']);
              $model->stepCount           = (int) $sheetData[$baseRow]['J'];
              $model->country             = (string) $sheetData[$baseRow]['K'];
              $model->city                = (string) $sheetData[$baseRow]['L'];
              $model->address             = (string) $sheetData[$baseRow]['M'];
              $model->tradeType           = (string) $sheetData[$baseRow]['N'];
              $model->tradeTipeId         = ((string) $sheetData[$baseRow]['N'] == 'Аукцион') ? 0 : 1;
              $model->procedureDate       = Yii::$app->formatter->asDate((string) $sheetData[$baseRow]['O'], 'php:Y-m-d H:i:s');
              $model->conclusionDate      = Yii::$app->formatter->asDate((string) $sheetData[$baseRow]['P'], 'php:Y-m-d H:i:s');
              $model->viewInfo            = (string) $sheetData[$baseRow]['Q'];
              $model->collateralPrice     = floatval($sheetData[$baseRow]['R']);
              $model->paymentDetails      = (string) $sheetData[$baseRow]['S'];
              $model->additionalConditions    = (string) $sheetData[$baseRow]['T'];
              $model->currentPeriod       = (string) $sheetData[$baseRow]['U'];
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
          Yii::$app->getSession()->setFlash('success', 'Success');
        } else {
          Yii::$app->getSession()->setFlash('error', 'Error');
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

      return $this->render('edit_lot', [
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
