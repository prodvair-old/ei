<?php

/* @var $this yii\web\View */
/* @var $model common\models\db\Lot */
/* @var $form yii\widgets\ActiveForm */

use yii\helpers\Url;
use yii\helpers\Html;

use common\models\db\District;
use common\models\db\Region;

?>

<div class='row'>
    <div class='col-sm-4'>
        <?= $form->field($model, 'district_id')->dropdownList(District::items(), [
            'prompt' => Yii::t('app', 'Select'),
        ]) ?>
    </div>
    <div class='col-sm-4'>
        <?= $form->field($model, 'region_id')->dropdownList(Region::items(), [
            'prompt' => Yii::t('app', 'Select'),
        ]) ?>
    </div>
    <div class='col-sm-4'>
        <?= $form->field($model, 'city')->textInput(['maxlength' => true]); ?>
    </div>
</div>

<div class='row'>
    <div class='col-sm-12'>
        <?= $form->field($model, 'address')->textArea(['maxlength' => true]) ?>
    </div>
</div>

<div class='row'>
    <div class='col-sm-4 col-md-offset-4'>
        <?= $form->field($model, 'geo_lat')->textInput() ?>
    </div>
    <div class='col-sm-4'>
        <?= $form->field($model, 'geo_lon')->textInput() ?>
    </div>
</div>

<div class='form-group'>
    <?= Html::submitButton(Yii::t('app', 'Save'), [
        'class' => 'btn btn-success',
    ]) ?>
</div>
