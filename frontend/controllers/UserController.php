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
use common\models\SendSMS;
use common\models\db\SearchQueries;

use arogachev\excel\import\advanced\Importer;

use frontend\models\UserSetting;
use frontend\models\UserEditPhone;
use frontend\models\UploadZalogLotImage;
use frontend\models\ZalogLotCategorySet;
use frontend\models\ImportZalog;
use frontend\models\zalog\FilterLots;
use frontend\models\arrestBankrupt\importFIleForm;

use common\models\User;
use common\models\db\WishList;

/**
 * User controller
 */
class UserController extends Controller
{
    public $_model;
    
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
  public function actionSetting()
  {
    if (!Yii::$app->user->isGuest) {

        $model = $this->findModel(Yii::$app->user->id);
        $model_image = new UserSetting();
        $model_phone = new UserEditPhone();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->setting(Yii::$app->user->id);
        }

        return $this->render('setting', ['model' => $model, 'model_image' => $model_image, 'model_phone' => $model_phone]);
    } else {
        return $this->goHome();
    }
  }
  public function actionGetCode() {
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    $code = rand(1000, 9999);

    $post = Yii::$app->request->post();
    $session = Yii::$app->session;
    $session->set('userCode', $code);
    $session->set('userPhone', $post['UserEditPhone']['phone']);

    $model = new SendSMS();

    $model->phone = preg_replace('/[^0-9]/', '', $post['UserEditPhone']['phone']);
    $model->message = "Vash kod: $code";

    $result = false;
    $mess = 'Ошибка сервера';

    if ($model->check()) {
      if ($response = $model->send()) {
        if ($response['status']) {
          $result = true;
        }
        $mess = $response['text'];
      }
    }

    return ['result' => $result, 'mess' => $mess];
  }
  public function actionEditPhone()
  {
    if (!Yii::$app->user->isGuest && Yii::$app->request->isAjax) {
      Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

      $result = false;
      $mess = 'Серверная ошибка';
      $session = Yii::$app->session;

      if ($session->has('userCode')) {
        $model = new UserEditPhone();

        if ($model->load(Yii::$app->request->post())) {
          if (str_replace('-', '', $model->code) == $session->get('userCode')) {
            $model_user = $this->findModel(Yii::$app->user->id);
            $model_user->phone = $session->get('userPhone');

            if ($model_user->save()) {
              $result = true;
              $mess = 'Успешно';
              $session->remove('userCode');
              $session->remove('userPhone');
            }
          } else {
            $mess = "Не верный код";
          }
        }
      } else {
        $mess = "Время истекло";
      }
      return ['result' => $result, 'error' => $mess];
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

      $wishCount = WishList::find()->where(['user_id' => Yii::$app->user->id])->count();
      $pages = new Pagination(['totalCount' => $wishCount, 'pageSize' => 6]);

      $wishList = WishList::find()->where(['user_id' => Yii::$app->user->id])->offset($pages->offset)->limit($pages->limit)->orderBy('id DESC')->all();

      return $this->render('wish_list', ['wishCount' => $wishCount, 'pages' => $pages, 'wishList' => $wishList]);
    } else {
      return $this->goHome();
    }
  }

  public function actionSearchPreset()
  {
    if (!Yii::$app->user->isGuest) {

      $searchQueriesCount = SearchQueries::find()->where(['user_id' => Yii::$app->user->id])->count();
      $pages = new Pagination(['totalCount' => $searchQueriesCount, 'pageSize' => 15]);

      $searchQueries = SearchQueries::find()->where(['user_id' => Yii::$app->user->id])->offset($pages->offset)->limit($pages->limit)->orderBy('id DESC')->all();

      return $this->render('search-preset', ['searchQueriesCount' => $searchQueriesCount, 'pages' => $pages, 'searchQueries' => $searchQueries]);
    } else {
      return $this->goHome();
    }
  }

  public function actionSearchPresetChange()
  {
    if (!Yii::$app->user->isGuest) {
      $searchQueries = SearchQueries::findOne(['id' => Yii::$app->request->queryParams['id']]);

      $searchQueries->send_email = (Yii::$app->request->queryParams['send_email'] === 'true')? true: false;

      return $searchQueries->update();
    } else {
      return $this->goHome();
    }
  }
  public function actionSearchPresetDel()
  {
    if (!Yii::$app->user->isGuest) {
      $searchQueries = SearchQueries::findOne(['id' => Yii::$app->request->queryParams['id']]);

      return $searchQueries->delete();
    } else {
      return $this->goHome();
    }
  }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id User ID
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function findModel($id)
    {
		if($this->_model === null) 
		{
			if(($this->_model = UserSetting::findOne($id)) && $this->_model->status == User::STATUS_ACTIVE) 
			{
				return $this->_model;
			} else {
				throw new NotFoundHttpException('The requested model does not exist.');
			}
		}
	}
}
