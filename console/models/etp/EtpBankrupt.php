<?php
namespace console\models\etp;

use Yii;
use yii\base\Module;

use console\models\GetInfoFor;

use common\models\ErrorSend;

use common\models\Query\Lot\Etp;
use common\models\Query\Lot\Parser;

class EtpBankrupt extends Module
{
    public function id($id)
    {
        $etp = \common\models\Query\Bankrupt\Etp::findOne($id);
        $parser = new Parser();

        $parser->tableNameTo = 'eiLot.etp';
        $parser->tableNameFrom = 'uds.tradeplace';
        $parser->tableIdFrom = $etp->id;

        $chekCase = Etp::find()->where(['oldId' => $etp->id])->all();

        if (!empty($chekCase[0])) {
            $parser->message = 'Был добавлен';
            $parser->statusId = 1;

            $parser->save();

            echo "Данный Торговой площадки уже был добавлен ID ".$etp->id.". \n";

            return true;
        } else {
            if ($etp->tradesite != null && $etp->tradename != null && $etp->inn != null) {
                $newEtp = new Etp();

                $info = [
                    'fullTitle' => $etp->ownername
                ];

                $newEtp->title          = GetInfoFor::mb_ucfirst($etp->tradename);
                $newEtp->url            = $etp->tradesite;
                $newEtp->inn            = $etp->inn;
                $newEtp->number         = $etp->idtradeplace;
                $newEtp->info           = $info;
                $newEtp->oldId          = $etp->id;
        
                try {
                    $newEtp->save();
        
                    $parser->message = 'Успешно добавлена';
                    $parser->statusId = 1;
        
                    $parser->save();

                    echo "Успешно добавлена в таблицу Торговой площадки ID ".$newEtp->id.", старый ID ".$etp->id.". \n";
                    return true;

                } catch (\Throwable $th) {
        
                    $parser->message = $th->getMessage();
                    $parser->statusId = 3;
        
                    $parser->save();

                    ErrorSend::parser($parser->id);
        
                    echo "Ошибка при добавлении в таблицу Торговой площадки ID ".$etp->id.". \n";
                    return false;
                }
        
            } else {
        
                $parser->message = 'Запись пуста';
                $parser->statusId = 2;
        
                $parser->save();

                echo "Пустые данные в таблице Торговой площадки ID ".$etp->id.". \n";
                return 2;
            }
        }
        return false;
    }
}