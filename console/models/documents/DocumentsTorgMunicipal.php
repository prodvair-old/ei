<?php
namespace console\models\documents;

use Yii;
use yii\base\Module;

use console\models\GetInfoFor;

use console\models\torgs\TorgsMunicipal;

use common\models\ErrorSend;

use common\models\Query\Lot\Torgs;
use common\models\Query\Lot\Documents;
use common\models\Query\Lot\Parser;

class DocumentsTorgMunicipal extends Module
{
    public function id($id)
    {
        $doc = \common\models\Query\Arrest\Documents::findOne($id);
        $parser = new Parser();

        $parser->tableNameTo = 'eiLot.documents';
        $parser->tableNameFrom = 'bailiff.documents';
        $parser->tableIdFrom = $doc->tdocId;

        $chekDoc = Documents::find()->where(['oldId' => $doc->tdocId, 'tableTypeId' => 2])->all();

        if (!empty($chekDoc[0])) {
            $parser->message = 'Был добавлен';
            $parser->statusId = 1;

            $parser->save();

            echo "Данный Документ уже был добавлен ID ".$doc->tdocId.". \n";

            return true;
        } else {
            if ($doc->tdocType != null && $doc->tdocUrl) {

                // Торг
                if (!$torg = Torgs::find()->where(['oldId' => $doc->torg->trgId, 'typeId' => 4])->one()) {
                    echo "Торг для связи отцуствует! \nПробуем спарсить данный Торга. \n";

                    $parsingLot = TorgsMunicipal::id($doc->torg->trgId);

                    if (!$parsingLot && $parsingLot !== 2){
                        
                        $parser->message = 'Торг для связи отсуствует!';
                        $parser->messageJson = [
                            'oldTorgId' => $doc->torg->trgId,
                        ];
                        $parser->statusId = 3;
            
                        $parser->save();

                        ErrorSend::parser($parser->id);
            
                        echo "Ошибка при добавлении в таблицу Документов ID ".$doc->tdocId.". \nОтсутствует Торг...\n";
                        return false;
                    } else {
                        $torg = Torgs::find()->where(['oldId' => $doc->torg->trgId, 'typeId' => 4])->one();
                    }
                }

                $title = GetInfoFor::mb_ucfirst((($doc->tdocDescription)? $doc->tdocDescription : $doc->tdocType));

                $newDocument = new Documents();
        
                $newDocument->tableId       = $torg->id;
                $newDocument->name          = $title;
                $newDocument->url           = $doc->tdocUrl;
                $newDocument->info          = [
                    'created' => $doc->tdocCreated
                ];
                $newDocument->tableTypeId   = 2;
                $newDocument->oldId         = $doc->tdocId;
        
                try {
                    $newDocument->save();
        
                    $parser->message = 'Успешно добавлена';
                    $parser->statusId = 1;
        
                    $parser->save();

                    echo "Успешно добавлена в таблицу Документов ID ".$newDocument->id.", старый ID ".$doc->tdocId.". \n";
                    return true;

                } catch (\Throwable $th) {
        
                    $parser->message = $th->getMessage();
                    $parser->statusId = 3;
        
                    $parser->save();

                    ErrorSend::parser($parser->id);
        
                    echo "Ошибка при добавлении в таблицу Документов ID ".$doc->tdocId.". \n";
                    return false;
                }
        
            } else {
        
                $parser->message = 'Запись пуста';
                $parser->statusId = 2;
        
                $parser->save();

                echo "Пустые данные в таблице Документов ID ".$doc->tdocId.". \n";
                return 2;
            }
        }
        return false;
    }
}