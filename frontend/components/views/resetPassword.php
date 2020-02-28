<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\PasswordResetRequestForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

?>
<div role="tabpanel" class="tab-pane fade in" id="loginFormTabInModal-forgot-pass">
                    
    <div class="form-login">
    
        <div class="form-header">
            <h4>Забыли свой пароль?</h4>
            <p>Пожалуйста заполните форму.</p>
        </div>
        
        <div class="form-body">
            <?php $form = ActiveForm::begin(['action'=>'/request-password-reset', 'id' => 'password-reset-form']); ?>
                <p class="line-145">Для восстановления пароля введите Ваш e-mail. На почту придёт сообщение c ссылкой для восстановления.</p>
                <span class="password-reset-form-error tab-external-link block mt-25 text-danger"></span>
                <div class="row">
                    <div class="col-12 col-md-10 col-lg-8">
                        <div class="form-group">
                            <?= $form->field($model, 'username')->textInput(['autofocus' => true])->label('E-mail адрес') ?>
                        </div>
                    </div>
                </div>
                <?= Html::submitButton('Восстановить пароль', ['class' => 'btn btn-primary mt-5']) ?>
            <?php ActiveForm::end(); ?>
        </div>
        
        <div class="form-footer">
            <p>Вернуться назад для <a href="#loginFormTabInModal-login" class="tab-external-link font600">Авторизации</a> или <a href="#loginFormTabInModal-register" class="tab-external-link font600">Регистрации</a></p>
        </div>
        
    </div>
    
</div>
