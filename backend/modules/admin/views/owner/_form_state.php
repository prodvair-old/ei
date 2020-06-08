<?php

/* @var $this yii\web\View */
/* @var $model common\models\db\Lot */
/* @var $form yii\widgets\ActiveForm */

use yii\helpers\Html;

use common\components\Property;
use sergmoro1\lookup\models\Lookup;

?>

<?= $form->field($model, 'status')->dropdownList(Lookup::items(Property::ORGANIZATION_STATUS, true)) ?>
<?= $form->field($model, 'reg_number')->textInput(['maxlength' => true]) ?>

<div class='form-group'>
    <?= Html::submitButton(Yii::t('app', 'Save'), [
        'class' => 'btn btn-success',
    ]) ?>
</div>
