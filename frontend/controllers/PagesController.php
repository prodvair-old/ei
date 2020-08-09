<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use frontend\models\ContactForm;

/**
 * Pages controller
 */
class PagesController extends Controller
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
    public function actionAbout()
    {
        return $this->render('about');
    }
    public function actionLicense()
    {
        return $this->render('license');
    }
    public function actionPolicy()
    {
        return $this->render('policy');
    }
    public function actionContact()
    {
        $model = new ContactForm();

        if (!Yii::$app->user->isGuest) {
            $model->name    = Yii::$app->user->identity->profile->first_name;
            $model->email   = Yii::$app->user->identity->email;
            $model->phone   = Yii::$app->user->identity->profile->phone;
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->sendEmail('contact');
        }


        return $this->render('contact', ['model' => $model]);
    }
    // public function actionService()
    // {
    //     return $this->render('service');
    // }
    public function actionFaq()
    {
        return $this->render('faq');
    }
    public function actionSitemap()
    {
        return $this->render('sitemap');
    }

}
