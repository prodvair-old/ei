<?php

/* @var $this yii\web\View */
/* @var $model common\models\db\Tariff */
/* @var $form yii\widgets\ActiveForm */

use yii\helpers\Html;

?>
<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'fee')->textInput(['type' => 'number']) ?>
<?= $form->field($model, 'description')->textArea(['maxlength' => true]) ?>

<div class='form-group'>
    <?= Html::submitButton(Yii::t('app', 'Save'), [
        'class' => 'btn btn-success',
    ]) ?>
</div>
