<?php

namespace frontend\modules\controllers;

use common\models\db\Lot;
use Yii;
use common\models\db\Bankrupt;
use frontend\modules\models\BankruptSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * BankruptController implements the CRUD actions for Bankrupt model.
 */
class BankruptController extends Controller
{
    /**
     * Lists all Bankrupt models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BankruptSearch();
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
            'offsetStep'  => Yii::$app->params[ 'defaultPageLimit' ]
        ]);
    }

    /**
     * Displays a single Bankrupt model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $bankruptLots = Lot::find()
            ->joinWith(['torg.bankrupt bnkr'])
            ->where(['bnkr.id' => $id])
            ->limit(15)
            ->orderBy(['torg.published_at' => SORT_DESC])
            ->all();

        return $this->render('view', [
            'model'        => $this->findModel($id),
            'bankruptLots' => $bankruptLots,
        ]);
    }

    /**
     * Finds the Bankrupt model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Bankrupt the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Bankrupt::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
