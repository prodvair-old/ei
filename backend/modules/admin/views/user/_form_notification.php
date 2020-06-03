<?php

/* @var $this yii\web\View */
/* @var $model common\models\db\Notification */
/* @var $form yii\widgets\ActiveForm */

use yii\helpers\Html;
use yii\jui\DatePicker;

?>
<?= $form->field($model, 'new_picture')->checkbox() ?>
<?= $form->field($model, 'new_report')->checkbox() ?>
<?= $form->field($model, 'price_reduction')->checkbox() ?>

<div class='form-group'>
    <?= Html::submitButton(Yii::t('app', 'Save'), [
        'class' => 'btn btn-success',
    ]) ?>
</div>
