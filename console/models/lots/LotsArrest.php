<?php
namespace console\models\lots;

use Yii;
use yii\base\Module;

use console\models\GetInfoFor;
use console\models\torgs\TorgsArrest;
use console\models\banks\BankArrest;

use common\models\ErrorSend;

use common\models\Query\LotsCategory;

use common\models\Query\Lot\Lots;
use common\models\Query\Lot\Torgs;
use common\models\Query\Lot\Participants;
use common\models\Query\Lot\Banks;
use common\models\Query\Lot\LotCategorys;

use common\models\Query\Lot\Parser;

class LotsArrest extends Module
{
    public function id($id, $sendEmpty = true)
    {
        $lot = \common\models\Query\Arrest\LotsArrest::findOne($id);
        
        $parser = new Parser();

        $parser->tableNameTo = 'eiLot.lots';
        $parser->tableNameFrom = 'bailiff.lots';
        $parser->tableIdFrom = $lot->lotId;

        $chekLot = Lots::find()->joinWith(['torg'])->where(['lots.oldId' => $lot->lotId, 'torg.typeId' => 2])->all();
        if (!empty($chekLot[0])) {

            $parser->message = 'Был добавлена';
            $parser->statusId = 1;

            $parser->save();

            echo "Данный Лот уже был добавлен ID ".$lot->lotId.". \n";

            return true;
        } else {
            if ($lot->lotBidNumber != null && $lot->lotNum != null && $lot->lotStartPrice != null) {

                // Торг
                if (!$torg = Torgs::find()->where(['oldId' => $lot->torgs->trgId, 'typeId' => 2])->one()) {
                    echo "Торг для связи отцуствует! \nПробуем спарсить данный Торга. \n";

                    $parsingManager = TorgsArrest::id($lot->torgs->trgId);

                    if (!$parsingManager && !$parsingManager[0] && $parsingManager !== 2){
                        
                        $parser->message = 'Торг для связи отсуствует!';
                        $parser->messageJson = [
                            'oldTorgId' => $lot->torgs->trgId,
                        ];
                        $parser->statusId = 3;
            
                        $parser->save();

                        ErrorSend::parser($parser->id);
            
                        echo "Ошибка при добавлении в таблицу Лотов ID ".$lot->lotId.". \nОтсутствует Торг...\n";
                        return false;
                    } else {
                        $torg = Torgs::find()->where(['oldId' => $lot->torgs->trgId, 'typeId' => 2])->one();
                    }
                }

                // Банк
                if (!$bank = Banks::find()->where(['payment' => $lot->lotPaymentRequisitesRs])->one()) {
                    echo "Организатор торгов для связи отцуствует! \nПробуем спарсить данный Организатора торгов. \n";

                    if (!BankArrest::lot($lot)){
                        
                        $parser->message = 'Организатор торгов для связи отсуствует!';
                        $parser->messageJson = [
                            'oldBankPayment' => $lot->lotPaymentRequisitesRs,
                        ];
                        $parser->statusId = 2;
            
                        $parser->save();

                        echo "Ошибка при добавлении в таблицу Лотов ID ".$lot->lotId.". \nОтсутствует Организатор торгов...\n";
                    } else {
                        $bank = Banks::find()->where(['payment' => $lot->lotPaymentRequisitesRs])->one();
                    }
                }

                $newLot = new Lots();

                $info = [
                    'torgReason'        => $lot->lotTorgReason,
                    'sellType'          => $lot->lotSellTypeName,
                    'sellTypeId'        => $lot->lotSellTypeId,
                    'minPrice'          => $lot->lotMinPrice,
                    'isBurdened'        => $lot->lotIsBurdened,
                    'burdenDesc'        => $lot->lotBurdenDesc,
                    'depositDesc'       => $lot->lotDepositDesc,
                    'contractDesc'      => $lot->lotContractDesc,
                    'contractTerm'      => $lot->lotContractTerm,
                    'currency'          => $lot->lotCurrency,
                    'vin'               => GetInfoFor::vin($lot->lotPropName),
                ];

                $cadastrNumber = GetInfoFor::cadastr($lot->lotPropName);

                if ($cadastrNumber) {
                    $cadastr = GetInfoFor::cadastr_address($cadastrNumber);
                    
                    $info['cadastreNumber'] = $cadastrNumber;
                    $info['flatFloor']      = $cadastr['flatFloor'];
                    $info['flatName']       = $cadastr['flatName'];
                }

                $address = GetInfoFor::address($lot->lotKladrLocationName);
        
                $info['address']       = $address['address'];

                $newLot->torgId         = $torg->id;
                $newLot->bankId         = $bank->id;
                $newLot->msgId          = $lot->lotBidNumber;
                $newLot->lotNumber      = $lot->lotNum;
                $newLot->title          = GetInfoFor::mb_ucfirst(GetInfoFor::title(($lot->lotPropName)? $lot->lotPropName : $lot->lotPropertyTypeName ));
                $newLot->description    = GetInfoFor::mb_ucfirst($lot->lotPropName);
                $newLot->startPrice     = $lot->lotStartPrice;
                $newLot->step           = $lot->lotPriceStep;
                $newLot->stepTypeId     = 2;
                $newLot->deposit        = $lot->lotDepositSize;
                $newLot->depositTypeId  = 2;
                $newLot->status         = $lot->lotBidStatusName;
                $newLot->info           = $info;
                $newLot->images         = null;
                $newLot->address        = $address['fullAddress'];
                $newLot->regionId       = $address['regionId'];
                $newLot->city           = $address['address']['city'];
                $newLot->district       = $address['address']['district'];
                $newLot->oldId          = $lot->lotId;
        
                try {
                    $newLot->save();
        
                    $parser->message = 'Успешно добавлена';
                    $parser->statusId = 1;
        
                    $parser->save();

                    $lotsCategory = LotsCategory::find()->all();
                        
                    foreach ($lotsCategory as $lotCategory) {
                        foreach ($lotCategory->arrest_categorys as $categoryId => $subcategory) {
                            if ($lot->lotPropertyTypeId == $categoryId) {
                                $newCategory = new LotCategorys();

                                $newCategory->lotId         = $newLot->id;
                                $newCategory->categoryId    = $categoryId;
                                $newCategory->name          = $subcategory['name'];
                                $newCategory->nameTranslit  = $subcategory['translit'];

                                try {
                                    $newCategory->save();

                                    $newLot->published = true;

                                    $newLot->update();

                                } catch (\Throwable $th) {

                                    $parser->message = $th->getMessage();
                                    $parser->messageJson = [
                                        'lotId'         => $newLot->id,
                                        'oldLotId'      => $lot->lotId,
                                        'oldCategoryId' => $lot->lotPropertyTypeId,
                                    ];
                                    $parser->statusId = 3;
                        
                                    $parser->save();
                                    
                                    ErrorSend::parser($parser->id);
                        
                                    echo "Ошибка при добавлении в таблицу Лотов ID ".$lot->lotId.". \nОшибка добавления категория ID ".$lot->lotPropertyTypeId."\n";
                                    return false;
                                }
                            }
                        }
                    }
        
                    if ($lot->lotWinnerName) {
                        $newParticipant = new Participants();

                        $newParticipant->lotId  = $newLot->id;
                        $newParticipant->msgId  = $lot->lotBidNumber;
                        $newParticipant->name   = $lot->lotWinnerName;
                        $newParticipant->phone  = $lot->lotWinnerPhone;
                        $newParticipant->status = 3;
                        $newParticipant->info   = [
                            'inn'       => $lot->lotWinnerInn,
                            'kpp'       => $lot->lotWinnerKpp,
                            'ogrnIp'    => $lot->lotWinnerOgrnip,
                            'ogrn'      => $lot->lotWinnerOgrn,
                            'address'   => $lot->lotWinnerLocation,
                        ];

                        try {
                            $newParticipant->save();

                            $newLot->published = true;

                            $newLot->update();

                        } catch (\Throwable $th) {

                            $parser->message = $th->getMessage();
                            $parser->messageJson = [
                                'lotId'         => $newLot->id,
                                'oldLotId'      => $lot->lotId,
                            ];
                            $parser->statusId = 3;
                
                            $parser->save();
                            
                            ErrorSend::parser($parser->id);
                
                            echo "Ошибка при добавлении в таблицу Лотов ID ".$lot->lotId.". \nОшибка добавления победителя ID ".$newParticipant->id."\n";
                            return false;
                        }
                    }

                    echo "Успешно добавлена в таблицу Лотов ID ".$newLot->id.", старый ID ".$lot->lotId.". \n";
                    return true;

                } catch (\Throwable $th) {
        
                    $parser->message = $th->getMessage();
                    $parser->statusId = 3;
        
                    $parser->save();
                    
                    ErrorSend::parser($parser->id);
        
                    echo "Ошибка при добавлении в таблицу Лотов ID ".$lot->lotId.". \n";
                    return false;
                }
        
            } else {
        
                $parser->message = 'Запись пуста';
                $parser->statusId = 2;
        
                $parser->save();

                if ($sendEmpty) {
                    ErrorSend::parser($parser->id);
                }

                echo "Пустые данные в таблице Лотов ID ".$lot->lotId.". \n";
                return 2;
            }
        }
        return false;
    }
}