<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;

use frontend\models\UserSetting;

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
    public function actionSetting()
    {
        if (!Yii::$app->user->isGuest) {

            $model = new UserSetting();
            $model_image = new UserSetting();

            if($model->load(Yii::$app->request->post())){
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

            if($model->load(Yii::$app->request->post())){
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
            $wishList = WishList::find()->where(['userId' => Yii::$app->user->id])->all();
            return $this->render('wish_list', ['wishList' => $wishList]);
        } else {
            return $this->goHome();
        }
            
    }

}
