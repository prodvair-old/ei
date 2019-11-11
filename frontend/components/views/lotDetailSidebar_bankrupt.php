<?php
use frontend\components\NumberWords;
$etpUrl = (strpos($lot->torgy->etp->tradesite, 'http') === 0)? $lot->torgy->etp->tradesite : 'http://'.$lot->torgy->etp->tradesite;
foreach ($lot->purchaselots as $key => $value) {
  if ($value->pheLotNumber == $lot->lotid) {
    $status = $value->pheLotStatus;
    $etpUrl = $value->pheLotUrl;
  }
}
?>
<aside class="sticky-kit-02 sidebar-wrapper no-border mt-20 mt-lg-0">

    <div class="booking-box">
    
        <div class="box-heading"><h3 class="h6 text-white text-uppercase">Информация о лоте</h3></div>
        
        <div class="box-content">
            
            <? if ($lot->torgy->tradetype == 'PublicOffer') {?>
                <span class="font600 text-muted line-125">Текущая цена</span>
                <h4 class="line-125"> <?= Yii::$app->formatter->asCurrency($lot->lotPrice)?> </h4>
            <? } ?>
            <span class="font600 text-muted line-125">Начальная цена</span>
            <h4 class="line-125 <?=($lot->torgy->tradetype == 'PublicOffer')? 'text-muted' : ''?>"> <?= Yii::$app->formatter->asCurrency($lot->startprice)?> </h4>
            
            <!-- <div class="form-group form-spin-group border-top mt-15 pt-10">
                <label class="h6 font-sm">How many guests?</label>
                <input type="text" class="form-control touch-spin-03 form-control-readonly" value="2" readonly />
            </div> -->
            
            <ul class="border-top mt-20 pt-15">
                <li class="clearfix">Статус<span class="float-right"><?=$status?></span></li>
                <li class="clearfix">Шаг<span class="float-right"><?=($lot->auctionstepunit == 'Percent')? $lot->stepprice.'% ('.Yii::$app->formatter->asCurrency((($lot->lotPrice / 100) * $lot->stepprice)).')' : Yii::$app->formatter->asCurrency($lot->stepprice) ?></span></li>
                <li class="clearfix">Задаток<span class="float-right"><?=($lot->advancestepunit == 'Percent')? $lot->advance.'% ('.Yii::$app->formatter->asCurrency((($lot->lotPrice / 100) * $lot->advance)).')' : Yii::$app->formatter->asCurrency($lot->advance) ?></span></li>
                <li class="clearfix">Форма предложения цены <span class="float-right"><?=($lot->torgy->pricetype == 'Public')? 'Открытая' : 'Закрытая'?></span></li>
                <li class="clearfix">Тип торгов <span class="float-right text-<?=($lot->torgy->tradetype == 'OpenedAuction')? 'success' : 'primary'?>"><?=($lot->torgy->tradetype == 'OpenedAuction')? 'Открытый аукцион' : 'Публичное предложение'?></span></li>
                <li class="clearfix">ЭТП <span class="float-right"><?=$lot->torgy->tradesite?></span></li>
                <li class="clearfix border-top"><?= ($etpUrl !== 'http://')? '<a href="'.$etpUrl.'" target="_blank" rel="nofollow">Ссылка на торги</a>' : null ?></li>
                <li class="clearfix"><a href="https://bankrot.fedresurs.ru/MessageWindow.aspx?ID=<?=$lot->torgy->msgguid?>" target="_blank" rel="nofollow">Страница лота на ЕФРСБ</a></li>
                <li class="clearfix border-top font700">
                    <div class="border-top mt-1">
                    <span>Цена</span><span class="float-right text-dark"><?=Yii::$app->formatter->asCurrency($lot->lotPrice)?></span>
                    </div>
                </li>
            </ul>
            
            <p class="text-right font-sm"></p>
            
            <a <?=(Yii::$app->user->isGuest)? 'href="#loginFormTabInModal-login" data-toggle="modal" data-target="#loginFormTabInModal" data-backdrop="static" data-keyboard="false"' : 'href="#lotFormTabInModal-service" data-toggle="modal" data-target="#lotFormTabInModal" data-backdrop="static" data-keyboard="false"'?> class="btn btn-primary btn-block">Подать заявку</a>
                        
        </div>
        
        <div class="box-bottom bg-light">
            <h6 class="font-sm">Консультация по лоту</h6>
            <p class="font-sm">Мы ответим на все вопросы по данному лоту: <br><a href="tel:8(800)600-33-05" class="text-primary">8-800-600-33-05</a>.</p>
        </div>
        
    </div>

</aside>