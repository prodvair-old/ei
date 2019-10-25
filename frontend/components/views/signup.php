<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>
<div role="tabpanel" class="tab-pane fade in" id="loginFormTabInModal-register">
    
    <div class="form-login">

        <div class="form-header">
            <h4>Регистрация на сайте Ei.ru</h4>
            <p>Заполните все поля и подтвердите аккаунт через почту</p>
        </div>
        
        <div class="form-body">
        
            <?php $form = ActiveForm::begin(['action'=>'/signup', 'id' => 'signup-form']); ?>

                <span class="signup-form-error tab-external-link block mt-25 text-danger"></span>
            
                <div class="d-flex flex-column flex-lg-row align-items-stretch">
                
                    <div class="flex-grow-1 bg-primary-light">

                        <div class="form-inner">
                        <div class="form-group">
                                <?= $form->field($model, 'email')->label('E-Mail Адрес') ?>
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
            
                <div class="d-flex flex-column flex-md-row mt-30 mt-lg-10">
                    <div class="flex-shrink-0">
                        <?=Html::submitButton('Зарегистрироваться', ['class' => 'btn btn-primary btn-wide', 'name' => 'signup-button'])?>
                    </div>
                    <div class="pt-1 ml-0 ml-md-15 mt-15 mt-md-0">
                        <div class="custom-control custom-checkbox">
                            <?= $form->field($model, 'checkPolicy',[
                                'template' => "{input}{label}"
                                ])->checkbox(['labelOption' => ['class'=>'line-145']])->label('Я принимаю условия соглашения!') ?>
                        </div>
                    </div>
                </div>
            
            <?php ActiveForm::end(); ?>
            
        </div>
        
        <div class="form-footer">
            <p>У меня есть аккаунт. <a href="#loginFormTabInModal-login" class="tab-external-link font600">Войти</a></p>
        </div>
        
    </div>
    
</div>