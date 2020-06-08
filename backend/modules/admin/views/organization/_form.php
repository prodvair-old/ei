<?php

/* @var $this yii\web\View */
/* @var $model common\models\db\Torg */
/* @var $form yii\widgets\ActiveForm */

use yii\helpers\Html;
use common\components\Property;
use sergmoro1\lookup\models\Lookup;
use yii\jui\DatePicker;

?>
<div class='row'>
    <div class='col-sm-6'>
        <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'full_title')->textArea(['maxlength' => true]) ?>
        <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'phone')->widget(\yii\widgets\MaskedInput::className(), [
            'mask' => '+7 999-999-99-99',
        ]) ?>
        <?= $form->field($model, 'website')->textInput(['maxlength' => true]) ?>
    </div>
    <div class='col-sm-6'>
        <?= $form->field($model, 'inn')->textInput() ?>
        <?= $form->field($model, 'ogrn')->textInput() ?>
        <?= $form->field($model, 'activity')->dropdownList(Lookup::items(Property::ORGANIZATION_ACTIVITY, true), [
            'prompt' => Yii::t('app', 'Select'),
        ]); ?>
        <?= $form->field($model, 'status')->dropdownList(Lookup::items(Property::ORGANIZATION_STATUS, true)) ?>
        <?= $form->field($model, 'reg_number')->textInput(['maxlength' => true]) ?>
    </div>
</div>
<div class='form-group'>
    <?= Html::submitButton(Yii::t('app', 'Save'), [
        'class' => 'btn btn-success',
    ]) ?>
</div>
