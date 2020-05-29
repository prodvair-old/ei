<?php
/**
 * Расширенный вариант поиска.
 * Пока в резерве.
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use common\components\Property;
use common\models\db\Category;
use sergmoro1\lookup\models\Lookup;

/* @var $this yii\web\View */
/* @var $model app\models\PostSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="post-search">
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'status')->dropDownList(Lookup::items(Property::LOT_STATUS, true), ['prompt' => '']) ?>

    <?= $form->field($model, 'reason')->dropDownList(Lookup::items(Property::LOT_REASON, true), ['prompt' => '']) ?>

    <?= $form->field($model, 'property')->dropDownList(Lookup::items(Property::TORG_PROPERTY, true), ['prompt' => '']) ?>

    <?= $form->field($model, 'category_id')->dropDownList(Category::items()) ?>

    <div class="form-group">
        <?= Html::submitButton('Искать', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Сбросить', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
