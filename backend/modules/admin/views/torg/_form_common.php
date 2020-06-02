<?php

/* @var $this yii\web\View */
/* @var $model common\models\db\Torg */
/* @var $form yii\widgets\ActiveForm */

use yii\helpers\Html;
use common\components\Property;
use sergmoro1\lookup\models\Lookup;
?>

<div class='row'>
    <div class='col-sm-4'>
        <?= $form->field($model, 'msg_id')->textInput(['maxlength' => true]) ?>
    </div>
    <div class='col-sm-4'>
        <?= $form->field($model, 'property')->dropdownList(Lookup::items(Property::TORG_PROPERTY, true), [
            'prompt' => Yii::t('app', 'Select'),
        ]); ?>
    </div>
    <div class='col-sm-4'>
        <?= $form->field($model, 'offer')->dropdownList(Lookup::items(Property::TORG_OFFER, true), [
            'prompt' => Yii::t('app', 'Select'),
        ]); ?>
    </div>
</div>
<div class='row'>
    <div class='col-sm-12'>
        <?= $form->field($model, 'description')->textArea(['maxlength' => true]) ?>
    </div>
</div>
