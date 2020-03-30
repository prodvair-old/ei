<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;

use console\models\GetInfoFor;
use common\models\Query\Bankrupt\Auction;
use common\models\Query\Bankrupt\Arbitrs;
use common\models\Query\Bankrupt\Offerreductions;
use common\models\Query\Municipal\Lots;
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
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $lots = Lots::find()->count();

        return $lots;
    }

    public function actionAddress()
    {
        # code...
    }
    // php yii test/arrest
}

