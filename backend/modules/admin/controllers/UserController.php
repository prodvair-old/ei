<?php

namespace backend\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;

use common\models\db\User;
use common\models\db\Profile;
use common\models\db\Notification;
use backend\modules\admin\models\UserSearch;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    private $_model;

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
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
     * List of all models.
     * @return mixed
     */
    public function actionIndex()
    {
        //if (!Yii::$app->user->can('index'))
            //throw new ForbiddenHttpException(Yii::t('app', 'Access denied.'));

        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id = 0)
    {
        $model = $this->findModel($id);
        //if (!Yii::$app->user->can('viewPost', ['lot' => $model]))
            //throw new ForbiddenHttpException(Yii::t('app', 'Access denied.'));

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing model.
     * If the update was successful, a success message flashes.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
        //if (!Yii::$app->user->can('update', ['user' => $model]))
            //throw new ForbiddenHttpException(Yii::t('app', 'Access denied.'));
        
        $profile = $model->profile;
        if (!$profile) {
            $profile = new Profile();
            $profile->scenario = Profile::SCENARIO_CREATE;
        }
        $notification = $model->notification;
        if (!$notification) {
            $notification = new Notification();
            $notification->scenario = Notification::SCENARIO_CREATE;
        }
        
        $post = Yii::$app->request->post();
        if ($model->load($post) && $profile->load($post) && $notification->load($post)) {
            $isValid = $model->validate();
            $isValid = $profile->validate() && $isValid;
            $isValid = $notification->validate() && $isValid;
            if ($isValid) {
                $model->save(false);
                $profile->model = $model->intCode;
                $profile->parent_id = $model->id;
                $profile->save(false);
                $notification->user_id = $model->id;
                $notification->save(false);
                Yii::$app->session->setFlash('success', Yii::t('app', 'Updated successfully.'));
            }
        }
        
        return $this->render('update', [
            'model' => $model,
            'profile' => $profile,
            'notification' => $notification,
        ]);
    }

    /**
     * Deletes an existing model.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if (!Yii::$app->user->can('delete'))
            throw new ForbiddenHttpException(Yii::t('app', 'Access denied.'));

        $model = $this->findModel($id);
        $model->delete();
        return $this->redirect(['index']);
    }

    /**
     * Finds the model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function findModel($id)
    {
        if ($this->_model === null) 
        {
            if ($this->_model = User::findOne($id))
            {
                return $this->_model;
            } else {
                throw new NotFoundHttpException(Yii::t('app', 'The requested model does not exist.'));
            }
        }
    }

    /**
     * Fill in user items for dropdown list.
     * $search string a part of username or profile.first_name or profile.last_name)
     * @return json array of {id: integer, text: string}
     * @throws ForbiddenHttpException if this is not an ajax request
     */
	public function actionFillin($search = '', $type)
	{
		if (Yii::$app->getRequest()->isAjax) {
            return $this->asJson(['results' => User::getItems($search)]);
		} else
			throw new ForbiddenHttpException('Only ajax request suitable.');
	}
}
