<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

// Запросы
use common\models\Query\MetaDate;
use common\models\Query\Bankrupt\LotsBankrupt;
use common\models\Query\Arrest\LotsArrest;

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
    public function actionIndex($type)
    {
        if ($type == 'bankrupt') {
            $lots = LotsBankrupt::find()->limit(3)->all();
        } else if ($type == 'arrest') {
            $lots = LotsArrest::find()->limit(10)->all();
        } else {
            Yii::$app->response->statusCode = 404;
            throw new \yii\web\NotFoundHttpException;
        }

        $metaData = MetaDate::find()->where(['mdName' => $type])->one();

        Yii::$app->params['description'] = $metaData->mdDescription;
        Yii::$app->params['text'] = $metaData->mdText;
        Yii::$app->params['title'] = $metaData->mdTitle;
        Yii::$app->params['h1'] = $metaData->mdH1;

        return $this->render('index', [
            'type'  => $type,
            'lots'  => $lots
        ]);
    }
    public function actionCategory()
    {
        return $this->render('category');
    }
    public function actionSubcategory()
    {
        return $this->render('subcategory');
    }
    public function actionLot_page()
    {
        return $this->render('lot_page');
    }

}
