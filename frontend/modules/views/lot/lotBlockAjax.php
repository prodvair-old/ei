<?php

use frontend\components\NumberWords;
use frontend\modules\components\LotBlockSmall;

/* @var $lots \common\models\db\Lot */

$priceClass = 'text-secondary';

if (count($lots) > 0) {
    foreach ($lots as $lot) {
        echo LotBlockSmall::widget(['lot' => $lot, 'long' => true, 'url' => $url]);
    }
}
