<?php
/* @var $this yii\web\View */
/* @var $model frontend\models\user\NotificationForm */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>

<div class="col-12 col-md-12 col-lg-8">
    <?php $form = ActiveForm::begin() ?>
        <div class="col-inner">
        
            <div class="row gap-20">
            
                <div class="col-sm-12">
                    <div class="form-group mb-0">
                        <?= $form->field($model, 'new_picture')->checkbox(['class' => 'form-control']) ?>
                    </div>
                    <div class="form-group mb-0">
                        <?= $form->field($model, 'new_report')->checkbox(['class' => 'form-control']) ?>
                    </div>
                    <div class="form-group mb-0">
                        <?= $form->field($model, 'price_reduction')->checkbox(['class' => 'form-control']) ?>
                    </div>
                </div>
            
            <div class="mb-30"></div>
            
            <div class="row gap-10 mt-15 justify-content-center justify-content-md-start">
                <div class="col-auto">
                    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
                </div>
                <div class="col-auto">
                    <a href="<?=Url::to(['/user/index'])?>" class="btn btn-secondary">Назад</a>
                </div>
            </div>
            
        </div>
    <?php ActiveForm::end() ?>
</div>
