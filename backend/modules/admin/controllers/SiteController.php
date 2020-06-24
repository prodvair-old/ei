<?php
namespace backend\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use common\models\form\LoginForm;
use common\models\db\Param;

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
                'only' => ['login', 'signup', 'error', 'logout', 'index', 'image-upload', 'file-upload', 'get-files', 'get-images', 'delete-file'],
                'rules' => [
                    [
                        'actions' => ['login', 'signup', 'error'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['image-upload', 'file-upload', 'get-files', 'get-images', 'delete-file'],
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
            'image-upload' => [
                'class' => 'vova07\imperavi\actions\UploadFileAction',
                'url' => ($this->toFrontend() . '/uploads/common'),
                'path' => '@frontend/web/uploads/common',
                'uploadOnlyImage' => true,
                'translit' => true,
                'unique' => false,
            ],
            'file-upload' => [
                'class' => 'vova07\imperavi\actions\UploadFileAction',
                'url' => ($this->toFrontend() . '/uploads/common'),
                'path' => '@frontend/web/uploads/common',
                'uploadOnlyImage' => false,
                'translit' => true,
                'unique' => false,
            ],
            'get-images' => [
                'class' => 'vova07\imperavi\actions\GetImagesAction',
                'url' => ($this->toFrontend() . '/uploads/common'),
                'path' => '@frontend/web/uploads/common',
            ],    
            'get-files' => [
                'class' => 'vova07\imperavi\actions\GetFilesAction',
                'url' => ($this->toFrontend() . '/uploads/common'),
                'path' => '@frontend/web/uploads/common',
            ],    
            'delete-file' => [
                'class' => 'vova07\imperavi\actions\DeleteFileAction',
                'url' => ($this->toFrontend() . '/uploads/common'),
                'path' => '@frontend/web/uploads/common',
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
        return $this->render('index', [
            'statistic' => Param::getDefs('statistic'),
        ]);
    }

    /**
     * Login action.
     * 
     * @param array $link of a page to redirect after login
     *
     * @return string
     */
    public function actionLogin($link = null)
    {
        $this->layout = 'main-login';
        
        $model = new LoginForm();
        
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            if ($link) {
                return $this->redirect([$link['to'] . '/' .$link['page'], 'id' => $link['id']]);
            } else {
                return $this->redirect(['site/index']);
            }
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->redirect(['site/login']);
    }

    /**
     * Return host and full path to frontend saved in params-local.php (common or backend).
     * 
     * @return string
     */
    private function toFrontend()
    {
        return Yii::$app->params['frontLink'];
    }
}
