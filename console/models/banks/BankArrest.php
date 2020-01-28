<?php
namespace console\models\banks;

use Yii;
use yii\base\Module;

use console\models\GetInfoFor;

use common\models\ErrorSend;

use common\models\Query\Lot\Banks;
use common\models\Query\Lot\Parser;

class BankArrest extends Module
{
    public function lot($lot)
    {
        $parser = new Parser();

        $parser->tableNameTo = 'eiLot.banks';
        $parser->tableNameFrom = 'bailiff.lots';
        $parser->tableIdFrom = $lot->lotId;

        $chekBank = Banks::find()->where(['payment' => $lot->lotPaymentRequisitesRs])->all();
        if (!empty($chekBank[0])) {
            
            $parser->message = 'Был добавлен';
            $parser->statusId = 1;

            $parser->save();

            echo "Данный Банк уже был добавлен ID ".$chekBank[0]->id.". \n";

            return $chekBank[0]->id;
        } else {
            if ($lot->lotPaymentRequisitesBankName != null || $lot->lotPaymentRequisitesRs != null || $lot->lotPaymentRequisitesBik != null || $lot->lotPaymentRequisitesPs != null) {

                $newBank = new Banks();
        
                $newBank->bik       = $lot->lotPaymentRequisitesBik;
                $newBank->name      = $lot->lotPaymentRequisitesBankName;
                $newBank->payment   = $lot->lotPaymentRequisitesRs;
                $newBank->personal  = $lot->lotPaymentRequisitesPs;
        
                try {
                    $newBank->save();
        
                    $parser->message = 'Успешно добавлена';
                    $parser->statusId = 1;
        
                    $parser->save();
        
                    echo "Успешно добавлена в таблицу Банков ID ".$newBank->id.", старый ID ".$lot->lotPaymentRequisitesRs.". \n";
                    return $newBank->id;

                } catch (\Throwable $th) {
        
                    $parser->message = $th->getMessage();
                    $parser->statusId = 3;
        
                    $parser->save();
                    
                    ErrorSend::parser($parser->id);
        
                    echo "Ошибка при добавлении в таблицу Банков ID ".$torg->lotPaymentRequisitesRs.". \n";
                    return false;
                }
        
            } else {
        
                $parser->message = 'Запись пуста';
                $parser->statusId = 2;
        
                $parser->save();

                echo "Пустые данные в таблице Банков ID ".$torg->lotPaymentRequisitesRs.". \n";
                return false;
            }
        }
        return false;
    }
}