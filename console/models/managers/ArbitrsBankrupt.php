<?php
namespace console\models\managers;

use Yii;
use yii\base\Module;

use console\models\GetInfoFor;
use console\models\sro\SroBankrupt;

use common\models\ErrorSend;

use common\models\Query\Bankrupt\Arbitrs;

use common\models\Query\Lot\Managers;
use common\models\Query\Lot\Sro;
use common\models\Query\Lot\Parser;

class ArbitrsBankrupt extends Module
{
    public function id($id)
    {
        $arbitr = Arbitrs::findOne($id);
        $parser = new Parser();

        $chekManager = Managers::find()->where(['oldId' => $arbitr->id, 'type' => 'arbitr'])->all();
        if (!empty($chekManager[0])) {

            $parser->tableNameTo = 'eiLot.managers';
            $parser->tableNameFrom = 'uds.obj$arbitrs';
            $parser->tableIdFrom = $arbitr->id;
            $parser->message = 'Успешно добавлена';
            $parser->statusId = 1;

            $parser->save();

            echo "Данный Арбитражный Управляющий (Менеджер) уже был добавлен ID ".$arbitr->id.". \n";

            return true;
        } else {
            if ($arbitr->person->lname != null || $arbitr->person->fname != null || $arbitr->person->mname != null || $arbitr->person->inn != null || $arbitr->postaddress != null || $arbitr->arbid != null) {

                if (!$sro = Sro::find()->where(['oldId' => $arbitr->sro->id])->one()) {
                    echo "СРО для связи отцуствует! \nПробуем спарсить данный СРО. \n";

                    $parsingSro = SroBankrupt::id($arbitr->sro->id);

                    if (!$parsingSro && $parsingSro !== 2){
                        $parser->tableNameTo = 'eiLot.managers';
                        $parser->tableNameFrom = 'uds.obj$arbitrs';
                        $parser->tableIdFrom = $arbitr->id;
                        $parser->message = 'СРО для связи отсуствует!';
                        $parser->statusId = 3;
            
                        $parser->save();

                        ErrorSend::parser($parser->id);
            
                        echo "Ошибка при добавлении в таблицу Арбитражных Управляющих (Менеджеров) ID ".$arbitr->id.". \nОтсутствует СРО...\n";
                        return false;
                    }
                }

                $newManager = new Managers();
        
                $address = GetInfoFor::address($arbitr->postaddress);
        
                $info = [
                    'address'   => $address['address'],
                    'ogrn'      => $arbitr->ogrn,
                    'snils'     => $arbitr->person->snils,
                    'birthDay'  => $arbitr->person->birthday,
                    'pol'       => ($arbitr->person->sexid != NULL && $arbitr->person->sexid == '1009')? 'Женщина' : 'Мужчина',
                    'polId'     => ($arbitr->person->sexid != NULL && $arbitr->person->sexid == '1009')? 0 : 1,
                ];
        
                $newManager->type           = 'arbitr';
                $newManager->lastName       = GetInfoFor::mb_ucfirst($arbitr->person->lname);
                $newManager->firstName      = GetInfoFor::mb_ucfirst($arbitr->person->fname);
                $newManager->middleName     = GetInfoFor::mb_ucfirst($arbitr->person->mname);
                $newManager->fullName       = GetInfoFor::mb_ucfirst($arbitr->person->fullName);
                $newManager->inn            = $arbitr->person->inn;
                $newManager->address        = $address['fullAddress'];
                $newManager->arbId          = $arbitr->arbid;
                $newManager->regnum         = $arbitr->regnum;
                $newManager->info           = $info;
                $newManager->regionId       = $address['regionId'];
                $newManager->city           = $address['address']['city'];
                $newManager->district       = $address['address']['district'];
                $newManager->sroId          = $sro->id;
                $newManager->oldId          = $arbitr->id;
        
                try {
                    $newManager->save();
        
                    $parser->tableNameTo = 'eiLot.managers';
                    $parser->tableNameFrom = 'uds.obj$arbitrs';
                    $parser->tableIdFrom = $arbitr->id;
                    $parser->message = 'Успешно добавлена';
                    $parser->statusId = 1;
        
                    $parser->save();
        
                    echo "Успешно добавлена в таблицу Арбитражных Управляющих (Менеджеров) ID ".$newManager->id.", старый ID ".$arbitr->id.". \n";
                    return true;

                } catch (\Throwable $th) {
        
                    $parser->tableNameTo = 'eiLot.managers';
                    $parser->tableNameFrom = 'uds.obj$arbitrs';
                    $parser->tableIdFrom = $arbitr->id;
                    $parser->message = $th->getMessage();
                    $parser->statusId = 3;
        
                    $parser->save();
                    
                    ErrorSend::parser($parser->id);
        
                    echo "Ошибка при добавлении в таблицу Арбитражных Управляющих (Менеджеров) ID ".$arbitr->id.". \n";
                    return false;
                }
        
            } else {
        
                $parser->tableNameTo = 'eiLot.managers';
                $parser->tableNameFrom = 'uds.obj$arbitrs';
                $parser->tableIdFrom = $arbitr->id;
                $parser->message = 'Запись пуста';
                $parser->statusId = 2;
        
                $parser->save();

                echo "Пустые данные в таблице Арбитражных Управляющих (Менеджеров) ID ".$arbitr->id.". \n";
                return 2;
            }
        }
        return false;
    }
}