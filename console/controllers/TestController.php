<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;

use console\models\GetInfoFor;
use common\models\Query\Bankrupt\Auction;
use common\models\Query\Bankrupt\Arbitrs;
use common\models\Query\Bankrupt\Lots;
use console\models\torgs\TorgsBankrupt;
use console\models\lots\LotsBankrupt;
use console\models\managers\ArbitrsBankrupt;

/**
 * Test controller
 */
class TestController extends Controller
{
    public function actionIndex($id) 
    {
        $table = Lots::findOne($id);

        var_dump($table);

        $parsing = LotsBankrupt::id($id);

        var_dump($parsing);


        // var_dump(Arbitrs::find()->where(['id' => 59878])->one());
        // echo "Yes, cron service is running.";
    }

    public function actionAddress()
    {
        # code...
    }
    // php yii test/arrest
}

