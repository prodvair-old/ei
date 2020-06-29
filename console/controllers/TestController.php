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
use common\models\db\SearchQueries;



/**
 * Test controller
 */
class TestController extends Controller
{
    public function actionIndex() 
    {
        $client = new \yii\httpclient\Client(['baseUrl' => 'http://sofifa.com/leagues?hl=en-US']);
        $response = $client->createRequest()
            ->send();

        var_dump('client: ', $client);
        var_dump('response: ', $response);
    }

    public function actionGet()
    {
        $searchQueries = SearchQueries::find()->one();

        // $parts = parse_url('http://185.141.227.253:9999/bankrupt/syre?LotSearch%5Bsearch%5D=&LotSearch%5BsubCategory%5D%5B0%5D=11085&LotSearch%5BsubCategory%5D%5B1%5D=11086&LotSearch%5BsubCategory%5D%5B2%5D=11087&LotSearch%5Bregion%5D=&LotSearch%5BminPrice%5D=&LotSearch%5BmaxPrice%5D=&LotSearch%5Betp%5D%5B0%5D=51825&LotSearch%5BtradeType%5D=&LotSearch%5BandArchived%5D=0&LotSearch%5BhaveImage%5D=0&LotSearch%5Befrsb%5D=&LotSearch%5BbankruptName%5D=&LotSearch%5BtorgStartDate%5D=&LotSearch%5BtorgEndDate%5D=&LotSearch%5BstartApplication%5D=0&LotSearch%5BcompetedApplication%5D=0');
        // parse_str($parts['query'], $query);
        // var_dump($parts);
        $path = ($searchQueries->getQueryParser(true))['path'];
        var_dump($path['scheme'].'://'.$path['host'].$path['path'].'/');
    }
    // php yii test/arrest
}

