<?php
namespace console\models\cases;

use Yii;
use yii\base\Module;

use console\models\GetInfoFor;

use common\models\ErrorSend;

use common\models\Query\Lot\Cases;
use common\models\Query\Lot\Parser;

class CasesBankrupt extends Module
{
    public function id($id)
    {
        $case = \common\models\Query\Bankrupt\Cases::findOne($id);
        $parser = new Parser();

        $parser->tableNameTo = 'eiLot.cases';
        $parser->tableNameFrom = 'uds.obj$cases';
        $parser->tableIdFrom = $case->id;

        $chekCase = Cases::find()->where(['oldId' => $case->id])->all();

        if (!empty($chekCase[0])) {
            $parser->message = 'Был добавлен';
            $parser->statusId = 1;

            $parser->save();

            echo "Данный Дел по лоту уже был добавлен ID ".$case->id.". \n";

            return true;
        } else {
            if ($case->caseid != null && $case->regnum != null) {
                $newCase = new Cases();
        
                $info = [
                    'caseOpen'  => $case->caseopen,
                    'caseClose' => $case->caseclose,
                    'regYear'   => $case->regyear,
                ];

                $newCase->number    = str_replace(' ', '', $case->caseid);
                $newCase->regnum    = $case->regnum;
                $newCase->judge     = $case->regpostfix;
                $newCase->info      = $info;
                $newCase->oldId     = $case->id;
        
                try {
                    $newCase->save();
        
                    $parser->message = 'Успешно добавлена';
                    $parser->statusId = 1;
        
                    $parser->save();

                    echo "Успешно добавлена в таблицу Дел по лоту ID ".$newCase->id.", старый ID ".$case->id.". \n";
                    return true;

                } catch (\Throwable $th) {
        
                    $parser->message = $th->getMessage();
                    $parser->statusId = 3;
        
                    $parser->save();

                    ErrorSend::parser($parser->id);
        
                    echo "Ошибка при добавлении в таблицу Дел по лоту ID ".$case->id.". \n";
                    return false;
                }
        
            } else {
        
                $parser->message = 'Запись пуста';
                $parser->statusId = 2;
        
                $parser->save();

                echo "Пустые данные в таблице Дела по лоту ID ".$case->id.". \n";
                return 2;
            }
        }
        return false;
    }
}