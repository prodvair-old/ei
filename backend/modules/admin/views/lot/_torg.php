<?php

/* @var $this yii\web\View */
/* @var $torg common\models\db\Torg */

use yii\helpers\Html;
use common\components\Property;
use sergmoro1\lookup\models\Lookup;

?>
<div class='row'>
    <div class='col-sm-3' title='<?= $torg->getAttributeLabel('msg_id') ?>'>
        <?= Html::a($torg->msg_id, ['/torg/update', 'id' => $torg->id]) ?>
    </div>
    <div class='col-sm-3' title='<?= $torg->getAttributeLabel('property') ?>'>
        <?= Lookup::item(Property::TORG_PROPERTY, $torg->property, true) ?>
    </div>
    <div class='col-sm-3' title='<?= $torg->getAttributeLabel('offer') ?>'>
        <?= Lookup::item(Property::TORG_OFFER, $torg->offer, true) ?>
    </div>
    <div class='col-sm-3' title='<?= $torg->getAttributeLabel('started_at') ?>'>
        <?= date('d.m.Y', $torg->started_at) ?>
    </div>
</div>
