<?php
use frontend\components\NumberWords;
?>
<aside class=" sidebar-wrapper no-border mt-20 mt-lg-0">

    <div class="booking-box">
    
        <div class="box-heading"><h3 class="h6 text-white text-uppercase">Информация о лоте</h3></div>
        
        <div class="box-content">
            
            <span class="font600 text-muted line-125">Начальная цена</span>
            <h4 class="line-125"> <?= Yii::$app->formatter->asCurrency($lot->startingPrice)?> </h4>
            
            <ul class="border-top mt-20 pt-15">
                <?= ($lot->info['eal-status'])? '<li class="clearfix">Статус сделки<span class="float-right">'.$lot->info['eal-status'].'</span></li>' : ''?>
                <?= ($lot->step || $lot->step > 0)? '<li class="clearfix">Шаг<span class="float-right">'.$lot->step.'</span></li>' : ''?>
                <?= ($lot->stepCount || $lot->stepCount > 0)? '<li class="clearfix">Количество шагов<span class="float-right">'.$lot->stepCount.'</span></li>' : ''?>
                <?= ($lot->currentPeriod)? '<li class="clearfix">Период действия цены <span class="float-right">'.$lot->currentPeriod.'</span></li>' : '' ?>
                <?= ($lot->completionDate)? '<li class="clearfix">Дата завершения <span class="float-right">'.Yii::$app->formatter->asDate($lot->completionDate, 'long').'</span></li>' : '' ?>
                <?= ($lot->procedureDate)? '<li class="clearfix">Срок проведения процедуры <span class="float-right">'.Yii::$app->formatter->asDate($lot->procedureDate, 'long').'</span></li>' : '' ?>
                <?= ($lot->conclusionDate)? '<li class="clearfix">Срок заключения договора <span class="float-right">'.Yii::$app->formatter->asDate($lot->conclusionDate, 'long').'</span></li>' : '' ?>
                <li class="clearfix">Тип лота <span class="float-right"><?= $lot->tradeType ?></span></li>
                <?=($lot->info['url'])? '<li class="clearfix"><a href="'.$lot->info['url'].'" target="_blank" rel="nofollow">Страница лота</a></li>' : ''?>
                <li class="clearfix border-top font700">
                    <div class="border-top mt-1">
                    <span>Цена</span><span class="float-right text-dark"><?=Yii::$app->formatter->asCurrency($lot->startingPrice)?></span>
                    </div>
                </li>
            </ul>
            
            <p class="text-right font-sm"></p>

            <? if(empty($lot->lot_archive)): ?>
              <? if (!$lot->lot_archive): ?>
                <a <?=(Yii::$app->user->isGuest)? 'href="#loginFormTabInModal-login" data-toggle="modal" data-target="#loginFormTabInModal" data-backdrop="static" data-keyboard="false"' : 'href="#lotFormTabInModal-service" data-toggle="modal" data-target="#lotFormTabInModal" data-backdrop="static" data-keyboard="false"'?> class="btn btn-primary btn-block" <?=($lot->owner->tamplate['color-1'])? 'style="background:'.$lot->owner->tamplate['color-1'].';border-color:'.$lot->owner->tamplate['color-1'].'"': ''?> >Подать заявку</a>
              <? endif ?>
            <? endif ?>
                        
        </div>
        
        <div class="box-bottom bg-light">
            <h6 class="font-sm">Консультация по лоту</h6>
            <p class="font-sm">Мы ответим на все вопросы по данному лоту: <br><a href="tel:8(800)600-33-05" class="text-primary" <?=($lot->owner->tamplate['color-4'])? 'style="color:'.$lot->owner->tamplate['color-4'].'!important"': ''?> >8-800-600-33-05</a>.</p>
        </div>
        
    </div>

</aside>