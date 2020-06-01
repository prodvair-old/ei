<?php

/* @var $this yii\web\View */
/* @var $model common\models\db\Lot */
/* @var $form yii\widgets\ActiveForm */

use yii\helpers\Html;

use backend\modules\admin\assets\Select2Asset;
use common\components\Property;
use sergmoro1\lookup\models\Lookup;
use common\models\db\Category;

Select2Asset::register($this);

$data = Category::jsonItems($model->new_categories);

$script = <<<JS
$(document).ready(function() { $('#lot-new_categories').select2(
    {data: $data}
); });
JS;
$this->registerJS($script);
?>

<?= $form->field($model, 'status')->dropdownList(Lookup::items(Property::LOT_STATUS, true)) ?>
<?= $form->field($model, 'reason')->dropdownList(Lookup::items(Property::LOT_REASON, true)) ?>

<?= $form->field($model, 'new_categories')->dropdownList([], ['multiple' => 'multiple']) ?>

<div class='form-group'>
    <?= Html::submitButton(Yii::t('app', 'Save'), [
        'class' => 'btn btn-success',
    ]) ?>
</div>
