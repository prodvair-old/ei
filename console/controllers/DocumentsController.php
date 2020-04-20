<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;

use console\models\documents\DocumentsBankrupt;
use console\models\documents\DocumentsLotArrest;
use console\models\documents\DocumentsTorgArrest;
use console\models\documents\DocumentsLotMunicipal;
use console\models\documents\DocumentsTorgMunicipal;

use common\models\Query\Bankrupt\Files;
use common\models\Query\Arrest\Documents;
use common\models\Query\Arrest\LotDocuments;

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
        error_reporting(0);

        echo 'Парсинг таблицы Докуметов (uds.obj$casefiles)';
        $count = Files::find()->joinWith('parser')->where(['parser.id' => Null])->orWhere(['parser.checked' => true])->count();
        echo "\nКоличество записей осталось: $count. \n";
        
        $parserCount = 0;

        if ($count > 0) {
            $docs = Files::find()->joinWith('parser')->where(['parser.id' => Null])->orWhere(['parser.checked' => true])->limit($limit)->orderBy('id '.(($sort = 'new')? 'DESC' : 'ASC'))->all();

            echo "Ограничения записей $limit. \n";

            if (!empty($docs[0])) {
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

    // Документы лота Арестоваанного имущества
    // php yii documents/arrest_lot
    public function actionArrest_lot($limit = 100, $delay = 'y', $sort = 'new') 
    {
        error_reporting(0);

        echo 'Парсинг таблицы Докуметов (bailiff.lotdocuments)';
        $count = LotDocuments::find()->joinWith('parser')->where(['parser.id' => Null])->orWhere(['parser.checked' => true])->count();
        echo "\nКоличество записей осталось: $count. \n";
        
        $parserCount = 0;

        if ($count > 0) {
            $docs = LotDocuments::find()->joinWith('parser')->where(['parser.id' => Null])->orWhere(['parser.checked' => true])->limit($limit)->orderBy('ldocId '.(($sort = 'new')? 'DESC' : 'ASC'))->all();

            echo "Ограничения записей $limit. \n";

            if (!empty($docs[0])) {
                echo "Данные взяты из быза. \n";

                foreach ($docs as $doc) {
                    $parsingDocument = DocumentsLotArrest::id($doc->ldocId);

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

    // Документы торга Арестоваанного имущества
    // php yii documents/arrest_torg
    public function actionArrest_torg($limit = 100, $delay = 'y', $sort = 'new') 
    {
        error_reporting(0);

        echo 'Парсинг таблицы Докуметов (bailiff.documents)';
        $count = Documents::find()->joinWith('parser')->where(['parser.id' => Null])->orWhere(['parser.checked' => true])->count();
        echo "\nКоличество записей осталось: $count. \n";
        
        $parserCount = 0;

        if ($count > 0) {
            $docs = Documents::find()->joinWith('parser')->where(['parser.id' => Null])->orWhere(['parser.checked' => true])->limit($limit)->orderBy('tdocId '.(($sort = 'new')? 'DESC' : 'ASC'))->all();

            echo "Ограничения записей $limit. \n";

            if (!empty($docs[0])) {
                echo "Данные взяты из быза. \n";

                foreach ($docs as $doc) {
                    $parsingDocument = DocumentsTorgArrest::id($doc->tdocId);

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

    // Документы лота Муниципального имущества
    // php yii documents/municipal_lot
    public function actionMunicipal_lot($limit = 100, $delay = 'y', $sort = 'new') 
    {
        error_reporting(0);

        echo 'Парсинг таблицы Докуметов (bailiff.lotdocuments)';
        $count = \common\models\Query\Municipal\LotDocuments::find()->joinWith('parser')->where(['parser.id' => Null])->orWhere(['parser.checked' => true])->count();
        echo "\nКоличество записей осталось: $count. \n";
        
        $parserCount = 0;

        if ($count > 0) {
            $docs = \common\models\Query\Municipal\LotDocuments::find()->joinWith('parser')->where(['parser.id' => Null])->orWhere(['parser.checked' => true])->limit($limit)->orderBy('ldocId '.(($sort = 'new')? 'DESC' : 'ASC'))->all();

            echo "Ограничения записей $limit. \n";

            if (!empty($docs[0])) {
                echo "Данные взяты из быза. \n";

                foreach ($docs as $doc) {
                    $parsingDocument = DocumentsLotMunicipal::id($doc->ldocId);

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

    // Документы торга Муниципального имущества
    // php yii documents/municipal_torg
    public function actionMunicipal_torg($limit = 100, $delay = 'y', $sort = 'new') 
    {
        error_reporting(0);

        echo 'Парсинг таблицы Докуметов (bailiff.documents)';
        $count = \common\models\Query\Municipal\Documents::find()->joinWith('parser')->where(['parser.id' => Null])->orWhere(['parser.checked' => true])->count();
        echo "\nКоличество записей осталось: $count. \n";
        
        $parserCount = 0;

        if ($count > 0) {
            $docs = \common\models\Query\Municipal\Documents::find()->joinWith('parser')->where(['parser.id' => Null])->orWhere(['parser.checked' => true])->limit($limit)->orderBy('tdocId '.(($sort = 'new')? 'DESC' : 'ASC'))->all();

            echo "Ограничения записей $limit. \n";

            if (!empty($docs[0])) {
                echo "Данные взяты из быза. \n";

                foreach ($docs as $doc) {
                    $parsingDocument = DocumentsTorgMunicipal::id($doc->tdocId);

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