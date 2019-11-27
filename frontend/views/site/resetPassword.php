<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ResetPasswordForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Восстановление пароля';
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="page-wrapper page-detail">
    <div class="container pt-50 mt-80">
        <div class="row justify-content-center">
            
            <h1><?= Html::encode($this->title) ?></h1>

            <div class="col-12 col-md-11 col-lg-10 col-xl-9">
                
                <div class="mt-80"></div>
                
                <div class="content-wrapper">
                    
                    <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <?= $form->field($model, 'password')->passwordInput(['autofocus' => true])->label('Новый пароль') ?>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <?= $form->field($model, 'confirm_password')->passwordInput()->label('Подтвердите пароль') ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
                        </div>

                    <?php ActiveForm::end(); ?>
                                        
                </div>
                
            </div>
        </div>
    </div>

</section>