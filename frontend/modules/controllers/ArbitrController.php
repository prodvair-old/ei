<?php

namespace frontend\modules\controllers;

use common\models\db\Casefile;
use common\models\db\Lot;
use common\models\db\Torg;
use common\models\db\TorgDebtor;
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
     * Displays a single Manager model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $lots = Lot::find()
            ->joinWith(['categories', 'torg.debtor deb'], true, 'INNER JOIN')
            ->andOnCondition(['deb.manager_id' => $id])
            ->limit(15)
            ->orderBy(['torg.published_at' => SORT_DESC])
            ->all();

        $lotsCount = Lot::find()
            ->joinWith(['categories', 'torg.debtor deb'])
            ->andOnCondition(['deb.manager_id' => $id])
            ->count('lot.id');

        $caseCount = Casefile::find()
            ->innerJoin(TorgDebtor::tableName(), 'torg_debtor.case_id = casefile.id')
            ->innerJoin(Torg::tableName(), 'torg.id = torg_debtor.torg_id')
            ->where(['torg_debtor.manager_id' => $id])
            ->count();

        return $this->render('view', [
            'model'     => $this->findModel($id),
            'lots'      => $lots,
            'lotsCount' => $lotsCount,
            'caseCount' => $caseCount,
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
