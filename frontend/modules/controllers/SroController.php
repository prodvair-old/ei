<?php

namespace frontend\modules\controllers;

use Yii;
use common\models\db\Sro;
use frontend\modules\models\SroSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * SroController implements the CRUD actions for Sro model.
 */
class SroController extends Controller
{

    /**
     * Lists all Sro models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SroSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $count = $searchModel->getTotalCount();
        $model = $dataProvider->getModels();

        if (Yii::$app->request->isAjax) {
            return $this->renderPartial('block', [
                'model' => $model,
            ]);
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'model'       => $model,
            'count'       => $count,
        ]);
    }

    /**
     * Displays a single Sro model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Finds the Sro model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Sro the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Sro::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
