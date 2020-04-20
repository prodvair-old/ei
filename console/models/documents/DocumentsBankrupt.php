<?php
namespace console\models\documents;

use Yii;
use yii\base\Module;

use console\models\GetInfoFor;

use common\models\Query\Bankrupt\Files;

use console\models\cases\CasesBankrupt;

use common\models\ErrorSend;

use common\models\Query\Lot\Cases;
use common\models\Query\Lot\Documents;
use common\models\Query\Lot\Parser;

class DocumentsBankrupt extends Module
{
    public function id($id)
    {
        $doc = Files::findOne($id);
        $parser = new Parser();

        $parser->tableNameTo = 'eiLot.documents';
        $parser->tableNameFrom = 'uds.obj$casefiles';
        $parser->tableIdFrom = $doc->id;

        $chekDoc = Documents::find()->where(['oldId' => $doc->id, 'tableTypeId' => 3])->all();

        if (!empty($chekDoc[0])) {
            $parser->message = 'Был добавлен';
            $parser->statusId = 1;

            $parser->save();

            echo "Данный Документ уже был добавлен ID ".$doc->id.". \n";

            return true;
        } else {
            if ($doc->filename != null && $doc->fileurl != null && $doc->caseid) {

                // Дело по должнику
                if (!$case = Cases::find()->where(['oldId' => $doc->caseid])->one()) {
                    echo "Дело для связи отцуствует! \nПробуем спарсить данный Дел по должнику. \n";

                    $parsingCase = CasesBankrupt::id($doc->caseid);

                    if (!$parsingCase && $parsingCase !== 2){
                        
                        $parser->message = 'Дело по должнику для связи отсуствует!';
                        $parser->messageJson = [
                            'oldCaseId' => $doc->caseid,
                        ];
                        $parser->statusId = 3;
            
                        $parser->save();

                        ErrorSend::parser($parser->id);
            
                        echo "Ошибка при добавлении в таблицу Документов ID ".$torg->id.". \nОтсутствует Дело по должнику...\n";
                        return false;
                    } else {
                        $case = Cases::find()->where(['oldId' => $doc->caseid])->one();
                    }
                }

                $newDocument = new Documents();
        
                $newDocument->tableId       = $case->id;
                $newDocument->name          = $doc->filename;
                $newDocument->url           = $doc->fileurl;
                $newDocument->hash          = $doc->filehash;
                $newDocument->format        = GetInfoFor::format($doc->filename);
                $newDocument->tableTypeId   = 3;
                $newDocument->oldId         = $doc->id;
        
                try {
                    $newDocument->save();
        
                    $parser->message = 'Успешно добавлена';
                    $parser->statusId = 1;
        
                    $parser->save();

                    echo "Успешно добавлена в таблицу Документов ID ".$newDocument->id.", старый ID ".$doc->id.". \n";
                    return true;

                } catch (\Throwable $th) {
        
                    $parser->message = $th->getMessage();
                    $parser->statusId = 3;
        
                    $parser->save();

                    ErrorSend::parser($parser->id);
        
                    echo "Ошибка при добавлении в таблицу Документов ID ".$doc->id.". \n";
                    return false;
                }
        
            } else {
        
                $parser->message = 'Запись пуста';
                $parser->statusId = 2;
        
                $parser->save();

                echo "Пустые данные в таблице Документов ID ".$doc->id.". \n";
                return 2;
            }
        }
        return false;
    }
}