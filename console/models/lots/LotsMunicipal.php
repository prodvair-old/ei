<?php
namespace console\models\lots;

use Yii;
use yii\base\Module;

use console\models\GetInfoFor;
use console\models\torgs\TorgsMunicipal;
use console\models\banks\BankArrest;

use common\models\ErrorSend;

use common\models\Query\LotsCategory;

use common\models\Query\Lot\Lots;
use common\models\Query\Lot\Torgs;
use common\models\Query\Lot\Participants;
use common\models\Query\Lot\Banks;
use common\models\Query\Lot\LotCategorys;

use common\models\Query\Lot\Parser;

class LotsMunicipal extends Module
{
    public function id($id, $sendEmpty = true)
    {
        $lot = \common\models\Query\Municipal\LotsMunicipal::findOne($id);
        
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
            if ($lot->lotBidNumber != null && $lot->lotNum != null && $lot->lotPropDesc != null && $lot->lotStartSalePrice != null) {

                // Торг
                if (!$torg = Torgs::find()->where(['oldId' => $lot->torgs->trgId, 'typeId' => 4])->one()) {
                    echo "Торг для связи отцуствует! \nПробуем спарсить данный Торга. \n";

                    $parsingManager = TorgsMunicipal::id($lot->torgs->trgId);

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
                        $torg = Torgs::find()->where(['oldId' => $lot->torgs->trgId, 'typeId' => 4])->one();
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

                if ($lot->lotTorgReason) {
                    $info['torgReason'] = $lot->lotTorgReason;
                }
                if ($lot->lotSellTypeName) {
                    $info['sellType'] = $lot->lotSellTypeName;
                }
                if ($lot->lotSellTypeId) {
                    $info['sellTypeId'] = $lot->lotSellTypeId;
                }
                if ($lot->lotMinPrice) {
                    $info['minPrice'] = $lot->lotMinPrice;
                }
                if ($lot->lotIsBurdened) {
                    $info['isBurdened'] = $lot->lotIsBurdened;
                }
                if ($lot->lotBurdenDesc) {
                    $info['burdenDesc'] = $lot->lotBurdenDesc;
                }
                if ($lot->lotDepositDesc) {
                    $info['depositDesc'] = $lot->lotDepositDesc;
                }
                if ($lot->lotContractDesc) {
                    $info['contractDesc'] = $lot->lotContractDesc;
                }
                if ($lot->lotContractTerm) {
                    $info['contractTerm'] = $lot->lotContractTerm;
                }
                if ($lot->lotCurrency) {
                    $info['currency'] = $lot->lotCurrency;
                }
                if ($lot->lotTorgAcceptReason) {
                    $info['torgAcceptReason'] = $lot->lotTorgAcceptReason;
                }
                if ($lot->lotPropDesc) {
                    $info['propDesc'] = $lot->lotPropDesc;
                }
                if ($lot->lotOrgFullName) {
                    $info['orgFullName'] = $lot->lotOrgFullName;
                }
                if ($lot->lotPostAddress) {
                    $info['postAddress'] = $lot->lotPostAddress;
                }
                if ($lot->lotFundSize) {
                    $info['fundSize'] = $lot->lotFundSize;
                }
                if ($lot->lotOrgNominalValue) {
                    $info['orgNominal'] = $lot->lotOrgNominalValue;
                }
                if ($lot->lotAcsPart) {
                    $info['acsPart'] = $lot->lotAcsPart;
                }
                if ($lot->lotStepNegative) {
                    $info['stepNegative'] = $lot->lotStepNegative;
                }
                if ($lot->lotCondition) {
                    $info['condition'] = $lot->lotCondition;
                }
                if ($lot->lotWorkList) {
                    $info['workList'] = $lot->lotWorkList;
                }
                if ($lot->lotDocsList) {
                    $info['docsList'] = $lot->lotDocsList;
                }
                if ($lot->lotMarketPartDesc) {
                    $info['marketPartDesc'] = $lot->lotMarketPartDesc;
                }
                if ($lot->lotAreaUnmovable) {
                    $info['areaUnmovable'] = $lot->lotAreaUnmovable;
                }
                if ($lot->lotObjectsList) {
                    $info['objectsList'] = $lot->lotObjectsList;
                }
                if ($lot->lotEmplNum) {
                    $info['emplNum'] = $lot->lotEmplNum;
                }
                if ($lot->lotDepositReturn) {
                    $info['depositReturn'] = $lot->lotDepositReturn;
                }
                if ($lot->lotArea) {
                    $info['area'] = $lot->lotArea;
                }
                if ($lot->lotAreaMeters) {
                    $info['areaMeters'] = $lot->lotAreaMeters;
                }
                if ($lot->lotSecuringObligations) {
                    $info['securingObligations'] = $lot->lotSecuringObligations;
                }
                if ($lot->lotOfferSendDesc) {
                    $info['offerSendDesc'] = $lot->lotOfferSendDesc;
                }
                if ($lot->lotLimit) {
                    $info['limit'] = $lot->lotLimit;
                }
                if ($lot->lotWinnerDefineDesc) {
                    $info['winnerDefineDesc'] = $lot->lotWinnerDefineDesc;
                }
                if ($lot->lotPrivateConditions) {
                    $info['privateConditions'] = $lot->lotPrivateConditions;
                }
                if ($lot->lotPaymentConditions) {
                    $info['paymentConditions'] = $lot->lotPaymentConditions;
                }
                if ($lot->lotLastInfo) {
                    $info['lastInfo'] = $lot->lotLastInfo;
                }
                if ($lot->lotFederalStockPercent) {
                    $info['federalStockPercent'] = $lot->lotFederalStockPercent;
                }
                if ($lot->lotStockNum) {
                    $info['stockNum'] = $lot->lotStockNum;
                }
                if ($lot->lotStockPercentSale) {
                    $info['stockPercentSale'] = $lot->lotStockPercentSale;
                }
                if ($lot->lotFederalSharePerc) {
                    $info['federalSharePerc'] = $lot->lotFederalSharePerc;
                }
                if ($lot->lotSharePercSale) {
                    $info['sharePercSale'] = $lot->lotSharePercSale;
                }
                if ($lot->lotFinalPrice) {
                    $info['finalPrice'] = $lot->lotFinalPrice;
                }
                if ($lot->lotResult) {
                    $info['result'] = $lot->lotResult;
                }
                if ($lot->lotSinglePrice) {
                    $info['singlePrice'] = $lot->lotSinglePrice;
                }
                if ($lot->lotContractPayment) {
                    $info['contractPayment'] = $lot->lotContractPayment;
                }
                if ($vin = GetInfoFor::vin($lot->lotPropName)) {
                    $info['vin'] = $vin;
                }

                $cadastrNumber = GetInfoFor::cadastr($lot->lotPropName);

                if ($cadastrNumber) {
                    $cadastr = GetInfoFor::cadastr_address($cadastrNumber);
                    
                    $info['cadastreNumber'] = $cadastrNumber;
                    $info['flatFloor']      = $cadastr['flatFloor'];
                    $info['flatName']       = $cadastr['flatName'];
                }

                $address = GetInfoFor::address($lot->lotLocation);
        
                $info['address']        = $address['address'];

                $newLot->torgId         = $torg->id;
                $newLot->bankId         = $bank->id;
                $newLot->msgId          = $lot->lotBidNumber;
                $newLot->lotNumber      = $lot->lotNum;
                $newLot->title          = GetInfoFor::mb_ucfirst(GetInfoFor::title((($lot->lotPropDesc)? $lot->lotPropDesc : $lot->lotPropName)));
                $newLot->description    = GetInfoFor::mb_ucfirst($lot->lotPropDesc);
                $newLot->startPrice     = $lot->lotStartSalePrice;
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
                $parser->messageJson = $lot;
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