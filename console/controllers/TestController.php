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
use common\models\Query\Bankrupt\Purchaselots;
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

        echo 'start';
        $lotInfo = Purchaselots::find()->limit(1)->all();
        echo 'get';

        var_dump($lotInfo[0]->lot[1]->title);
    }

    public function actionAddress()
    {
        # code...
    }
    // php yii test/arrest
}

