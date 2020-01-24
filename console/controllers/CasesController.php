<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;

use console\models\cases\CasesBankrupt;

use common\models\Query\Bankrupt\Cases;

use common\models\Query\Lot\Parser;

/**
 * Cases controller 
 * Парсинг таблицы Дел по лоту
 */
class CasesController extends Controller
{
    // Дела по лоту Банкротного имущества
    // php yii cases/bankrupt
    public function actionBankrupt($limit = 100, $delay = 'y') 
    {
        echo 'Парсинг таблицы дел по лоту (uds.obj$cases)';
        $count = Cases::find()->joinWith('parser')->where(['parser.id' => Null])->orWhere(['parser.checked' => true])->count();
        echo "\nКоличество записей осталось: $count. \n";
        
        $parserCount = 0;

        if ($count > 0) {
            $cases = Cases::find()->joinWith('parser')->where(['parser.id' => Null])->orWhere(['parser.checked' => true])->limit($limit)->orderBy('id ASC')->all();

            echo "Ограничения записей $limit. \n";

            if ($cases[0]) {
                echo "Данные взяты из быза. \n";

                foreach ($cases as $case) {
                    $parsingCases = CasesBankrupt::id($case->id);

                    if ($parsingCases && $parsingCases !== 2) {
                        foreach ($case->parser as $value) {
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