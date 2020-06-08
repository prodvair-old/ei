<?php

/* @var $this yii\web\View */
/* @var $model common\models\db\Torg */
/* @var $form yii\widgets\ActiveForm */

use yii\helpers\Html;
use common\components\Property;
use sergmoro1\lookup\models\Lookup;
?>

<?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'status')->dropdownList(Lookup::items(Property::USER_STATUS, true), [
    'prompt' => Yii::t('app', 'Select'),
]); ?>
<?= $form->field($model, 'role')->dropdownList(Lookup::items(Property::USER_ROLE, true), [
    'prompt' => Yii::t('app', 'Select'),
]); ?>
<div class='form-group'>
    <?= Html::submitButton(Yii::t('app', 'Save'), [
        'class' => 'btn btn-success',
    ]) ?>
</div>
