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

<?php
if ($model->torg->property == 1) {
    $lotTypeUrl = 'bankrupt';
} else if ($model->torg->property == 2) {
    $lotTypeUrl = 'arrest';
} else if ($model->torg->property == 3) {
    $lotTypeUrl = 'zalog';
} else if ($model->torg->property == 4) {
    $lotTypeUrl = 'municipal';
}
?>

<a href="<?= Yii::$app->params['frontLink'] . '/' . $lotTypeUrl . '/'
    .((empty( $lot->categories[0]->slug))? 'lot-list' :  $lot->categories[0]->slug )
    . '/' . $model->id ?>"
   target="_blank">
    Лот на сайте
</a>
