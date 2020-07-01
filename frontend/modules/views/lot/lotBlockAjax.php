<?php

use frontend\components\NumberWords;
use frontend\modules\components\LotBlock;

/* @var $lots \common\models\db\Lot */

$priceClass = 'text-secondary';

if (count($lots) > 0) {
    foreach ($lots as $lot) {
        echo LotBlock::widget(['lot' => $lot, 'type' => 'long', 'url' => $url]);
    }
}
