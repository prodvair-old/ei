<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use frontend\models\ContactForm;

use common\models\Query\MetaDate;

/**
 * Services controller
 */
class ServicesController extends Controller
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
        $metaData = MetaDate::find()->where(['mdName' => 'service'])->one();

        Yii::$app->params['description'] = $metaData->mdDescription;
        Yii::$app->params['text'] = $metaData->mdText;
        Yii::$app->params['title'] = $metaData->mdTitle;
        Yii::$app->params['h1'] = $metaData->mdH1;

        return $this->render('index');
    }
    public function actionAgent()
    {
        $metaData = MetaDate::find()->where(['mdName' => 'service/agent'])->one();

        Yii::$app->params['description'] = $metaData->mdDescription;
        Yii::$app->params['text'] = $metaData->mdText;
        Yii::$app->params['title'] = $metaData->mdTitle;
        Yii::$app->params['h1'] = $metaData->mdH1;

        return $this->render('agent');
    }

    public function actionEcp()
    {
        $metaData = MetaDate::find()->where(['mdName' => 'service/ecp'])->one();

        Yii::$app->params['description'] = $metaData->mdDescription;
        Yii::$app->params['text'] = $metaData->mdText;
        Yii::$app->params['title'] = $metaData->mdTitle;
        Yii::$app->params['h1'] = $metaData->mdH1;

        return $this->render('ecp');
    }
    
    public function actionLot()
    {
        $metaData = MetaDate::find()->where(['mdName' => 'service/lot'])->one();

        Yii::$app->params['description'] = $metaData->mdDescription;
        Yii::$app->params['text'] = $metaData->mdText;
        Yii::$app->params['title'] = $metaData->mdTitle;
        Yii::$app->params['h1'] = $metaData->mdH1;

        return $this->render('lot');
    }


    public function actionSpecialist()
    {
        $model = new ContactForm();

        if ($model->load(Yii::$app->request->post())) {
            $model->sendEmail('specialist');
        }

        $metaData = MetaDate::find()->where(['mdName' => 'service/specialist'])->one();

        Yii::$app->params['description'] = $metaData->mdDescription;
        Yii::$app->params['text'] = $metaData->mdText;
        Yii::$app->params['title'] = $metaData->mdTitle;
        Yii::$app->params['h1'] = $metaData->mdH1;

        return $this->render('specialist', compact('model'));
    }


}
