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

    public function actionCad()
    {
        $text = 'Полуприцеп Renders RSCC, VINYA5B3Z1N639B94500, 1996г.в., НПЦ 238 000р. Грузовой седельный тягач International 92001, 2002 г.в., VIN 3HSCEAMR93N054641, НПЦ 770 000р. Грузовой тягач седельный MAN TGA 19.390 4X2 BLS-WW, 2008г.в., VIN WMAHW7ZZ19P011656, НПЦ 1 760 000р.';
        $vin_text = str_replace(['WIN', 'VIN', 'ВИН', 'win', 'vin', 'вин'], '',$text);
        $vin_check = preg_match("/[A-HJ-NPR-Z0-9]{17}/", $vin_text, $vin);
        // return ($kadastr_check)? $kadastr[0] : false;
        var_dump($vin_text,$vin_check, $vin);
    }
    // php yii test/arrest
}

