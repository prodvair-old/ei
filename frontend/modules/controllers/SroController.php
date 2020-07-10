<?php

namespace frontend\modules\controllers;

use common\models\db\Casefile;
use common\models\db\Lot;
use common\models\db\Manager;
use common\models\db\ManagerSro;
use common\models\db\Torg;
use common\models\db\TorgDebtor;
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
            'offsetStep'  => Yii::$app->params[ 'defaultPageLimit' ]
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
        $managersInSroQuery = Manager::find()
            ->select('manager.id')
            ->innerJoin(ManagerSro::tableName(), 'manager_sro.manager_id = manager.id')
            ->where(['manager_sro.sro_id' => $id]);

        $caseCount = Casefile::find()
            ->innerJoin(TorgDebtor::tableName(), 'torg_debtor.case_id = casefile.id')
            ->innerJoin(Torg::tableName(), 'torg.id = torg_debtor.torg_id')
            ->where(['in', TorgDebtor::tableName() . '.manager_id', $managersInSroQuery])
            ->count('casefile.id');

        $lotCount = Lot::find()
            ->joinWith(['torg.debtor deb'])
            ->where(['in', 'deb.manager_id', $managersInSroQuery])
            ->count('lot.id');

        $arbitrs = $managersInSroQuery
            ->limit(15)
            ->all();

        $arbitrCount = $managersInSroQuery->count('manager.id');

        return $this->render('view', [
            'model'       => $this->findModel($id),
            'caseCount'   => $caseCount,
            'arbitrs'     => $arbitrs,
            'arbitrCount' => $arbitrCount,
            'lotCount'    => $lotCount,
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
