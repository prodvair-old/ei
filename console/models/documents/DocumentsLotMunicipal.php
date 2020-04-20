<?php
namespace console\models\documents;

use Yii;
use yii\base\Module;

use console\models\GetInfoFor;

use common\models\Query\Municipal\LotDocuments;

use console\models\lots\LotsMunicipal;

use common\models\ErrorSend;

use common\models\Query\Lot\Lots;
use common\models\Query\Lot\Documents;
use common\models\Query\Lot\Parser;

class DocumentsLotMunicipal extends Module
{
    public function id($id)
    {
        $doc = LotDocuments::findOne($id);
        $parser = new Parser();

        $parser->tableNameTo = 'eiLot.documents';
        $parser->tableNameFrom = 'bailiff.lotdocuments';
        $parser->tableIdFrom = $doc->ldocId;

        $chekDoc = Documents::find()->where(['oldId' => $doc->ldocId, 'tableTypeId' => 1])->all();

        if (!empty($chekDoc[0])) {
            $parser->message = 'Был добавлен';
            $parser->statusId = 1;

            $parser->save();

            echo "Данный Документ уже был добавлен ID ".$doc->ldocId.". \n";

            return true;
        } else {
            if ($doc->ldocType != null && $doc->ldocCreated != null && $doc->ldocUrl) {

                // Лот
                if (!$lot = Lots::find()->alias('lot')->joinWith(['torg'])->where(['lot.oldId' => $doc->lot->lotId, 'torg.typeId' => 1])->one()) {
                    echo "Лот для связи отцуствует! \nПробуем спарсить данный Лота. \n";

                    $parsingLot = LotsMunicipal::id($doc->lot->lotId, false);

                    if (!$parsingLot && $parsingLot !== 2){
                        
                        $parser->message = 'Лот для связи отсуствует!';
                        $parser->messageJson = [
                            'oldLotId' => $doc->lot->lotId,
                        ];
                        $parser->statusId = 3;
            
                        $parser->save();

                        ErrorSend::parser($parser->id);
            
                        echo "Ошибка при добавлении в таблицу Документов ID ".$doc->ldocId.". \nОтсутствует Лот...\n";
                        return false;
                    } else {
                        $lot = Lots::find()->alias('lot')->joinWith(['torg'])->where(['lot.oldId' => $doc->lot->lotId, 'torg.typeId' => 1])->one();
                    }
                }

                $title = GetInfoFor::mb_ucfirst((($doc->ldocDescription)? $doc->ldocDescription : $doc->ldocType));

                $newDocument = new Documents();
        
                $newDocument->tableId       = $lot->id;
                $newDocument->msgId         = $doc->ldocBidNumber;
                $newDocument->name          = $title;
                $newDocument->url           = $doc->ldocUrl;
                $newDocument->info          = [
                    'created' => $doc->ldocCreated
                ];
                $newDocument->tableTypeId   = 1;
                $newDocument->oldId         = $doc->ldocId;
        
                try {
                    $newDocument->save();
        
                    $parser->message = 'Успешно добавлена';
                    $parser->statusId = 1;
        
                    $parser->save();

                    echo "Успешно добавлена в таблицу Документов ID ".$newDocument->id.", старый ID ".$doc->ldocId.". \n";
                    return true;

                } catch (\Throwable $th) {
        
                    $parser->message = $th->getMessage();
                    $parser->statusId = 3;
        
                    $parser->save();

                    ErrorSend::parser($parser->id);
        
                    echo "Ошибка при добавлении в таблицу Документов ID ".$doc->ldocId.". \n";
                    return false;
                }
        
            } else {
        
                $parser->message = 'Запись пуста';
                $parser->statusId = 2;
        
                $parser->save();

                echo "Пустые данные в таблице Документов ID ".$doc->ldocId.". \n";
                return 2;
            }
        }
        return false;
    }
}