<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;

use console\models\GetInfoFor;

/**
 * Test controller
 */
class TestController extends Controller
{
    public function actionIndex() 
    {

        $cadastrAddress = GetInfoFor::cadastrAddress('24:50:0500190:55');
        
        var_dump(GetInfoFor::address($cadastrAddress['address']));
        // echo "Yes, cron service is running.";
    }
    // php yii test/arrest
}

