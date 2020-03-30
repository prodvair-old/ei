<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>
<div role="tabpanel" class="tab-pane fade in" id="loginFormTabInModal-register">
    
    <div class="form-login">
        <div id="alert-email-confirm" class="alert alert-success" style="display: none;">
            <h4 class="pb-1">Спасибо за регистрацию!</h4>
            <p>Для подтверждения регистрации вам необходимо пройти по ссылке отправленной на ваш электронный адрес</p> 
        </div> 

        <div id="register-form">
        <div class="form-header">
            <h4>Регистрация на сайте ei.ru</h4>
            <p>Заполните все поля и подтвердите аккаунт через почту</p>
        </div>
        
        <div class="form-body">
        
            <?php $form = ActiveForm::begin(['action'=>'/signup', 'id' => 'signup-form']); ?>

                <span class="signup-form-error tab-external-link block mt-25 text-danger"></span>
            
                <div class="d-flex flex-column flex-lg-row align-items-stretch">
                
                    <div class="flex-grow-1 bg-primary-light">

                        <div class="form-inner">
                        <div class="form-group">
                                <?= $form->field($model, 'email')->label('E-mail адрес') ?>
                            </div>
                            <div class="form-group">
                                <?= $form->field($model, 'phone')->label('Номер телефона') ?>
                            </div>
                            <div class="row cols-2 gap-10">
                                <div class="col">
                                    <div class="form-group">
                                        <?= $form->field($model, 'password')->passwordInput()->label('Пароль') ?>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <?= $form->field($model, 'passwordConfirm')->passwordInput()->label('Подтвердите пароль') ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                
                </div>
            
                <div class="d-flex flex-column flex-md-row mt-30 mt-lg-10 ">
                   
                        
                    <div class="pt-1 ml-0 mt-15 mt-md-0">
                        <div class="custom-control custom-checkbox pl-0">
                            <?= $form->field($model, 'checkPolicy',[
                                'template' => "{input}{label}"
                                ])->checkbox(['checked' => true])->label('Я принимаю <a href="/policy" target="_blank">соглашения!</a>') ?>
                        </div>
                        <?=Html::submitButton('Зарегистрироваться', ['class' => 'btn btn-primary btn-wide', 'name' => 'signup-button'])?>
                    </div>
                   
                    
                </div>
            
            <?php ActiveForm::end(); ?>
            
        </div>
        
        <div class="form-footer">
            <p>У меня есть аккаунт. <a href="#loginFormTabInModal-login" class="tab-external-link font600">Войти</a></p>
        </div>
        </div>
    </div>
    
</div>