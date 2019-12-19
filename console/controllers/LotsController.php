<?php
namespace console\controllers;

use Yii;
use yii\web\Controller;

use common\models\Query\Arrest\LotsArrest;

/**
 * Lots controller
 */
class LotsController extends Controller
{
    public function actionIndex() 
    {
        echo "Yes, cron service is running.";
    }
    // php yii lots/arrest
    public function actionArrest()
    {
        $lots = LotsArrest::find()->all();

        var_dump($lots);
    }
}

