<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use backend\models\HistoryAdd;
use backend\models\UserAccess;
use backend\models\Editors\LotEditor;
use backend\models\Editors\TorgEditor;
use backend\models\Editors\OwnerrEditor;

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
                        'actions' => ['logout', 'index', 'add-field'],
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
            if (Yii::$app->user->identity->auth_key !== Yii::$app->request->get('token')) {
                HistoryAdd::singOut(1, 'Выход из системы');

                Yii::$app->user->logout();

                $model = new LoginForm();

                if ($model->token = Yii::$app->request->get('token')) {
                    $login = $model->loginAdminToken();
    
                    if ($login['status']) {
                        HistoryAdd::singIn(1, 'Вход в систему');
    
                        if ($link = Yii::$app->request->get('link')) {
                            return $this->redirect([$link['to'].'/'.$link['page'], 'id' => $link['id']]);
                        } else {
                            return $this->goHome();
                        }
                    }
    
                    if ($login['user']) {
                        HistoryAdd::singIn(2, 'Не удачный вход в систему', $model->errors, $login['user']);
                    }
                }
            } else {
                if ($link = Yii::$app->request->get('link')) {
                    return $this->redirect([$link['to'].'/'.$link['page'], 'id' => $link['id']]);
                } else {
                    return $this->goHome();
                }
            }
        }

        $model = new LoginForm();
        if (Yii::$app->request->isGet) {
            if ($model->token = Yii::$app->request->get('token')) {
                $login = $model->loginAdminToken();

                if ($login['status']) {
                    HistoryAdd::singIn(1, 'Вход в систему');

                    if ($link = Yii::$app->request->get('link')) {
                        if ($link['id']) {
                            $url = [$link['to'].'/'.$link['page'], 'id' => $link['id']];
                        } else {
                            $url = [$link['to'].'/'.$link['page']];
                        }
                        return $this->redirect($url);
                    } else {
                        return $this->goBack();
                    }
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
            HistoryAdd::singOut(1, 'Выход из системы');
        }
        
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionAddField($type)
    {
        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        
        switch ($type) {
            case 'lot':
                $model  = new LotEditor();
                break;
            case 'torg':
                $model  = new TorgEditor();
                break;
            case 'owner':
                $model  = new OwnerrEditor();
                break;
        }
        $name   = Yii::$app->request->get('name');
    
        //other stuff
    
        return $this->renderAjax("_field",['model'=>$model, 'name' => $name]);
    }
}
