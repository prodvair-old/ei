<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;

use console\models\GetInfoFor;
use common\models\Query\Bankrupt\Auction;
use common\models\Query\Bankrupt\Arbitrs;
use common\models\Query\Bankrupt\Offerreductions;
use common\models\Query\Bankrupt\Lots;
use common\models\Query\Arrest\LotDocuments;
use console\models\torgs\TorgsBankrupt;
use console\models\lots\LotsBankrupt;
use console\models\managers\ArbitrsBankrupt;

/**
 * Test controller
 */
class TestController extends Controller
{
    public function actionIndex() 
    {
        error_reporting(0);

        // $table = Lots::findOne($id);

        // var_dump($table);

        // $parsing = LotsBankrupt::id($id);

        $doc = LotDocuments::findOne(63813);

        var_dump($doc->ldocId);
        var_dump($doc->lot);
        
        // var_dump(Arbitrs::find()->where(['id' => 59878])->one());
        // echo "Yes, cron service is running.";
    }

    public function actionAddress()
    {
        # code...
    }
    // php yii test/arrest
}

