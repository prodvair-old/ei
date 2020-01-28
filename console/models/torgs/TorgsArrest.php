<?php
namespace console\models\torgs;

use Yii;
use yii\base\Module;

use console\models\GetInfoFor;
use console\models\managers\ManagerArrest;

use common\models\ErrorSend;

use common\models\Query\Lot\Torgs;
use common\models\Query\Lot\Managers;

use common\models\Query\Lot\Parser;

class TorgsArrest extends Module
{
    public function id($id)
    {
        $torg = \common\models\Query\Arrest\Torgs::findOne($id);
        $parser = new Parser();

        $parser->tableNameTo = 'eiLot.torgs';
        $parser->tableNameFrom = 'bailiff.torgs';
        $parser->tableIdFrom = $torg->trgId;

        $chekTorg = Torgs::find()->where(['oldId' => $torg->trgId, 'typeId' => 2])->all();
        if (!empty($chekTorg[0])) {

            $parser->message = 'Был добавлена';
            $parser->statusId = 1;

            $parser->save();

            echo "Данный Торга уже был добавлен ID ".$torg->trgId.". \n";

            return true;
        } else {
            if ($torg->trgPublished != null && $torg->trgBidFormId != null) {

                // Организатор торгов
                if (!$manager = Managers::find()->where(['oldId' => $torg->trgOrganizationId])->one()) {
                    echo "Организатор торгов для связи отцуствует! \nПробуем спарсить данный Организатора торгов. \n";

                    if (!ManagerArrest::torg($torg)){
                        
                        $parser->message = 'Организатор торгов для связи отсуствует!';
                        $parser->messageJson = [
                            'oldManagerId' => $torg->trgOrganizationId,
                        ];
                        $parser->statusId = 2;
            
                        $parser->save();

                        echo "Отсутствует Организатор торгов...\n";
                    } else {
                        $manager = Managers::find()->where(['oldId' => $torg->trgOrganizationId])->one();
                    }
                }

                $newTorg = new Torgs();
        
                $info = [
                    'auctionType'       => $torg->trgBidKindName,
                    'auctionDate'       => $torg->trgBidAuctionDate,
                    'auctionPlace'      => $torg->trgBidAuctionPlace,
                    'SummationPlace'    => $torg->trgSummationPlace,
                    'url'               => $torg->trgBidUrl,
                    'notificationUrl'   => $torg->trgNotificationUrl,
                    'contactFio'        => $torg->trgFio,
                    'lotCount'          => $torg->trgLotCount,
                    'lastChanged'       => GetInfoFor::date_check($torg->trgLastChanged),
                    'withDrawType'      => $torg->trgWithDrawType,
                    'requirement'       => $torg->trgAppRequirement,
                    'openingDate'       => $torg->trgOpeningDate,
                    'placeRequest'      => $torg->trgPlaceRequest,
                ];
        
                $newTorg->typeId        = 2;
                $newTorg->publisherId   = $manager->id;
                $newTorg->msgId         = $torg->trgBidNumber;
                $newTorg->description   = GetInfoFor::mb_ucfirst($torg->trgAppReceiptDetails);
                $newTorg->startDate     = GetInfoFor::date_check($torg->trgStartDateRequest);
                $newTorg->endDate       = GetInfoFor::date_check($torg->trgExpireDate);
                $newTorg->publishedDate = GetInfoFor::date_check($torg->trgPublished);
                $newTorg->tradeTypeId   = $torg->trgBidFormId;
                $newTorg->info          = $info;
                $newTorg->oldId         = $torg->trgId;
        
                try {
                    $newTorg->save();
        
                    $parser->message = 'Успешно добавлена';
                    $parser->statusId = 1;
        
                    $parser->save();
        
                    echo "Успешно добавлена в таблицу Торгов ID ".$newTorg->id.", старый ID ".$torg->trgId.". \n";
                    return [true, $newTorg->id];

                } catch (\Throwable $th) {
        
                    $parser->message = $th->getMessage();
                    $parser->statusId = 3;
        
                    $parser->save();
                    
                    ErrorSend::parser($parser->id);
        
                    echo "Ошибка при добавлении в таблицу Торгов ID ".$torg->trgId.". \n";
                    return false;
                }
        
            } else {
        
                $parser->message = 'Запись пуста';
                $parser->statusId = 2;
        
                $parser->save();

                echo "Пустые данные в таблице Торгов ID ".$torg->trgId.". \n";
                return 2;
            }
        }
        return false;
    }
}