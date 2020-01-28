<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;

use console\models\lots\LotsBankrupt;

use common\models\Query\Bankrupt\Lots;
use common\models\Query\Arrest\LotsArrest;

use common\models\Query\LotsCategory;

use common\models\Query\Lot\Parser;
use common\models\Query\Lot\LotCategorys;

/**
 * Lots controller 
 * Парсинг таблицы Лотов
 */
class LotsController extends Controller
{
    // Лоты Банкротного имущества
    // php yii lots/bankrupt
    public function actionBankrupt($limit = 100, $delay = 'y', $sort = 'new') 
    {
        error_reporting(0);
        
        echo 'Парсинг таблицы Лотов (uds.obj$lots)';
        $count = Lots::find()->joinWith('parser')->where(['parser.id' => Null])->orWhere(['parser.checked' => true])->count();
        echo "\nКоличество записей осталось: $count. \n";
        
        $parserCount = 0;

        if ($count > 0) {
            $lots = Lots::find()->joinWith('parser')->where(['parser.id' => Null])->orWhere(['parser.checked' => true])->limit($limit)->orderBy('id '.(($sort = 'new')? 'DESC' : 'ASC'))->all();

            echo "Ограничения записей $limit. \n";

            if (!empty($lots[0])) {
                echo "Данные взяты из быза. \n";

                foreach ($lots as $lot) {
                    $parsingLot = LotsBankrupt::id($lot->id);

                    if ($parsingLot && $parsingLot !== 2) {
                        foreach ($lot->parser as $value) {
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

    // Категории лота Банкротного имущества
    // php yii lots/bankrupt-сategory
    public function actionBankruptcategory($lotId, $oldLotId)
    {
        echo 'Парсинг категория для Лота ID '.$lotId;
        $newLot = \common\models\Query\Lot\Lots::findOne([
            'id' => $lotId
        ]);
        $lot = Lots::findOne($oldLotId);

        if ($lot) {
            echo "\nДанные изьяты из быза \n";

            $lotsCategory = LotsCategory::find()->all();
                            
            foreach ($lotsCategory as $lotCategory) {
                foreach ($lotCategory->bankrupt_categorys as $categoryId => $subcategory) {
                    foreach ($lot->lotCategorys as $oldCategoryId => $oldCategory) {

                        if ($oldCategoryId == $categoryId) {
                            $newCategory = new LotCategorys();

                            $newCategory->lotId         = $newLot->id;
                            $newCategory->categoryId    = $categoryId;
                            $newCategory->name          = $subcategory['name'];
                            $newCategory->nameTranslit  = $subcategory['translit'];

                            try {
                                $newCategory->save();

                                $newLot->published = true;

                                $newLot->update();

                                echo "Успешно добавлено \n";
                            } catch (\Throwable $th) {

                                $parser->message = $th->getMessage();
                                $parser->messageJson = [
                                    'lotId'         => $newLot->id,
                                    'oldLotId'      => $lot->id,
                                    'oldCategoryId' => $oldCategoryId,
                                ];
                                $parser->statusId = 3;
                    
                                $parser->save();
                                
                                ErrorSend::parser($parser->id);
                    
                                echo "Ошибка при добавлении в таблицу Лотов ID ".$newLot->id.". \nОшибка добавления категория ID ".$oldCategoryId."\n";
                                return false;
                            }
                        }

                    }
                }
                
            }
        }

        echo "Завершение парсинга \n";
    }

    // Лоты Арестованного имущества
    // php yii lots/arrest
    public function actionArrest($limit = 100, $delay = 'y', $sort = 'new') 
    {
        error_reporting(0);
        
        echo 'Парсинг таблицы Лотов (bailiff.lots)';
        $count = LotsArrest::find()->joinWith('parser')->where(['parser.id' => Null])->orWhere(['parser.checked' => true])->count();
        echo "\nКоличество записей осталось: $count. \n";
        
        $parserCount = 0;

        if ($count > 0) {
            $lots = LotsArrest::find()->joinWith('parser')->where(['parser.id' => Null])->orWhere(['parser.checked' => true])->limit($limit)->orderBy('lotId '.(($sort = 'new')? 'DESC' : 'ASC'))->all();

            echo "Ограничения записей $limit. \n";

            if (!empty($lots[0])) {
                echo "Данные взяты из быза. \n";

                foreach ($lots as $lot) {
                    $parsingLot = \console\models\lots\LotsArrest::id($lot->lotId);

                    if ($parsingLot && $parsingLot !== 2) {
                        foreach ($lot->parser as $value) {
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