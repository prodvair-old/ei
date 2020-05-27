<?php

/* @var $this yii\web\View */
/* @var $model common\models\db\Lot */
/* @var $form yii\widgets\ActiveForm */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use backend\modules\admin\assets\Select2Asset;
use common\components\Property;
use sergmoro1\lookup\models\Lookup;
use common\models\db\Category;

Select2Asset::register($this);

$script = <<<JS
$(document).ready(function() { $('#lot-new_categories').select2(); });
JS;
$this->registerJS($script);
?>

<?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'status')->dropdownList(Lookup::items(Property::LOT_STATUS, true)) ?>
    <?= $form->field($model, 'reason')->dropdownList(Lookup::items(Property::LOT_REASON, true)) ?>

    <?= $form->field($model, 'new_categories')->dropDownList(Category::items(), [
            'multiple'=>'multiple',
        ]             
    ); ?>

    <div class='form-group'>
        <?= Html::submitButton(Yii::t('app', 'Save'), [
            'class' => 'btn btn-success',
        ]) ?>
    </div>
    
<?php ActiveForm::end(); ?>
