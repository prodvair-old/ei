<?php

/* @var $this yii\web\View */
/* @var $torg common\models\db\Lot */

use yii\helpers\Html;
use common\components\Property;
use sergmoro1\lookup\models\Lookup;

?>
<div class='row'>
    <div class='col-sm-2' title='<?= $lot->torg->getAttributeLabel('msg_id') ?>'>
        <?= Html::a($lot->torg->msg_id, ['torg/view', 'id' => $lot->torg->id], ['title' => Yii::t('app', 'Torg')]) ?><br>
        <?= Html::a($lot->id, ['lot/view', 'id' => $lot->id], ['title' => Yii::t('app', 'Lot')]) ?><br>
        <?= Lookup::item(Property::TORG_PROPERTY, $lot->torg->property, true) ?>
    </div>
    <div class='col-sm-7' title='<?= $lot->getAttributeLabel('title') ?>'>
        <?= $lot->title ?>
    </div>
    <div class='col-sm-3' title='<?= $lot->getAttributeLabel('start_price') ?>'>
        <?= $lot->start_price ?>
    </div>
</div>
