<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;

use console\models\lotPriceHistorys\PriceHistorysBankrupt;

use common\models\Query\Bankrupt\Offerreductions;

use common\models\Query\Lot\Parser;

/**
 * Price historys controller 
 * Парсинг таблицы Истории снижения цены
 */
class PricehistorysController extends Controller
{
    // История снижения цены Банкротного имущества
    // php yii pricehistorys/bankrupt
    public function actionBankrupt($limit = 100, $delay = 'y', $sort = 'new') 
    {
        echo 'Парсинг таблицы Истории снижения цены (bailiff.offerreductions)';
        $count = Offerreductions::find()->joinWith('parser')->where(['parser.id' => Null])->orWhere(['parser.checked' => true])->count();
        echo "\nКоличество записей осталось: $count. \n";
        
        $parserCount = 0;

        if ($count > 0) {
            $priceHistorys = Offerreductions::find()->joinWith('parser')->where(['parser.id' => Null])->orWhere(['parser.checked' => true])->limit($limit)->orderBy('ofrRdnId '.(($sort = 'new')? 'DESC' : 'ASC'))->all();

            echo "Ограничения записей $limit. \n";

            if ($priceHistorys[0]) {
                echo "Данные взяты из быза. \n";

                foreach ($priceHistorys as $priceHistory) {
                    $parsingPriceHistory = PriceHistorysBankrupt::id($priceHistory->ofrRdnId);

                    if ($parsingPriceHistory && $parsingPriceHistory !== 2) {
                        foreach ($priceHistory->parser as $value) {
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