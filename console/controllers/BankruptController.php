<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;

use console\models\bankrupt\BankruptsBankrupt;

use common\models\Query\Bankrupt\Bankrupts;

use common\models\Query\Lot\Parser;

/**
 * Bankrupt controller 
 * Парсинг таблицы Должников
 */
class BankruptController extends Controller
{
    // Должники Банкротного имущества
    // php yii bankrupt/bankrupt
    public function actionBankrupt($limit = 100) 
    {
        echo 'Парсинг таблицы должника (uds.obj$bankrupts)';
        $count = Bankrupts::find()->joinWith('parser')->where(['parser.id' => Null])->orWhere(['parser.checked' => true])->count();
        echo "\nКоличество записей осталось: $count. \n";
        
        $parserCount = 0;

        if ($count > 0) {
            $bankrupts = Bankrupts::find()->joinWith('parser')->where(['parser.id' => Null])->orWhere(['parser.checked' => true])->limit($limit)->orderBy('id ASC')->all();

            echo "Ограничения записей $limit. \n";

            if ($bankrupts[0]) {
                echo "Данные взяты из быза. \n";

                foreach ($bankrupts as $bankrupt) {
                    $parsingBankrupt = BankruptsBankrupt::id($bankrupt->id);

                    if ($parsingBankrupt && $parsingSro != 2) {
                        foreach ($bankrupt->parser as $value) {
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