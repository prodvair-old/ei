<?php

/* @var $this yii\web\View */
/* @var $model common\models\db\TorgPledge */
/* @var $form yii\widgets\ActiveForm */

use yii\helpers\Url;
use yii\helpers\Html;
use backend\modules\admin\assets\Select2Asset;
use common\models\db\User;
use common\models\db\Owner;

Select2Asset::register($this);

$url = Url::to(['user/fillin']);

$script = <<<JS
$(document).ready(function() { $('#torgpledge-user_id').select2({
    ajax: {
        url: '$url',
        dataType: 'json',
        data: function (params) {
          var query = {
            search: params.term,
            type: 'public'
          }
          return query;
        }
    }
}); });
JS;
$this->registerJS($script);
?>

<?= $form->field($model, 'owner_id')->dropdownList(Owner::items(), ['prompt' => Yii::t('app', 'Select')]) ?>

<?= $form->field($model, 'user_id')->dropdownList($model->isNewRecord ? [] : [$model->user_id => User::findOne($model->user_id)->fullName]) ?>

<?= $model->isNewRecord ? $form->field($model, 'add_lot')->checkBox() : '' ?>

<div class='form-group'>
    <?= Html::submitButton(Yii::t('app', 'Save'), [
        'class' => 'btn btn-success',
    ]) ?>
</div>
