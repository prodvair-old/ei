<?php
namespace console\models\sro;

use Yii;
use yii\base\Module;

use console\models\GetInfoFor;

use common\models\ErrorSend;

use common\models\Query\Lot\Sro;
use common\models\Query\Lot\Parser;

class SroBankrupt extends Module
{
    public function id($id)
    {
        $sro = \common\models\Query\Bankrupt\Sro::findOne($id);
        $parser = new Parser();

        $chekSro = Sro::find()->where(['oldId' => $sro->id])->all();

        if ($chekSro[0]) {
            $parser->tableNameTo = 'eiLot.sro';
            $parser->tableNameFrom = 'uds.obj$sro';
            $parser->tableIdFrom = $sro->id;
            $parser->message = 'Успешно добавлена';
            $parser->statusId = 1;

            $parser->save();

            echo "Данный СРО уже был добавлен ID ".$sro->id.". \n";

            return true;
        } else {
            if ($sro->title != null || $sro->inn != null || $sro->ogrn != null || $sro->address != null || $sro->regnum != null || $sro->sroid != null) {
                $newSro = new Sro();
        
                $address = GetInfoFor::address($sro->address);
                
        
                $info['address'] = $address['address'];
        
                $newSro->title      = GetInfoFor::mb_ucfirst($sro->title);
                $newSro->inn        = $sro->inn;
                $newSro->orgn       = $sro->ogrn;
                $newSro->address    = $address['fullAddress'];
                $newSro->sroId      = $sro->sroid;
                $newSro->regnum     = $sro->regnum;
                $newSro->info       = $info;
                $newSro->regionId   = $address['regionId'];
                $newSro->city       = $address['address']['city'];
                $newSro->district   = $address['address']['district'];
                $newSro->oldId      = $sro->id;
        
                try {
                    $newSro->save();
        
                    $parser->tableNameTo = 'eiLot.sro';
                    $parser->tableNameFrom = 'uds.obj$sro';
                    $parser->tableIdFrom = $sro->id;
                    $parser->message = 'Успешно добавлена';
                    $parser->statusId = 1;
        
                    $parser->save();

                    echo "Успешно добавлена в таблицу СРО ID ".$newSro->id.", старый ID ".$sro->id.". \n";
                    return true;

                } catch (\Throwable $th) {
        
                    $parser->tableNameTo = 'eiLot.sro';
                    $parser->tableNameFrom = 'uds.obj$sro';
                    $parser->tableIdFrom = $sro->id;
                    $parser->message = $th->getMessage();
                    $parser->statusId = 3;
        
                    $parser->save();

                    ErrorSend::parser($parser->id);
        
                    echo "Ошибка при добавлении в таблицу СРО ID ".$sro->id.". \n";
                    return false;
                }
        
            } else {
        
                $parser->tableNameTo = 'eiLot.sro';
                $parser->tableNameFrom = 'uds.obj$sro';
                $parser->tableIdFrom = $sro->id;
                $parser->message = 'Запись пуста';
                $parser->statusId = 2;
        
                $parser->save();

                echo "Пустые данные в таблице СРО ID ".$sro->id.". \n";
                return 2;
            }
        }
        return false;
    }
}