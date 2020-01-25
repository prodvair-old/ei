<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;

use console\models\documents\DocumentsBankrupt;

use common\models\Query\Bankrupt\Files;

use common\models\Query\Lot\Parser;

/**
 * Documents controller 
 * Парсинг таблицы Документов
 */
class DocumentsController extends Controller
{
    // Документы Банкротного имущества
    // php yii documents/bankrupt
    public function actionBankrupt($limit = 100, $delay = 'y', $sort = 'new') 
    {
        echo 'Парсинг таблицы Докуметов (uds.obj$casefiles)';
        $count = Files::find()->joinWith('parser')->where(['parser.id' => Null])->orWhere(['parser.checked' => true])->count();
        echo "\nКоличество записей осталось: $count. \n";
        
        $parserCount = 0;

        if ($count > 0) {
            $docs = Files::find()->joinWith('parser')->where(['parser.id' => Null])->orWhere(['parser.checked' => true])->limit($limit)->orderBy('id '.(($sort = 'new')? 'DESC' : 'ASC'))->all();

            echo "Ограничения записей $limit. \n";

            if ($docs[0]) {
                echo "Данные взяты из быза. \n";

                foreach ($docs as $doc) {
                    $parsingDocument = DocumentsBankrupt::id($doc->id);

                    if ($parsingDocument && $parsingDocument !== 2) {
                        foreach ($doc->parser as $value) {
                            if ($value->checked) {
                                $parser = Parser::findOne($value->id);

                                $parser->checked = false;

                                $parser->update();
                            }
                        }

                        $parserCount++;
                    }

                    if ($delay == 'y') {
                        $sleep = rand(1, 3);

                        echo "Задержка $sleep секунды. \n";

                        sleep($sleep);
                    }
                    
                }
            }

            echo "Загружено $parserCount записей. \n";
        } else {
            echo "Новых данных нет. \n";
        }
        echo "Завершение парсинга. \n";
    }
}  