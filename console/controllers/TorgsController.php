<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;

use console\models\torgs\TorgsBankrupt;
use console\models\torgs\TorgsArrest;

use common\models\Query\Bankrupt\Auction;
use common\models\Query\Arrest\Torgs;

use common\models\Query\Lot\Parser;

/**
 * Torgs controller 
 * Парсинг таблицы Торгов
 */
class TorgsController extends Controller
{
    // Торги Банкротного имущества
    // php yii torgs/bankrupt
    public function actionBankrupt($limit = 100, $delay = 'y') 
    {
        error_reporting(0);

        echo 'Парсинг таблицы Торгов (uds.obj$auctions)';
        $count = Auction::find()->joinWith('parser')->where(['parser.id' => Null])->orWhere(['parser.checked' => true])->count();
        echo "\nКоличество записей осталось: $count. \n";
        
        $parserCount = 0;

        if ($count > 0) {
            $torgs = Auction::find()->joinWith('parser')->where(['parser.id' => Null])->orWhere(['parser.checked' => true])->limit($limit)->orderBy('id DESC')->all();

            echo "Ограничения записей $limit. \n";

            if (!empty($torgs[0])) {
                echo "Данные взяты из быза. \n";

                foreach ($torgs as $torg) {
                    $parsingTorg = TorgsBankrupt::id($torg->id);

                    if ($parsingTorg && $parsingTorg !== 2) {
                        foreach ($torg->parser as $value) {
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

    // Торги Арестованного имущества
    // php yii torgs/arrest
    public function actionArrest($limit = 100, $delay = 'y') 
    {
        error_reporting(0);

        echo 'Парсинг таблицы Торгов (bailiff.torgs)';
        $count = Torgs::find()->joinWith('parser')->where(['parser.id' => Null])->orWhere(['parser.checked' => true])->count();
        echo "\nКоличество записей осталось: $count. \n";
        
        $parserCount = 0;

        if ($count > 0) {
            $torgs = Torgs::find()->joinWith('parser')->where(['parser.id' => Null])->orWhere(['parser.checked' => true])->limit($limit)->orderBy('id DESC')->all();

            echo "Ограничения записей $limit. \n";

            if (!empty($torgs[0])) {
                echo "Данные взяты из быза. \n";

                foreach ($torgs as $torg) {
                    $parsingTorg = TorgsArrest::id($torg->trgId);

                    if ($parsingTorg && $parsingTorg !== 2) {
                        foreach ($torg->parser as $value) {
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