<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;

use console\models\GetInfoFor;
use common\models\Query\Bankrupt\Auction;
use common\models\Query\Bankrupt\Arbitrs;
use common\models\Query\Bankrupt\Offerreductions;
use common\models\Query\Municipal\LotsMunicipal;
use common\models\Query\Arrest\LotsArrest;
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
    public function actionGetArrest($id)
    {
        var_dump(\console\models\lots\LotsMunicipal::id($id));
        // var_dump(LotsMunicipal::find()->where(['lotId' => '326236'])->limit(1)->all());
    }
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
    public function actionApi()
    {
        
        $client = Yii::$app->efrsbAPI;
        $currentDate = date("Y-m-d");
        $minDate = date("Y-m-d", strtotime("-10 days"));
        $result = $client->GetMessageIds(["startDate"=>$minDate."T00:00:00","endDate"=>$currentDate."T00:00:00"]);
        // $result = $client->GetMessageContent(array("id"=>661183));
        // foreach ($client->GetMessageIds(["startFrom"=>$minDate."T00:00:00","endTo"=>$currentDate."T00:00:00"]) as $result) {
        //     foreach ($result->TradePlace->TradeList->Trade as $tradeList) {
        //         foreach ($tradeList->MessageList->TradeMessage as $trade) {
        //             try
        //             {
        //                 $res =  $client->GetMessageContent(["id"=>$trade->ID]);
        //                 var_dump($res);
        //             }    
        //             catch (exception $ex)
        //             {
        //                 $result = $ex->getMessage();  
        //                 if(preg_match("/ообщение\s+не\s+найдено\s+по\s+идентификатору/i",  $res))
        //                 {
        //                     $log->out("    Error: ".$ex->getMessage()."\n");
        //                     unset($result);
        //                     $db->execute_query("update uds.msgSource set msgXML = :msgXML, xmlprocessed = true where msgType = :msgType and msgId = :msgId",
        //                     array(":msgType"=>0, ":msgId"=>$vMsgId, ":msgXML"=>$ex->getMessage()));
        //                 }       
        //                 else
        //                 throw($ex);
        //             }
        //             var_dump($trade->ID);
                    
        //         }
        //     }
            
        // }

        // var_dump($currentDate, $minDate, $client);
        foreach ($result->GetMessageIdsResult->int as $msgId) {
            var_dump($client->GetMessageContent(["id"=>$msgId]));
        }
    }
    // php yii test/arrest
}

