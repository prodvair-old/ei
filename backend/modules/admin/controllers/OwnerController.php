<?php

namespace backend\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;

use common\models\db\Owner;
use common\models\db\Organization;
use common\models\db\Place;
use backend\modules\admin\models\OwnerSearch;

/**
 * OwnerController implements the CRUD actions for Owner model.
 */
class OwnerController extends Controller
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
        if (!Yii::$app->user->can('indexOwner'))
            throw new ForbiddenHttpException(Yii::t('app', 'Access denied.'));

        $searchModel = new OwnerSearch();
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
        if (!Yii::$app->user->can('viewOwner'))
            throw new ForbiddenHttpException(Yii::t('app', 'Access denied.'));

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if (!Yii::$app->user->can('createOwner'))
            throw new ForbiddenHttpException(Yii::t('app', 'Access denied.'));

        $model = new Owner();
        $organization = new Organization();
        $organization->scenario = Organization::SCENARIO_CREATE;
        $place = new Place();
        $place->scenario = Place::SCENARIO_CREATE;

        $post = Yii::$app->request->post();
        if ($model->load($post) && $organization->load($post) && $place->load($post)) {
            $isValid = $model->validate();
            $isValid = $orgnization->validate() && $isValid;
            $isValid = $place->validate() && $isValid;
            if ($isValid) {
                $model->save(false);
                $organization->model = Owner::INT_CODE;
                $organization->parent_id = $model->id;
                $organization->save(false);
                $place->model = Owner::INT_CODE;
                $place->parent_id = $model->id;
                $place->save(false);
                Yii::$app->session->setFlash('success', Yii::t('app', 'Created successfully.'));
                return $this->redirect(['update', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'organization'  => $organization,
            'place' => $place,
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
        
        if (!Yii::$app->user->can('updateOwner'))
            throw new ForbiddenHttpException(Yii::t('app', 'Access denied.'));
        
        $organization = isset($model->organization)
            ? $model->organization
            : new Organization(['model' => Owner::INT_CODE, 'parent_id' => $model->id]);
        $place = isset($model->place)
            ? $model->place
            : new Place(['model' => Owner::INT_CODE, 'parent_id' => $model->id]);

        $post = Yii::$app->request->post();
        if ($model->load($post) && $organization->load($post) && $place->load($post)) {
            $isValid = $model->validate();
            $isValid = $organization->validate() && $isValid;
            $isValid = $place->validate() && $isValid;
            if ($isValid) {
                $model->save(false);
                $place->save(false);
                Yii::$app->session->setFlash('success', Yii::t('app', 'Updated successfully.'));
            }
        }

        return $this->render('update', [
            'model' => $model,
            'organization'  => $organization,
            'place' => $place,
        ]);
    }

    /**
     * Deletes an existing model.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if (!Yii::$app->user->can('deleteOwner'))
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
            if ($this->_model = Owner::findOne($id))
            {
                return $this->_model;
            } else {
                throw new NotFoundHttpException(Yii::t('app', 'The requested model does not exist.'));
            }
        }
    }
}
