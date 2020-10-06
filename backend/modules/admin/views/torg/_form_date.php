<?php

/* @var $this yii\web\View */
/* @var $model common\models\db\Torg */
/* @var $form yii\widgets\ActiveForm */

use yii\helpers\Html;
use yii\jui\DatePicker;

?>
<div class="row">
    <div class="col-sm-3">
        <?= $form->field($model, 'started_at')->widget(DatePicker::classname(), [
            'dateFormat' => 'dd.MM.yyyy',
            'options' => [
                'class' => 'form-control',
                'placeholder' => $model->attributeLabels()['started_at'],
                'autocomplete'=>'off',
            ],
        ]) ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'end_at')->widget(DatePicker::classname(), [
            'dateFormat' => 'dd.MM.yyyy',
            'options' => [
                'class' => 'form-control',
                'placeholder' => $model->attributeLabels()['end_at'],
                'autocomplete'=>'off',
            ],
        ]) ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'published_at')->widget(DatePicker::classname(), [
            'dateFormat' => 'dd.MM.yyyy',
            'options' => [
                'class' => 'form-control',
                'placeholder' => $model->attributeLabels()['published_at'],
                'autocomplete'=>'off',
            ],
        ]) ?>
    </div>
    <div class="col-sm-3">
        <?= $form->field($model, 'completed_at')->widget(DatePicker::classname(), [
            'dateFormat' => 'dd.MM.yyyy',
            'options' => [
                'class' => 'form-control',
                'placeholder' => $model->attributeLabels()['completed_at'],
                'autocomplete'=>'off',
            ],
        ]) ?>
    </div>
</div>

<div class='form-group'>
    <?= Html::submitButton(Yii::t('app', 'Save'), [
        'class' => 'btn btn-success',
    ]) ?>
</div>
