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
        <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'middle_name')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'gender')->dropdownList(Lookup::items(Property::GENDER, true), [
            'prompt' => Yii::t('app', 'Select'),
        ]); ?>
        <?= $form->field($model, 'birthday')->widget(DatePicker::classname(), [
            'dateFormat' => 'dd.MM.yyyy',
            'options' => [
                'class' => 'form-control',
                'placeholder' => $model->attributeLabels()['birthday'],
            ],
        ]) ?>
    </div>
    <div class='col-sm-6'>
        <?= $form->field($model, 'inn')->textInput() ?>
        <?= $form->field($model, 'activity')->dropdownList(Lookup::items(Property::PERSON_ACTIVITY, true), [
            'prompt' => Yii::t('app', 'Select'),
        ]); ?>
        <?= $form->field($model, 'phone')->widget(\yii\widgets\MaskedInput::className(), [
            'mask' => '+7 999-999-99-99',
        ]) ?>
    </div>
</div>
<div class='form-group'>
    <?= Html::submitButton(Yii::t('app', 'Save'), [
        'class' => 'btn btn-success',
    ]) ?>
</div>
