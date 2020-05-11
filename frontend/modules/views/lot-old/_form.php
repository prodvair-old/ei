<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\modules\models\LotsOld */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="lots-old-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'torgId')->textInput() ?>

    <?= $form->field($model, 'msgId')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'lotNumber')->textInput() ?>

    <?= $form->field($model, 'createdAt')->textInput() ?>

    <?= $form->field($model, 'updatedAt')->textInput() ?>

    <?= $form->field($model, 'title')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'startPrice')->textInput() ?>

    <?= $form->field($model, 'step')->textInput() ?>

    <?= $form->field($model, 'stepType')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'stepTypeId')->textInput() ?>

    <?= $form->field($model, 'deposit')->textInput() ?>

    <?= $form->field($model, 'depositType')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'depositTypeId')->textInput() ?>

    <?= $form->field($model, 'status')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'info')->textInput() ?>

    <?= $form->field($model, 'images')->textInput() ?>

    <?= $form->field($model, 'published')->checkbox() ?>

    <?= $form->field($model, 'regionId')->textInput() ?>

    <?= $form->field($model, 'city')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'district')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'oldId')->textInput() ?>

    <?= $form->field($model, 'bankId')->textInput() ?>

    <?= $form->field($model, 'address')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'archive')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
