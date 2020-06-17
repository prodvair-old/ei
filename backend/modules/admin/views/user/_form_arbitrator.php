<?php

/* @var $this yii\web\View */
/* @var $model common\models\db\Torg */
/* @var $form yii\widgets\ActiveForm */

use yii\helpers\Html;
use common\models\db\Manager;
use backend\modules\admin\assets\Select2Asset;

Select2Asset::register($this);

$data = Manager::jsonItems([$model->id]);

$script = <<<JS
$(document).ready(function() { $('#user-manager_id').select2(
    {data: $data}
); });
JS;
$this->registerJS($script);
?>

<?= $form->field($model, 'manager_id')->dropdownList([], [
    'prompt' => Yii::t('app', 'Select'),
]); ?>
<div class='form-group'>
    <?= Html::submitButton(Yii::t('app', 'Save'), [
        'class' => 'btn btn-success',
    ]) ?>
</div>
