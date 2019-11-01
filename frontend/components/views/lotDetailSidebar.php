<?php
use frontend\components\NumberWords;
?>
<aside class="sticky-kit-02 sidebar-wrapper no-border mt-20 mt-lg-0">

    <div class="booking-box">
    
        <div class="box-heading"><h3 class="h6 text-white text-uppercase">Информация о лоте</h3></div>
        
        <div class="box-content">
        
            <span class="font600 text-muted line-125">Дата начала торгов</span>
            <!-- <small class="d-block">(3 days) <a href="#detail-content-sticky-nav-05" class="anchor font10 pl-40 d-block text-uppercase h6 text-primary float-right mt-5">Change</a></small> -->
            <h4 class="line-125 choosen-date mt-3"><i class="ri-calendar"></i> <?= Yii::$app->formatter->asDate($lot->lotDateStart, 'long')?> </h4>
            <span class="font600 text-muted line-125">Дата окончания торгов</span>
            <!-- <small class="d-block">(3 days) <a href="#detail-content-sticky-nav-05" class="anchor font10 pl-40 d-block text-uppercase h6 text-primary float-right mt-5">Change</a></small> -->
            <h4 class="line-125 choosen-date mt-3"><i class="ri-calendar"></i> <?= Yii::$app->formatter->asDate($lot->lotDateEnd, 'long')?> </h4>
            
            <!-- <div class="form-group form-spin-group border-top mt-15 pt-10">
                <label class="h6 font-sm">How many guests?</label>
                <input type="text" class="form-control touch-spin-03 form-control-readonly" value="2" readonly />
            </div> -->
            
            <ul class="border-top mt-20 pt-15">
                <li class="clearfix">Шаг<span class="float-right"><?=($lot->auctionstepunit == 'Percent')? $lot->stepprice.'% ('.Yii::$app->formatter->asCurrency((($lot->lotPrice / 100) * $lot->stepprice)).')' : Yii::$app->formatter->asCurrency($lot->stepprice) ?></span></li>
                <li class="clearfix">Задаток<span class="float-right"><?=($lot->advancestepunit == 'Percent')? $lot->advance.'% ('.Yii::$app->formatter->asCurrency((($lot->lotPrice / 100) * $lot->advance)).')' : Yii::$app->formatter->asCurrency($lot->advance) ?></span></li>
                <li class="clearfix">Форма предложения цены <span class="float-right"><?=($lot->torgy->pricetype == 'Public')? 'Открытая' : 'Закрытая'?></span></li>
                <li class="clearfix">Тип торгов <span class="float-right text-<?=($lot->torgy->tradetype == 'OpenedAuction')? 'success' : 'primary'?>"><?=($lot->torgy->tradetype == 'OpenedAuction')? 'Открытый Аукцион' : 'Публичное предложение'?></span></li>
                <li class="clearfix">ЭТП <span class="float-right"><?=$lot->torgy->tradesite?></span></li>
                <li class="clearfix border-top font700">
                    <div class="border-top mt-1">
                    <span>Цена</span><span class="float-right text-dark"><?=Yii::$app->formatter->asCurrency($lot->lotPrice)?></span>
                    </div>
                </li>
            </ul>
            
            <p class="text-right font-sm"></p>
            
            <a href="https://bankrot.fedresurs.ru/MessageWindow.aspx?ID=<?=$lot->torgy->msgguid?>" target="_blank" rel="nofollow" class="btn btn-primary btn-block">Страница сообщения</a>
            
            <!-- <p class="line-115 mt-20">By clicking the above button you agree to our <a href="#">Terms of Service</a> and have read and understood our <a href="#">Privacy Policy</a></p> -->
            
        </div>
        
        <!-- <div class="box-bottom bg-light">
            <h6 class="font-sm">We are the best tour operator</h6>
            <p class="font-sm">Our custom tour program, direct call <span class="text-primary">+66857887444</span>.</p>
        </div> -->
        
    </div>

</aside>