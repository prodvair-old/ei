<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;

use console\models\etp\EtpBankrupt;

use common\models\Query\Bankrupt\Etp;

use common\models\Query\Lot\Parser;

/**
 * Etp controller 
 * Парсинг таблицы Торговой площадки
 */
class EtpController extends Controller
{
    // Торговые площадки Банкротного имущества
    // php yii etp/bankrupt
    public function actionBankrupt($limit = 100, $delay = 'y') 
    {
        echo 'Парсинг таблицы Торговой площадки (uds.tradeplace)';
        $count = Etp::find()->joinWith('parser')->where(['parser.id' => Null])->orWhere(['parser.checked' => true])->count();
        echo "\nКоличество записей осталось: $count. \n";
        
        $parserCount = 0;

        if ($count > 0) {
            $etps = Etp::find()->joinWith('parser')->where(['parser.id' => Null])->orWhere(['parser.checked' => true])->limit($limit)->orderBy('id ASC')->all();

            echo "Ограничения записей $limit. \n";

            if ($etps[0]) {
                echo "Данные взяты из быза. \n";

                foreach ($etps as $etp) {
                    $parsingEtp = EtpBankrupt::id($etp->id);

                    if ($parsingEtp && $parsingEtp !== 2) {
                        foreach ($etp->parser as $value) {
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