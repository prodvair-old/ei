<?php

/* @var $this yii\web\View */
/* @var $model common\models\db\User */
/* @var $manager common\models\db\Manager */
/* @var $form yii\widgets\ActiveForm */

use yii\helpers\Html;
use common\components\Property;
use sergmoro1\lookup\models\Lookup;
use common\models\db\Manager;
use backend\modules\admin\assets\Select2Asset;

Select2Asset::register($this);

$data = Manager::jsonItems([$model->manager_id]);

$script = <<<JS
$(document).ready(function() { $('#user-manager_id').select2(
    {data: $data}
); });
JS;
$this->registerJS($script);?>

<?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'status')->dropdownList(Lookup::items(Property::USER_STATUS, true), [
    'prompt' => Yii::t('app', 'Select'),
]); ?>
<?= $form->field($model, 'role')->dropdownList(Lookup::items(Property::USER_ROLE, true), [
    'prompt' => Yii::t('app', 'Select'),
]); ?>

<?= $form->field($model, 'manager_id')->dropdownList([], [
    'prompt' => Yii::t('app', 'Select'),
]); ?>

<div class='form-group'>
    <?= Html::submitButton(Yii::t('app', 'Save'), [
        'class' => 'btn btn-success',
    ]) ?>
</div>
