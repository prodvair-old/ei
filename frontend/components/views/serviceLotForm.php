<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

use common\models\Query\Bankrupt\LotsBankrupt;
use common\models\Query\Arrest\LotsArrest;

use frontend\components\LotBlock;

switch ($lotType) {
    case 'arrest':
            $lot = LotsArrest::findOne($lotId);
            $name = 'Арестованное имущество';
        break;
    case 'bankrupt':
            $lot = LotsBankrupt::findOne($lotId);
            $name = 'Банкротнное имущество';
        break;
}
?>
<div role="tabpanel" class="tab-pane" id="lotFormTabInModal-service">
    
    <div class="form-service">

        <div class="form-header">
            <h4>Подать заявку на лот №<?=$lotId?>, <span class="font400"><?=$name?></span></h4>
            <p>Наши опытные специалисты быстро и грамотно подготовят документы для участия в торгах. По статистике мы выигрываем 76% торгов.</p>
        </div>
        
        <div class="form-body">
        
            <?php $form = ActiveForm::begin(['action'=>Url::to(['lot/lot_service']), 'id'=>'lot-service-form']); ?>

                <span class="signup-form-error tab-external-link block mt-25 text-danger"></span>
            
                <div class="row">

                    <div class="col-lg-6 col-12">
                        <?=LotBlock::widget(['lot' => $lot])?>
                    </div>
									
                    <div class="col-lg-6 col-12">

                        <div class="form-inner border-bottom">
                            <div class="custom-control custom-checkbox">
                                <?= $form->field($model, 'ecp',[
                                    'template' => "{input}{label}"
                                    ])->checkbox(['labelOption' => ['class'=>'line-145']])->label('У меня есть ЭЦП') ?>
                            </div>
                        </div>

                        <?=$form->field($model, 'lotType')->hiddenInput(['value'=>$lotType])->label(false);?>
                        <?=$form->field($model, 'lotId')->hiddenInput(['value'=>$lotId])->label(false);?>
                        <?=$form->field($model, 'servicePrice')->hiddenInput(['class'=>'service-lot-itog-input'])->label(false);?>

                        <div class="form-inner mt-15 border-bottom">
                            <div class="custom-control custom-checkbox">
                                <?= $form->field($model, 'serviceAgent',[
                                    'template' => "{input}{label}"
                                    ])->checkbox(['class' => 'service-check-inpurt', 'data-price' => '0', 'labelOption' => ['class'=>'line-145']])
                                    ->label('Услуга агента по тограм <span class="text-primary ml-5">Бесплатно</span>') ?>
                            </div>
                            <div class="custom-control custom-checkbox">
                                <?= $form->field($model, 'serviceKonsultEcp',[
                                    'template' => "{input}{label}"
                                    ])->checkbox(['class' => 'service-check-inpurt', 'data-price' => $lot->lotPrice, 'labelOption' => ['class'=>'line-145']])
                                    ->label('Консультация и сопровождение в получении ЭЦП <span class="text-primary ml-5">'.Yii::$app->formatter->asCurrency($lot->lotPrice).'</span>') ?>
                            </div>
                            <div class="custom-control custom-checkbox">
                                <?= $form->field($model, 'serviceRegEcp',[
                                    'template' => "{input}{label}"
                                    ])->checkbox(['class' => 'service-check-inpurt', 'data-price' => '2500', 'labelOption' => ['class'=>'line-145']])
                                    ->label('Регистрация на ЭТП <span class="text-primary ml-5">2500 ₽</span>') ?>
                            </div>
                            <div class="custom-control custom-checkbox">
                                <?= $form->field($model, 'serviceSendZ',[
                                    'template' => "{input}{label}"
                                    ])->checkbox(['class' => 'service-check-inpurt', 'data-price' => '5000', 'labelOption' => ['class'=>'line-145']])
                                    ->label('Подача заявки на участие в торгах в нерабочее время (с 18:00 до 23:00) <span class="text-primary ml-5">5000 ₽</span>') ?>
                                
                            </div>
                            <div class="custom-control custom-checkbox">
                                <?= $form->field($model, 'serviceTorg',[
                                    'template' => "{input}{label}"
                                    ])->checkbox(['class' => 'service-check-inpurt', 'data-price' => '5000', 'labelOption' => ['class'=>'line-145']])
                                    ->label('Участие в торгах вторым и каждым последующим участником (покупателем) <span class="text-primary ml-5">5000 ₽</span>') ?>
                            </div>
                            <div class="custom-control custom-checkbox">
                                <?= $form->field($model, 'serviceSendLastZ',[
                                    'template' => "{input}{label}"
                                    ])->checkbox(['class' => 'service-check-inpurt', 'data-price' => '7000', 'labelOption' => ['class'=>'line-145']])
                                    ->label('Подача заявки за 30 минут до окончания приема заявок <span class="text-primary ml-5">7000 ₽</span>') ?>
                            </div>
                        </div>

                        <div class="form-inner border-bottom">
                            ИТОГО: <span class="font600 service-lot-itog">0</span> ₽
                        </div>
                    </div>
                
                </div>
            
                <div class="d-flex flex-column flex-md-row mt-50 mt-lg-10">
                    <div class="flex-shrink-0">
                        <?=Html::submitButton('Отправить', ['class' => 'btn btn-primary btn-wide', 'name' => 'signup-button'])?>
                    </div>
                    <div class="pt-1 ml-0 ml-md-15 mt-15 mt-md-0">
                        <div class="custom-control custom-checkbox">
                            <?= $form->field($model, 'checkPolicy',[
                                'template' => "{input}{hint}{label}{error}"
                                ])->checkbox(['labelOption' => ['class'=>'line-145'], 'checked'=>true])->label('Я принимаю условия соглашения!') ?>
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