<?php

namespace backend\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;

use common\models\db\Torg;
use common\models\db\TorgPledge;
use backend\modules\admin\models\TorgSearch;
use backend\modules\admin\traits\TrExtractor;

/**
 * TorgController implements the CRUD actions for Torg model.
 */
class TorgController extends Controller
{
    use TrExtractor;
    
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
     * List of all models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (!Yii::$app->user->can('indexTorg'))
            throw new ForbiddenHttpException(Yii::t('app', 'Access denied.'));

        $searchModel = new TorgSearch();
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
        if (!Yii::$app->user->can('viewTorg', ['model' => $model]))
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
        if (!Yii::$app->user->can('createTorg'))
            throw new ForbiddenHttpException(Yii::t('app', 'Access denied.'));

        $model = new Torg();
        $model->property = Torg::PROPERTY_ZALOG;
        $pledge = new TorgPledge();
        $pledge->scenario = TorgPledge::SCENARIO_CREATE;

        if ($model->load(Yii::$app->request->post()) && $pledge->load(Yii::$app->request->post())) {
            $isValid = $model->validate();
            $isValid = $pledge->validate() && $isValid;
            if ($isValid) {
                $model->save(false);
                $pledge->torg_id = $model->id;
                $pledge->save(false);
                if ($pledge->add_lot)
                    return $this->redirect(['create/lot', 'torg_id' => $model->id]);
                else {
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Created successfully.'));
                    return $this->redirect(['update', 'id' => $model->id]);
                }
            }
        }
        return $this->render('create', [
            'model'   => $model,
            'pledge' => $pledge,
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
        
        if (!Yii::$app->user->can('update', ['model' => $model]))
            throw new ForbiddenHttpException(Yii::t('app', 'Access denied.'));
        
        // auction (Torg) can be edited only for the pledge (zalog) property
        if (!($model->property == Torg::PROPERTY_ZALOG))
            throw new ForbiddenHttpException(Yii::t('app', 'Access denied.'));

        $pledge = new TorgPledge();

        $pledge = $model->torgPledge;
        if (!$pledge)
            $pledge = new TorgPledge();

        if ($model->load(Yii::$app->request->post()) && $pledge->load(Yii::$app->request->post())) {
            $isValid = $model->validate();
            $isValid = $pledge->validate() && $isValid;
            if ($isValid) {
                $model->save(false);
                $pledge->save(false);
                Yii::$app->session->setFlash('success', Yii::t('app', 'Updated successfully.'));
            }
        }
        return $this->render('update', [
            'model' => $model,
            'pledge' => $pledge,
        ]);
    }

    /**
     * Deletes an existing model.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if (!Yii::$app->user->can('deleteTorg'))
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
            if ($this->_model = Torg::findOne($id))
            {
                return $this->_model;
            } else {
                throw new NotFoundHttpException(Yii::t('app', 'The requested model does not exist.'));
            }
        }
    }

    /**
     * Getting a pool of models that meet the conditions.
     * @return json array {count: integer, content: string}
     * @throws ForbiddenHttpException if this is not an ajax request
     */
	public function actionMore()
	{
		if(Yii::$app->getRequest()->isAjax) {
            $searchModel = new TorgSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->get(), Yii::$app->request->get('offset'));

            return $this->asJson([
                'count' => $dataProvider->getCount(),
                'content' => $this->getTr($this->renderAjax('more', [
                    'dataProvider' => $dataProvider,
                    'searchModel' => $searchModel,
                ]))
            ]);
		} else
			throw new ForbiddenHttpException('Only ajax request suitable.');
	}
}
