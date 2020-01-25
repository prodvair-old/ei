<?php
namespace console\models\lotPriceHistorys;

use Yii;
use yii\base\Module;

use console\models\GetInfoFor;

use common\models\Query\Bankrupt\Offerreductions;

use console\models\lots\LotsBankrupt;

use common\models\ErrorSend;

use common\models\Query\Lot\Lots;
use common\models\Query\Lot\LotPriceHistorys;
use common\models\Query\Lot\Parser;

class PriceHistorysBankrupt extends Module
{
    public function id($id)
    {
        $priceHistory= Offerreductions::findOne($id);
        $parser = new Parser();

        $parser->tableNameTo = 'eiLot.lotPriceHistorys';
        $parser->tableNameFrom = 'bailiff.offerreductions';
        $parser->tableIdFrom = $priceHistory->ofrRdnId;

        $chekPriceHistory = LotPriceHistorys::find()->where(['oldId' => $priceHistory->ofrRdnId])->all();

        if (!empty($chekPriceHistory[0])) {
            $parser->message = 'Был добавлен';
            $parser->statusId = 1;

            $parser->save();

            echo "Данный История снижения цены уже был добавлен ID ".$priceHistory->ofrRdnId.". \n";

            return true;
        } else {
            if ($priceHistory->ofrRdnLotNumber != null && $priceHistory->ofrRdnNumberInEFRSB != null && $priceHistory->ofrRdnDateTimeBeginInterval != null && $priceHistory->ofrRdnDateTimeEndInterval != null && $priceHistory->ofrRdnPriceInInterval != null) {

                // Лот
                if (!$lot = Lots::find()->where(['oldId' => $priceHistory->lot->id])->one()) {
                    echo "Лот для связи отцуствует! \nПробуем спарсить данный Лота. \n";

                    $parsingLot = LotsBankrupt::id($priceHistory->lot->id);

                    if (!$parsingLot && $parsingLot !== 2){
                        
                        $parser->message = 'Лот для связи отсуствует!';
                        $parser->messageJson = [
                            'oldLotId' => $priceHistory->lot->id,
                        ];
                        $parser->statusId = 3;
            
                        $parser->save();

                        ErrorSend::parser($parser->id);
            
                        echo "Ошибка при добавлении в таблицу Истории снижения цены ID ".$priceHistory->ofrRdnId.". \nОтсутствует Лот...\n";
                        return false;
                    } else {
                        $lot = Lots::find()->where(['oldId' => $priceHistory->lot->id])->one();
                    }
                }

                $newPriceHistory = new LotPriceHistorys();
        
                $newPriceHistory->lotId         = $lot->id;
                $newPriceHistory->msgId         = (string)$priceHistory->ofrRdnNumberInEFRSB;
                $newPriceHistory->lotNumber     = $priceHistory->ofrRdnLotNumber;
                $newPriceHistory->intervalBegin = $priceHistory->ofrRdnDateTimeBeginInterval;
                $newPriceHistory->intervalEnd   = $priceHistory->ofrRdnDateTimeEndInterval;
                $newPriceHistory->price         = $priceHistory->ofrRdnPriceInInterval;
                $newPriceHistory->oldId         = $priceHistory->ofrRdnId;
        
                try {
                    $newPriceHistory->save();
        
                    $parser->message = 'Успешно добавлена';
                    $parser->statusId = 1;
        
                    $parser->save();

                    echo "Успешно добавлена в таблицу Истории снижения цены ID ".$newPriceHistory->id.", старый ID ".$priceHistory->ofrRdnId.". \n";
                    return true;

                } catch (\Throwable $th) {
        
                    $parser->message = $th->getMessage();
                    $parser->statusId = 3;
        
                    $parser->save();

                    ErrorSend::parser($parser->id);
        
                    echo "Ошибка при добавлении в таблицу Истории снижения цены ID ".$priceHistory->ofrRdnId.". \n";
                    return false;
                }
        
            } else {
        
                $parser->message = 'Запись пуста';
                $parser->statusId = 2;
        
                $parser->save();

                echo "Пустые данные в таблице Истории снижения цены ID ".$priceHistory->ofrRdnId.". \n";
                return 2;
            }
        }
        return false;
    }
}