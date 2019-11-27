<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\ResetPasswordForm */

use yii\helpers\Html;

$this->title = 'Аккаунт подтверждён';
?>
<section class="page-wrapper page-detail">
    
    <div class="container pt-50 mt-80">

        <div class="row justify-content-center">
            
            <div class="col-12 col-md-11 col-lg-10 col-xl-9">
                
                <div class="content-wrapper">
                    
                    <div class="success-icon-text">
                        <span class="icon-font  text-success"><i class="elegent-icon-check_alt2"></i></span>
                        <h4 class="text-uppercase letter-spacing-1"><?=Html::encode($this->title)?>!</h4>
                        <p>Спасибо Вам за регистрацию на сайте Ei.ru. Теперь вы можете авторизоваться и пользоваться всеми сервисами услуг.</p>
                    </div>
                    
                </div>
                
            </div>
            
        </div>
        
    </div>

</section>