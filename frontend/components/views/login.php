<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>
<div role="tabpanel" class="tab-pane active" id="loginFormTabInModal-login">

    <div class="form-login">

        <div class="form-header">
            <h4>Добро пожаловать на ei.ru</h4>
            <p>Авторизуйтесь на сайте, чтобы получить больше возможностей!</p>
        </div>
        
        <div class="form-body">
            <?php $form = ActiveForm::begin(['action'=>'/login', 'id' => 'login-form']); ?>
            
                <span class="login-form-error tab-external-link block mt-25 text-danger"></span>
                
                <div class="d-flex flex-column flex-lg-row align-items-stretch">

                    <div class="flex-md-grow-1 bg-primary-light">
                        <div class="form-inner">

                            <div class="form-group">
                                <?= $form->field($model, 'username')->textInput(['autofocus' => true])->label('E-mail адрес') ?>
                            </div>

                            <div class="form-group">
                                <?= $form->field($model, 'password')->passwordInput()->label('Пароль') ?>
                            </div>

                            <div class="d-flex flex-column flex-md-row mt-25">
                                <div class="flex-shrink-0">
                                    <?=Html::submitButton('Войти', ['class' => 'btn btn-primary btn-wide', 'name' => 'login-button'])?>
                                </div>
                                <div class="ml-0 ml-md-15 mt-15 mt-md-0">
                                    <div class="custom-control custom-checkbox">
                                        <?= $form->field($model, 'rememberMe',[
                                            'template' => "{input}{label}"
                                        ])->checkbox()->label('Запомнить меня') ?>
                                    </div>
                                </div>
                            </div>

                            <a href="#loginFormTabInModal-forgot-pass" class="tab-external-link block mt-25 font600">Забыли пароль?</a>

                        </div>
                    </div>
                </div>

            <?php ActiveForm::end(); ?>
            
        </div>
        
        <div class="form-footer">
            <p>У Вас нет аккаунта? <a href="#loginFormTabInModal-register" class="tab-external-link font600 ">Зарегистрироваться</a></p>
        </div>
        
    </div>

</div>