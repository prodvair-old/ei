<?php

/* @var $this yii\web\View */
/* @var $model common\models\db\Lot */
/* @var $form yii\widgets\ActiveForm */

use yii\helpers\Url;
use yii\helpers\Html;

use vova07\imperavi\Widget;
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
        <?= $form->field($model, 'deposit_measure')->dropdownList(Lookup::items(Property::TORG_OFFER, true), [
            'prompt' => Yii::t('app', 'Select'),
        ]); ?>
    </div>
</div>
<div class='row'>
    <div class='col-sm-12'>
        <?= $form->field($model, 'description')->textArea(['maxlength' => true]) ?>
    </div>
</div>

<div class='form-group'>
    <?= Html::submitButton(Yii::t('app', 'Save'), [
        'class' => 'btn btn-success',
    ]) ?>
</div>
