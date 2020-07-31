<?php

namespace frontend\modules\controllers;

use frontend\modules\components\ReportService;
use yii\web\Controller;


class PurchaseController extends Controller
{

    public function actionSuccess()
    {
        $rs = new ReportService();
        $get = \Yii::$app->request->get();

        if(isset($get['orderId'])) {

            if($rs->buyConfirm($get['orderId'])) {
                $this->redirect($get['fromUrl']);
            }
        }

    }
}
