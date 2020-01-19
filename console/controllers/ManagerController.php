<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;

use console\models\managers\ArbitrsBankrupt;

use common\models\Query\Bankrupt\Arbitrs;

use common\models\Query\Lot\Parser;

/**
 * Manager controller 
 * Парсинг таблицы Менеджеров
 */
class ManagerController extends Controller
{
    // Арбитражный Управляющий (Менеджер) Банкротного имущества
    // php yii manager/bankrupt
    public function actionBankrupt($limit = 100) 
    {
        echo 'Парсинг таблицы Арбитражных Управляющих (Менеджеров) (uds.obj$arbitrs)';
        $count = Arbitrs::find()->joinWith('parser')->where(['parser.id' => Null])->orWhere(['parser.checked' => true])->count();
        echo "\nКоличество записей осталось: $count. \n";
        
        $parserCount = 0;

        if ($count > 0) {
            $arbitrs = Arbitrs::find()->joinWith('parser')->where(['parser.id' => Null])->orWhere(['parser.checked' => true])->limit($limit)->orderBy('id ASC')->all();

            echo "Ограничения записей $limit. \n";

            if ($arbitrs[0]) {
                echo "Данные взяты из быза. \n";

                foreach ($arbitrs as $arbitr) {
                    $parsingArbitr = ArbitrsBankrupt::id($arbitr->id);

                    if ($parsingArbitr && $parsingSro != 2) {
                        foreach ($arbitr->parser as $value) {
                            if ($value->checked) {
                                $parser = Parser::findOne($value->id);

                                $parser->checked = false;

                                $parser->update();
                            }
                        }

                        $parserCount++;
                    }

                    $sleep = rand(1, 3);

                    echo "Задержка $sleep секунды. \n";

                    sleep($sleep);
                }
            }

            echo "Загружено $parserCount записей. \n";
        } else {
            echo "Новых данных нет. \n";
        }
        echo "Завершение парсинга. \n";
    }
}  