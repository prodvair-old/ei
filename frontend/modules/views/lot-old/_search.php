<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\modules\models\LotsOldSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="lots-old-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'torgId') ?>

    <?= $form->field($model, 'msgId') ?>

    <?= $form->field($model, 'lotNumber') ?>

    <?= $form->field($model, 'createdAt') ?>

    <?php // echo $form->field($model, 'updatedAt') ?>

    <?php // echo $form->field($model, 'title') ?>

    <?php // echo $form->field($model, 'description') ?>

    <?php // echo $form->field($model, 'startPrice') ?>

    <?php // echo $form->field($model, 'step') ?>

    <?php // echo $form->field($model, 'stepType') ?>

    <?php // echo $form->field($model, 'stepTypeId') ?>

    <?php // echo $form->field($model, 'deposit') ?>

    <?php // echo $form->field($model, 'depositType') ?>

    <?php // echo $form->field($model, 'depositTypeId') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'info') ?>

    <?php // echo $form->field($model, 'images') ?>

    <?php // echo $form->field($model, 'published')->checkbox() ?>

    <?php // echo $form->field($model, 'regionId') ?>

    <?php // echo $form->field($model, 'city') ?>

    <?php // echo $form->field($model, 'district') ?>

    <?php // echo $form->field($model, 'oldId') ?>

    <?php // echo $form->field($model, 'bankId') ?>

    <?php // echo $form->field($model, 'address') ?>

    <?php // echo $form->field($model, 'archive') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
