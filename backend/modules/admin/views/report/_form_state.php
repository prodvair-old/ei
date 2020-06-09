<?php

/* @var $this yii\web\View */
/* @var $model common\models\db\Lot */
/* @var $form yii\widgets\ActiveForm */

use yii\helpers\Html;

use common\components\Property;
use sergmoro1\lookup\models\Lookup;

?>

<?= $form->field($model, 'cost')->textInput(['type' => 'number']) ?>

<?= $form->field($model, 'status')->dropdownList(Lookup::items(Property::REPORT_STATUS, true)) ?>

<div class='form-group'>
    <?= Html::submitButton(Yii::t('app', 'Save'), [
        'class' => 'btn btn-success',
    ]) ?>
</div>
