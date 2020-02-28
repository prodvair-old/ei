<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use yii\data\Pagination;

use backend\models\UserAccess;

use common\models\Query\HistoryAdmin;

/**
 * Historys controller
 */
class HistorysController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['all', 'index', 'view'],
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
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        
        return $this->render('index');
    }
    public function actionAll()
    {
        if (!UserAccess::forAdmin()) {
            return $this->goHome();
        }
        
        return $this->render('all');
    }

    public function actionView()
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $history =  HistoryAdmin::findOne(Yii::$app->request->get());
        
        return $this->render('view', ['history' => $history]);
    }

}
