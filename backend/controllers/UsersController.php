<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use yii\data\Pagination;

use backend\models\UserAccess;
use backend\models\Editors\UsersEditor;

use common\models\User;

/**
 * Users controller
 */
class UsersController extends Controller
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
                        'actions' => ['update', 'add', 'index'],
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
        if (!UserAccess::forAdmin('users')) {
            return $this->goHome();
        }

        $users = User::find()->orderBy('created_at ASC');

        if (!UserAccess::forSuperAdmin()) {
            $users->where(['!=', 'role', 'superAdmin']);
        }
        
        return $this->render('index', ['users' => $users]);
    }

    public function actionUpdate()
    {
        if (!UserAccess::forManager('users', 'edit')) {
            return $this->goHome();
        }

        $get = Yii::$app->request->get();

        $model = UsersEditor::findOne($get['id']);

        return $this->render('update', ['model' => $model]);
    }
}
