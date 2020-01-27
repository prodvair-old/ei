<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;

use console\models\sro\SroBankrupt;

use common\models\Query\Bankrupt\Sro;

/**
 * Sro controller 
 * Парсинг таблицы СРО
 */
class SroController extends Controller
{
    // СРО Банкротного имущества
    // php yii sro/bankrupt
    public function actionBankrupt($limit = 100, $delay = 'y') 
    {
        error_reporting(0);

        echo 'Парсинг таблицы СРО (uds.obj$sro)';
        $count = Sro::find()->joinWith('parser')->where(['parser.id' => Null])->orWhere(['parser.checked' => true])->count();
        echo "\nКоличество записей осталось: $count. \n";
        
        $parserCount = 0;

        if ($count > 0) {
            $sros = Sro::find()->joinWith('parser')->where(['parser.id' => Null])->orWhere(['parser.checked' => true])->limit($limit)->orderBy('id ASC')->all();

            echo "Ограничения записей $limit. \n";

            if (!empty($sros[0])) {
                echo "Данные взяты из быза. \n";

                foreach ($sros as $sro) {
                    $parsingSro = SroBankrupt::id($sro->id);

                    if ($parsingSro && $parsingSro !== 2) {
                        foreach ($sro->parser as $value) {
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