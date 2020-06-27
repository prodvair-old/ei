<?php


/* @var $model Bankrupt[] */

use common\models\db\Bankrupt;
use frontend\modules\components\BankruptBlock;

if (count($model) > 0) {
    foreach ($model as $item) {
        echo BankruptBlock::widget(['bankrupt' => $item]);
    }
}