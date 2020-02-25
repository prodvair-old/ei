<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use backend\models\HistoryAdd;
use backend\models\UserAccess;

/**
 * Site controller
 */
class SiteController extends Controller
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
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
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
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if (Yii::$app->request->isGet) {
            if ($model->token = Yii::$app->request->get('token')) {
                $login = $model->loginAdminToken();

                if ($login['status']) {
                    HistoryAdd::singIn(1, 'Вход в систему');

                    return $this->goBack();
                }

                if ($login['user']) {
                    HistoryAdd::singIn(2, 'Не удачный вход в систему', $model->errors, $login['user']);
                }
            }
        }
        if ($model->load(Yii::$app->request->post())) {
            $login = $model->loginAdmin();
            
            if ($login['status']) {
                HistoryAdd::singIn(1, 'Вход в систему');

                return $this->goBack();
            }

            if ($login['user']) {
                HistoryAdd::singIn(2, 'Не удачный вход в систему', $model->errors, $login['user']);
            }
        }
        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        if (!Yii::$app->user->isGuest) {
            HistoryAdd::singOut(1, 'Выход из систему');
        }
        
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
