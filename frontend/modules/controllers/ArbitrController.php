<?php

namespace frontend\modules\controllers;

use Yii;
use common\models\db\Manager;
use frontend\modules\models\ManagerSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * ArbitrController implements actions for Manager model.
 */
class ArbitrController extends Controller
{
    /**
     * Lists all Manager models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ManagerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $count = $searchModel->getTotalCount();
        $model = $dataProvider->getModels();

        if (Yii::$app->request->isAjax) {
            return $this->renderPartial('viewAjax', [
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
     * Displays a single Manager model.
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
     * Finds the Manager model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Manager the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Manager::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
