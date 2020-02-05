<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;

use common\models\Query\Bankrupt\LotsBankrupt;
use common\models\Query\Arrest\LotsArrest;
use common\models\Query\Lot\Lots;

use frontend\components\LotBlock;

$btnName = 'Участвовать через агента';

$lot = Lots::findOne($lotId);

switch ($lotType) {
    case 'arrest':
            $name = 'Арестованное имущество';
        break;
    case 'bankrupt':
            $name = 'Банкротное имущество';
        break;
    case 'zalog':
            $name = ($lot->torg->owner->title)? $lot->owner->title : 'Имущество организации';
            $btnName = 'Подать заявку';
            $color4 = $lot->otorg->wner->tamplate['color-4'];
            $color1 = $lot->torg->owner->tamplate['color-1'];
            $logo = $lot->torg->owner->logo;
        break;
}


if ($lot->price < 500000) {
    $agentPrice = 8000;
} else if ($lot->price < 1000000) {
    $agentPrice = 12000;
} else if ($lot->price < 2000000) {
    $agentPrice = 15000;
} else if ($lot->price < 4000000) {
    $agentPrice = 20000;
} else if ($lot->price < 6000000) {
    $agentPrice = 25000;
} else if ($lot->price < 8000000) {
    $agentPrice = 30000;
} else if ($lot->price < 10000000) {
    $agentPrice = 35000;
} else if ($lot->price < 15000000) {
    $agentPrice = 40000;
} else if ($lot->price < 30000000) {
    $agentPrice = 50000;
} else {
    $agentPrice = 60000;
}
?>
<div role="tabpanel" class="tab-pane" id="lotFormTabInModal-service">
    
    <div class="form-service">

        <div class="form-header">
            <h4>Подать заявку на лот №<?=$lotId?>, <span class="font400"><?=$name?></span></h4>
            <p>Наши опытные специалисты быстро и грамотно подготовят документы для участия в торгах. По статистике мы выигрываем 76% торгов.</p>
            <?=($logo)? '<img src="http://n.ei.ru'.$logo.'" alt="">': '' ?>
        </div>
        
        <div class="form-body">
        
            <?php $form = ActiveForm::begin(['action'=>Url::to(['lot/lot_service']), 'id'=>'lot-service-form']); ?>

                <span class="signup-form-error tab-external-link block mt-25 text-danger"></span>
            
                <div class="row">

                    <div class="col-lg-6 col-12">
                        <?=LotBlock::widget(['lot' => $lot, 'color' => $color4])?>
                    </div>
									
                    <div class="col-lg-6 col-12">

                        <h6 class="mt-10">
                            Выкупите этот лот через агента!
                        </h6>

                        <h4>Стоимость участия в торгах: <span class="text-primary" <?= ($color4)? 'style="color: '.$color4.'!important"' : '' ?>><?=$agentPrice?> руб.!</span></h4>
                        <?=$form->field($model, 'lotType')->hiddenInput(['value'=>$lotType])->label(false);?>
                        <?=$form->field($model, 'lotId')->hiddenInput(['value'=>$lotId])->label(false);?>
                        
                        <ul class="mt-20">
                            <li class="mt-15 pr-20"><h6 class="text-muted">Подадим заявку на торги, которую не отклонят</h6></li>
                            <li class="mt-15 pr-20"><h6 class="text-muted">Участие в торгах без аккредитации на ЭТП</h6></li>
                            <li class="mt-15 pr-20"><h6 class="text-muted">Не нужно покупать ЭЦП, мы используем свою</h6></li>
                        </ul>

                        <div class=" mt-30">
                            <?=Html::submitButton($btnName, ['class' => 'btn btn-primary btn-wide', 'style' => 'background: '.$color1.';border-color:'.$color1, 'name' => 'signup-button'])?>
                        </div>

                        <div class="custom-checkbox mt-10">
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