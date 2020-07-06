<?php

use common\models\db\Manager;
use frontend\modules\components\ArbitrBlock;

/* @var $model Manager[] */

if (count($model) > 0) {
    foreach ($model as $item) {
        echo ArbitrBlock::widget(['arbitr' => $item]);
    }
}