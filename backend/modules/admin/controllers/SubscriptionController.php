<?php

namespace backend\modules\admin\controllers;

use backend\modules\admin\models\SubscriptionSearch;
use common\models\db\Subscription;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;

/**
 * Class SubscribeController
 * @package backend\modules\admin\controllers
 */
class SubscriptionController extends Controller
{
    private $_model;

    public function behaviors()
    {
        return [
            'verbs' => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * List of all models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (!Yii::$app->user->can('index'))
            throw new ForbiddenHttpException(Yii::t('app', 'Access denied.'));

        $searchModel = new SubscriptionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel'  => $searchModel,
        ]);
    }

    /**
     * View model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {

        if (!Yii::$app->user->can('view'))
            throw new ForbiddenHttpException(Yii::t('app', 'Access denied.'));

        $model = $this->findModel($id);

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException
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
     * @param $id
     * @return Subscription|null
     * @throws NotFoundHttpException
     */
    public function findModel($id)
    {
        if ($this->_model === null) {
            if ($this->_model = Subscription::findOne($id)) {
                return $this->_model;
            }

            throw new NotFoundHttpException(Yii::t('app', 'The requested model does not exist.'));
        }

        return $this->_model;
    }
}
