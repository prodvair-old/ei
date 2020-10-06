<?php

use common\models\db\Lot;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use frontend\modules\components\LotBlockSmall;

/**
 * @var $lot Lot
 * @var $lotType
 * @var $torgProperty
 */

$btnName = 'Отправить запрос <i class="ion-android-send"></i>';

switch ($torgProperty) {
    case 1:
        $name = 'Банкротное имущество';
        break;
    case 2:
        $name = 'Арестованное имущество';
        break;
    case 3:
        $name = 'Имущество организации';
        break;
    case 4:
        $name = 'Муниципальное имущество';
        break;
}

function getAgentPrice($lotPrice, $torgProperty) {
    $agentPriceMap = [
        'bankrupt' => [
            6000,
            8000,
            10000,
            15000,
            20000,
            30000
        ],
        'other'    => [
            8000,
            10000,
            12000,
            15000,
            20000,
            30000
        ]
    ];

    $agentPrice = 6000;

    if($torgProperty == 1) {
        $agentPriceMap = $agentPriceMap['bankrupt'];
    }
    else {
        $agentPriceMap = $agentPriceMap['other'];
    }

    if ($lotPrice < 500000) {
        $agentPrice = $agentPriceMap[0];
    } else if ($lotPrice >= 500000 && $lotPrice < 2000000) {
        $agentPrice = $agentPriceMap[1];
    } else if ($lotPrice >= 2000000 && $lotPrice < 10000000) {
        $agentPrice = $agentPriceMap[2];
    } else if ($lotPrice >= 10000000 && $lotPrice < 20000000) {
        $agentPrice = $agentPriceMap[3];
    } else if ($lotPrice >= 20000000 && $lotPrice < 30000000) {
        $agentPrice = $agentPriceMap[4];
    } else if ($lotPrice >= 30000000) {
        $agentPrice = $agentPriceMap[5];
    }

    return $agentPrice;
}

?>
<div role="tabpanel" class="tab-pane" id="lotFormTabInModal-service">
    <div class="form-service">

        <div class="form-header row">
            <div class="col-md-11">
                <h4>Подать заявку на лот №<?= $lot->id ?></h4>
                <!-- <p>Наши опытные специалисты быстро и грамотно подготовят документы для участия в торгах. По статистике мы выигрываем 76% торгов.</p> -->
<!--                --><?//=($logo)? '<img src="http://n.ei.ru'.$logo.'" alt="">': '' ?>
            </div>


            <div class="col-md-1">
                <button type="button" class="close" data-dismiss="modal" aria-labelledby="Close">
                    <span aria-hidden="true"><i class="far fa-times-circle"></i></span>
                </button>
            </div>
        </div>

        
        <div class="form-body pb-30">
        
            <?php $form = ActiveForm::begin(['action'=>Url::to(['/lot/lot/order-save']), 'id'=>'lot-service-form']); ?>

                <span class="signup-form-error tab-external-link block mt-25 text-danger"></span>
            
                <div class="row">

                    <div class="col-lg-6 col-12">
                        <div>
                            <?=LotBlockSmall::widget(['lot' => $lot])?>
                        </div>
                    </div>
									
                    <div class="col-lg-6 col-12">

                        <h6 class="mt-10">
                            Выкупите этот лот через <a href="https://agent.broker" class="text-green">agent.broker</a>!
                        </h6>

                        <h4 style="line-height: 32px;">Стоимость участия в торгах: <span class="text-primary"><?= getAgentPrice($lot->start_price, $torgProperty)?> руб.!</span></h4>
                        <?=$form->field($model, 'lot_id')->hiddenInput(['value'=> $lot->id])->label(false);?>
                        <?=$form->field($model, 'user_id')->hiddenInput(['value'=> Yii::$app->user->identity->getId()])->label(false);?>

                        <!-- <ul class="mt-20">
                            <li class="mt-15 pr-20"><h6 class="text-muted">Подадим заявку на торги, которую не отклонят</h6></li>
                            <li class="mt-15 pr-20"><h6 class="text-muted">Участие в торгах без аккредитации на ЭТП</h6></li>
                            <li class="mt-15 pr-20"><h6 class="text-muted">Не нужно покупать ЭЦП, мы используем свою</h6></li>
                        </ul> -->

                        <style>
                            .custom-control-label::before, .custom-control-label::after {
                                top: -3px
                            }
                            .search-form-control {
                                border: 2px solid #077751!important;
                            }
                            .search-box {
                                -webkit-box-shadow: none;
                                box-shadow: none;
                                border-radius: 3px;
                                border: 1px solid #d6dade;
                                padding: 15px;
                            }
                            .search-box .control-label {
                                display: block;
                                margin-bottom: .25rem;
                                line-height: 1;
                                font-size: 12px;
                                font-weight: 700;
                                text-transform: uppercase;
                            }
                        </style>

                        <div class=" mt-30">
                            <?= $form->field($model, 'bid_price')->textInput([
                                    'type' => 'number',
                                    'class'       => 'form-control search-form-control borr-10 mt-5 pl-10 pt-5 pr-10 pb-5',
                                    'placeholder' => 'Не обязательное поле',
                                    'tabindex'    => '2',
                                ])->label('Ваша желаемая цена покупки, руб.'); ?>
                        </div>
                        <div class=" mt-30">
                            <?=Html::submitButton($btnName, ['class' => 'btn btn-primary btn-wide borr-10', 'style' => 'background: '.$color1.';border-color:'.$color1, 'name' => 'signup-button'])?>
                        </div>

                        <div class="custom-checkbox mt-10">
                            <?= $form->field($model, 'checkPolicy',[
                                'template' => "{input}{hint}{label}{error}"
                                ])->checkbox(['labelOption' => ['class'=>'line-145'], 'checked'=>true])->label('Согласен с <a href="/policy">пользовательским соглашением</a> и политикой в отношении обработки персональных данных.') ?>

                        </div>
                    </div>
                
                </div>
                
            <?php ActiveForm::end(); ?>
            
        </div>

        <div class="form-confirm">
            <div class="success-icon-text">
                <span class="icon-font  text-success"><i class="elegent-icon-check_alt2"></i></span>
                <h4 class="text-uppercase letter-spacing-1">Сообщение отправлено!</h4>
                <p>Мы ответим на все вопросы по данному лоту в ближайшее время. Если у Вас возникли воросы звоните по номеру: <br><a href="tel:8(800)600-33-05" class="text-primary">8-800-600-33-05</a>.</p>
            </div>
        </div>
                
    </div>
    
</div>