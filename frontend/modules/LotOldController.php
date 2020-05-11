<?php

namespace frontend\modules;

use Yii;
use frontend\modules\models\LotsOld;
use frontend\modules\LotsOldSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * LotOldController implements the CRUD actions for LotsOld model.
 */
class LotOldController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all LotsOld models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LotsOldSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single LotsOld model.
     * @param integer $id
     * @param integer $torgId
     * @param string $msgId
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $torgId, $msgId)
    {
        return $this->render('view', [
            'model' => $this->findModel($id, $torgId, $msgId),
        ]);
    }

    /**
     * Creates a new LotsOld model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new LotsOld();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id, 'torgId' => $model->torgId, 'msgId' => $model->msgId]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing LotsOld model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @param integer $torgId
     * @param string $msgId
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id, $torgId, $msgId)
    {
        $model = $this->findModel($id, $torgId, $msgId);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id, 'torgId' => $model->torgId, 'msgId' => $model->msgId]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing LotsOld model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @param integer $torgId
     * @param string $msgId
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id, $torgId, $msgId)
    {
        $this->findModel($id, $torgId, $msgId)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the LotsOld model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @param integer $torgId
     * @param string $msgId
     * @return LotsOld the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $torgId, $msgId)
    {
        if (($model = LotsOld::findOne(['id' => $id, 'torgId' => $torgId, 'msgId' => $msgId])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
