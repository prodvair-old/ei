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
use backend\models\HistoryAdd;

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

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            if ($model->update()) {
                Yii::$app->session->setFlash('success', "Пользователь успешнот изменён.");
                HistoryAdd::edit(1, 'users/update','Редактирование пользователя №'.$model->id, ['userId' => $model->id], Yii::$app->user->identity);

                return $this->redirect(['users/update', 'id' => $model->id]);
            } else {
                Yii::$app->session->setFlash('error', "Ошибка при редактировании пользователя");
                HistoryAdd::edit(2, 'owners/update','Ошибка при редактировании пользователя №'.$model->id, ['userId' => $model->id], Yii::$app->user->identity);
            }
        }

        return $this->render('update', ['model' => $model]);
    }
    public function actionDelete()
    {
        if (!UserAccess::forManager('users', 'delete')) {
            return $this->goHome();
        }

        $get = Yii::$app->request->get();

        $user = UsersEditor::findOne($get['id']);

        if ($user->delete()) {
            Yii::$app->session->setFlash('success', "Пользователь успешно удалёно");
            HistoryAdd::remove(1, 'users/delete','Удалён пользователь №'.$get['id'], ['userId' => $get['id']], Yii::$app->user->identity);
            return $this->redirect(['users/index']);
        } else {
            Yii::$app->session->setFlash('error', "Ошибка при удалении пользователя №".$get['id']);
            HistoryAdd::remove(2, 'users/delete','Ошибка удаления пользователя №'.$get['id'], ['userId' => $get['id']], Yii::$app->user->identity);
            return $this->redirect(['users/update', 'id' => $get['id']]);
        }
    }
}
