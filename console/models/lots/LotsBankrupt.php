<?php
namespace console\models\lots;

use Yii;
use yii\base\Module;

use console\models\GetInfoFor;
use console\models\torgs\TorgsBankrupt;

use common\models\ErrorSend;

use common\models\Query\LotsCategory;

use common\models\Query\Lot\Lots;
use common\models\Query\Lot\Torgs;
use common\models\Query\Lot\LotCategorys;

use common\models\Query\Lot\Parser;

class LotsBankrupt extends Module
{
    public function id($id)
    {
        $lot = \common\models\Query\Bankrupt\Lots::findOne($id);
        
        $parser = new Parser();

        $parser->tableNameTo = 'eiLot.lots';
        $parser->tableNameFrom = 'uds.obj$lots';
        $parser->tableIdFrom = $lot->id;

        $chekLot = Lots::find()->where(['oldId' => $lot->id])->all();
        if ($chekLot[0]) {

            $parser->message = 'Был добавлена';
            $parser->statusId = 1;

            $parser->save();

            echo "Данный Лот уже был добавлен ID ".$lot->id.". \n";

            return true;
        } else {
            if ($lot->description != null && $lot->torgy->msgid != null && $lot->lotid != null && $lot->startprice != null && $lot->auctionstepunit != null && $lot->advancestepunit != null) {

                // Торг
                if (!$torg = Torgs::find()->where(['oldId' => $lot->torgy->id])->one()) {
                    echo "Торг для связи отцуствует! \nПробуем спарсить данный Торга. \n";

                    $parsingManager = TorgsBankrupt::id($lot->torgy->id);

                    if (!$parsingManager && !$parsingManager[0] && $parsingManager !== 2){
                        
                        $parser->message = 'Торг для связи отсуствует!';
                        $parser->messageJson = [
                            'oldTorgId' => $lot->torgy->id,
                        ];
                        $parser->statusId = 3;
            
                        $parser->save();

                        ErrorSend::parser($parser->id);
            
                        echo "Ошибка при добавлении в таблицу Лотов ID ".$torg->id.". \nОтсутствует Торг...\n";
                        return false;
                    } else {
                        $torg = Torgs::findOne([
                            'id' => $parsingManager[1]
                        ]);
                    }
                }


                $newLot = new Lots();

                $info = [
                    'priceReduction'    => $lot->pricereduction,
                    'vin'               => GetInfoFor::vin($lot->description),
                    'address'           => $address['address'],
                ];

                $cadastrNumber = GetInfoFor::cadastr($lot->description);

                if ($cadastrNumber) {
                    $cadastr = GetInfoFor::cadastr_address($cadastrNumber);
                    
                    $info['cadastreNumber'] = $cadastrNumber;
                    $info['flatFloor']      = $cadastr['flatFloor'];
                    $info['flatName']       = $cadastr['flatName'];
                }

                $address = GetInfoFor::address(($cadastr['address'])? $cadastr['address'] : $lot->torgy->case->bnkr->address);
        
                $info['address']       = $address['address'];

                if ($lot->purchaselots[0]) {
                    foreach ($lot->purchaselots as $key => $value) {
                        if ($value->pheLotNumber == $lot->lotid) {
                            $title  = $value->pheLotName;
                        }
                        if ($value->pheLotNumber == $lot->lotid) {
                            $status  = $value->pheLotStatus;
                        }
                    }
                }

                foreach ($lot->images as $key => $image) {
                    $images[] = [
                        'max' => 'img/lot/bankrupt/'.$image->objid.'/'.$image->fileurl,
                        'min' => 'img/lot/bankrupt/'.$image->objid.'/'.$image->fileurl,
                    ];
                }
        
                $newLot->torgId         = $torg->id;
                $newLot->msgId          = $lot->torgy->msgid;
                $newLot->lotNumber      = $lot->lotid;
                $newLot->title          = ($title)? $title : GetInfoFor::mb_ucfirst(GetInfoFor::title($lot->description));
                $newLot->description    = GetInfoFor::mb_ucfirst($lot->description);
                $newLot->startPrice     = $lot->startprice;
                $newLot->step           = $lot->stepprice;
                $newLot->stepTypeId     = ($lot->auctionstepunit == 'Percent')? 1 : 2;
                $newLot->deposit        = $lot->advance;
                $newLot->depositTypeId  = ($lot->advancestepunit == 'Percent')? 1 : 2;
                $newLot->status         = ($status)? $status : $lot->lotStatus;
                $newLot->info           = $info;
                $newLot->images         = $images;
                $newLot->regionId       = $address['regionId'];
                $newLot->city           = $address['address']['city'];
                $newLot->district       = $address['address']['district'];
                $newLot->oldId          = $lot->id;
        
                try {
                    $newLot->save();
        
                    $parser->message = 'Успешно добавлена';
                    $parser->statusId = 1;
        
                    $parser->save();

                    $lotsCategory = LotsCategory::find()->all();
                        
                    foreach ($lotsCategory as $lotCategory) {
                        foreach ($lotCategory->bankrupt_categorys as $categoryId => $subcategory) {
                            foreach ($lot->lotCategorys as $oldCategoryId => $oldCategory) {

                                if ($oldCategoryId == $categoryId) {
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
                                            'oldLotId'      => $lot->id,
                                            'oldCategoryId' => $oldCategoryId,
                                        ];
                                        $parser->statusId = 3;
                            
                                        $parser->save();
                                        
                                        ErrorSend::parser($parser->id);
                            
                                        echo "Ошибка при добавлении в таблицу Лотов ID ".$lot->id.". \nОшибка добавления категория ID ".$oldCategoryId."\n";
                                        return false;
                                    }
                                }

                            }
                        }
                        
                    }
        
                    echo "Успешно добавлена в таблицу Лотов ID ".$newLot->id.", старый ID ".$lot->id.". \n";
                    return true;

                } catch (\Throwable $th) {
        
                    $parser->message = $th->getMessage();
                    $parser->statusId = 3;
        
                    $parser->save();
                    
                    ErrorSend::parser($parser->id);
        
                    echo "Ошибка при добавлении в таблицу Лотов ID ".$lot->id.". \n";
                    return false;
                }
        
            } else {
        
                $parser->message = 'Запись пуста';
                $parser->statusId = 2;
        
                $parser->save();

                ErrorSend::parser($parser->id);

                echo "Пустые данные в таблице Лотов ID ".$lot->id.". \n";
                return 2;
            }
        }
        return false;
    }
}