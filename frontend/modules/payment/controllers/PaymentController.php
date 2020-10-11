<?php

namespace frontend\modules\payment\controllers;

use yii\web\Controller;

/**
 * Default controller for the `Payment` module
 */
class PaymentController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return 'payment';
    }
}
