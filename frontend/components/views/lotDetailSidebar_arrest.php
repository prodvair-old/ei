<?php
use frontend\components\NumberWords;
?>
<aside class="sticky-kit-02 sidebar-wrapper no-border mt-20 mt-lg-0">

    <div class="booking-box">
    
        <div class="box-heading"><h3 class="h6 text-white text-uppercase">Информация о лоте</h3></div>
        
        <div class="box-content">
            
            <span class="font600 text-muted line-125">Начальная цена</span>
            <h4 class="line-125"> <?= Yii::$app->formatter->asCurrency($lot->lotStartPrice)?> </h4>
            
            <ul class="border-top mt-20 pt-15">
                <li class="clearfix">Статус<span class="float-right"><?=$lotStatus?></span></li>
                <li class="clearfix">Шаг<span class="float-right"><?= Yii::$app->formatter->asCurrency($lot->lotPriceStep) ?></span></li>
                <li class="clearfix">Задаток<span class="float-right"><?=Yii::$app->formatter->asCurrency($lot->lotDepositSize) ?></span></li>
                <li class="clearfix">Минимальная цена<span class="float-right"><?=Yii::$app->formatter->asCurrency($lot->lotMinPrice) ?></span></li>
                <?= ($lot->lotSellTypeName != null)? '<li class="clearfix">Основания реализации<span class="float-right">'.$lot->lotSellTypeName.'</span></li>' : ''?>
                <?= ($lot->torgs->trgBidFormName != null)? '<li class="clearfix">Тип торгов<span class="float-right">'.$lot->torgs->trgBidFormName.'</span></li>' : ''?>
                <?= ($lot->torgs->trgEtpName != null)? '<li class="clearfix">ЭТП<span class="float-right">'.$lot->torgs->trgEtpName.'</span></li>' : ''?>
                <li class="clearfix border-top"><?= ($lot->torgs->trgUrl != null)? '<a href="'.$lot->torgs->trgUrl.'" target="_blank" rel="nofollow">Сайт организатора торгов</a>' : null ?></li>
                <li class="clearfix"><?= ($lot->torgs->trgNotificationUrl != null)? '<a href="'.$lot->torgs->trgNotificationUrl.'" target="_blank" rel="nofollow">Ссылка на извещения</a></li>' : null ?>
                <li class="clearfix border-top font700">
                    <div class="border-top mt-1">
                    <span>Цена</span><span class="float-right text-dark"><?=Yii::$app->formatter->asCurrency($lot->lotPrice)?></span>
                    </div>
                </li>
            </ul>
            
            <p class="text-right font-sm"></p>

            <? if(empty($lot->lot_archive)): ?>
                <? if (!$lot->lot_archive): ?>
                    <a <?=(Yii::$app->user->isGuest)? 'href="#loginFormTabInModal-login" data-toggle="modal" data-target="#loginFormTabInModal" data-backdrop="static" data-keyboard="false"' : 'href="#lotFormTabInModal-service" data-toggle="modal" data-target="#lotFormTabInModal" data-backdrop="static" data-keyboard="false"'?> class="btn btn-primary btn-block">Подать заявку</a>
                <? endif ?>
            <? endif ?>
            <!-- <p class="line-115 mt-20">By clicking the above button you agree to our <a href="#">Terms of Service</a> and have read and understood our <a href="#">Privacy Policy</a></p> -->
            
        </div>
        
        <div class="box-bottom bg-light">
            <h6 class="font-sm">Консультация по лоту</h6>
            <p class="font-sm">Мы ответим на все вопросы по данному лоту: <br><a href="tel:8(800)600-33-05" class="text-primary">8-800-600-33-05</a>.</p>
        </div>
        
    </div>

</aside>