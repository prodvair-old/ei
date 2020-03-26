<?php
namespace console\models\lots;

use Yii;
use yii\base\Module;

use console\models\GetInfoFor;
use console\models\torgs\TorgsBankrupt;

use common\models\ErrorSend;

use common\models\Query\LotsCategory;

use common\models\Query\Bankrupt\Purchaselots;
use common\models\Query\Lot\Lots;
use common\models\Query\Lot\Torgs;
use common\models\Query\Lot\LotCategorys;

use common\models\Query\Lot\Parser;

class LotsBankruptStatus extends Module
{
    public function id($id, $sendEmpty = true)
    {
        $lot = Purchaselots::find()->where(['pheLotId' => $id])->one();
        $updateCheck = false;
    
        if (empty($parser = Parser::find()->where(['tableNameFrom' => 'bailiff.purchaselots', 'tableIdFrom' => $lot->pheLotId])->one())) {
            $parser = new Parser();

            $parser->tableNameFrom  = 'bailiff.purchaselots';
            $parser->tableNameTo    = 'eiLot.lots';
            $parser->tableIdFrom    = $lot->pheLotId;
            $parser->tableIdTo      = $lot->lot->id;
        } else {
            $updateCheck = true;
        }

        if (empty($lot->lot)) {

            $parser->message = 'Не найден лот';
            $parser->status = 'empty';

            if ($updateCheck) {
                $parser->update();
            } else {
                $parser->save();
            }

            if ($sendEmpty) {
                ErrorSend::parser($parser->id);
            }

            echo "Лот не найден ID ".$lot->pheLotId.". \n";

            return 2;
        } else {
            if ($lot->lot->status !== $lot->pheLotStatus) {

                $editLot = Lots::findOne(['id' => $lot->lot->id]);

                $info = $editLot->info;

                if (empty($editLot->address)) {
                    if ($info['cadastreNumber']) {
                        $cadastr = GetInfoFor::cadastr_address($info['cadastreNumber']);
                    }
                    $address = GetInfoFor::address((!empty($cadastr['address']))? $cadastr['address'] : $editLot->torg->bankrupt->address);

                    $editLot->address = $address['fullAddress'];
                }
        
                $editLot->status         = $lot->pheLotStatus;

                if ($lot->pheLotName) {
                    $editLot->title      = $lot->pheLotName;
                }

                $info['etpUrl']          = $lot->pheLotHost;
                $info['etpLotUrl']       = $lot->pheLotUrl;
                $editLot->info           = $info;
        
                try {
                    $editLot->update();
        
                    $parser->message = 'Успешно добавлена';

                    if ($lot->pheLotIsNotUpdated === 0) {
                        $parser->status = 'updated';
                    } else {
                        $parser->statusId = 'success';
                    }
        
                    if ($updateCheck) {
                        $parser->update();
                    } else {
                        $parser->save();
                    }

                    echo "Успешно редактирована таблица Лотов ID ".$editLot->id.". \n";
                    return true;

                } catch (\Throwable $th) {
        
                    $parser->message = $th->getMessage();
                    $parser->status = 'error';
        
                    if ($updateCheck) {
                        $parser->update();
                    } else {
                        $parser->save();
                    }
                    
                    ErrorSend::parser($parser->id);
        
                    echo "Ошибка при редактировании таблицы Лотов ID ".$lot->pheLotId.". \n";
                    return false;
                }
        
            } else {
        
                $parser->message = 'Данные верны';
                if ($lot->pheLotIsNotUpdated === 0) {
                    $parser->status = 'updated';
                } else {
                    $parser->statusId = 'success';
                }

                try {
                    $editLot = Lots::findOne(['id' => $lot->lot->id]);

                    if (empty($editLot->address)) {
                        if ($info['cadastreNumber']) {
                            $cadastr = GetInfoFor::cadastr_address($info['cadastreNumber']);
                        }
                        $address = GetInfoFor::address((!empty($cadastr['address']))? $cadastr['address'] : $editLot->torg->bankrupt->address);
    
                        $editLot->address = $address['fullAddress'];
                    }

                    $editLot->update();
                } catch (\Throwable $th) {
                    echo $th->getMessage();
                }

                if ($updateCheck) {
                    $parser->update();
                } else {
                    $parser->save();
                }

                echo "Данные верны ID ".$lot->pheLotId.". \n";
                return 2;
            }
        }
        return false;
    }
}