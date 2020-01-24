<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;

use console\models\torgs\TorgsBankrupt;

use common\models\Query\Bankrupt\Auction;

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
        echo 'Парсинг таблицы Торгов (uds.obj$auctions)';
        $count = Auction::find()->joinWith('parser')->where(['parser.id' => Null])->orWhere(['parser.checked' => true])->count();
        echo "\nКоличество записей осталось: $count. \n";
        
        $parserCount = 0;

        if ($count > 0) {
            $torgs = Auction::find()->joinWith('parser')->where(['parser.id' => Null])->orWhere(['parser.checked' => true])->limit($limit)->orderBy('id DESC')->all();

            echo "Ограничения записей $limit. \n";

            if ($torgs[0]) {
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
}  