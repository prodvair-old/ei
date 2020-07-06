<?php


/* @var $model Sro[] */

use common\models\db\Sro;
use frontend\modules\components\SroBlock;

if (count($model) > 0) {
    foreach ($model as $item) {
        echo SroBlock::widget(['sro' => $item]);
    }
}