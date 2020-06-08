<?php

namespace backend\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\base\InvalidParamException;

use common\models\db\Lot;
use common\models\db\Torg;
use common\models\db\Place;
use backend\modules\admin\models\LotSearch;
use backend\modules\admin\traits\TrExtractor;

/**
 * LotController implements the CRUD actions for Lot model.
 */
class LotController extends Controller
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

        $searchModel = new LotSearch();
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
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($torg_id = null)
    {

        //if (!Yii::$app->user->can('createLot')) {
            //throw new ForbiddenHttpException(Yii::t('app', 'Access denied.'));

        if (!$torg_id)
            throw new InvalidParamException(Yii::t('app', 'Torg ID is needed.'));

        $torg  = Torg::findOne($torg_id);
        $model = new Lot(['torg_id' => $torg_id]);
        $place = new Place(['model' => Lot::INT_CODE, 'parent_id' => $model->id]);

        $post = Yii::$app->request->post();
        if ($model->load($post) && $place->load($post)) {
            $isValid = $model->validate();
            $isValid = $place->validate() && $isValid;
            if ($isValid) {
                $model->save(false);
                $place->save(false);
                Yii::$app->session->setFlash('success', Yii::t('app', 'Created successfully.'));
                return $this->redirect(['update', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'torg'  => $torg,
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
        
        //if (!Yii::$app->user->can('update', ['lot' => $model]))
            //throw new ForbiddenHttpException(Yii::t('app', 'Access denied.'));
        
        $place = isset($model->place)
            ? $model->place
            : new Place(['model' => Lot::INT_CODE, 'parent_id' => $model->id]);

        $post = Yii::$app->request->post();
        if ($model->load($post) && $place->load($post)) {
            $isValid = $model->validate();
            $isValid = $place->validate() && $isValid;
            if ($isValid) {
                $model->save(false);
                $place->save(false);
                Yii::$app->session->setFlash('success', Yii::t('app', 'Updated successfully.'));
            }
        }

        return $this->render('update', [
            'model' => $model,
            'torg'  => $model->torg,
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
            if ($this->_model = Lot::findOne($id))
            {
                return $this->_model;
            } else {
                throw new NotFoundHttpException(Yii::t('app', 'The requested model does not exist.'));
            }
        }
    }

    /**
     * Getting a pool of lots that meet the conditions.
     * @return json array {count: integer, content: string}
     * @throws ForbiddenHttpException if this is not an ajax request
     */
	public function actionMore()
	{
		if(Yii::$app->getRequest()->isAjax) {
            $searchModel = new LotSearch();
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
