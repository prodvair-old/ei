<?php
namespace console\models\lots;

use Yii;
use yii\base\Module;

use common\models\Query\Lot\Lots;

class LotsArcive extends Module
{
    public function checking($limit, $sendEmpty = true)
    {
        $count = 0;
        $lots = Lots::find()->joinWith(['torg'])->where("archive = 0 AND 
            (
                lower(status) LIKE '%окончен%'
                OR lower(status) LIKE '%несостоявшиеся%'
                OR lower(status) LIKE '%состоявшиеся%'
                OR lower(status) LIKE '%не состоялся%'
                OR lower(status) LIKE '%отменен/аннулирован%'
                OR lower(status) LIKE '%отменён организатором%'
                OR lower(status) LIKE '%торги завершены%'
                OR lower(status) LIKE '%торги отменены%'
                OR lower(status) LIKE '%торги не состоялись%'
                OR lower(status) LIKE '%торги по лоту отменены%'
                OR lower(status) LIKE '%торги по лоту не состоялись%'
                OR \"torg\".\"publishedDate\" IS NULL
                OR \"torg\".\"endDate\" <= NOW()
                OR \"torg\".\"completeDate\" <= NOW()		
            )")->limit($limit)->all();

        if ($lots[0]) {
            echo "Данные изьяты \n";

            foreach ($lots as $lot) {
                $lot->archive = 1;


                if ($lot->save()) {
                    echo "Добавлен в архив лот под номером: ".$lot->id." \n";
                } else {
                    echo "Добавлен в архив лот под номером: ".$lot->id." \n";
                }

                $count++;
            }

        } else {
            echo "Пустые данные \n";
        }

        return $count;
    }
}