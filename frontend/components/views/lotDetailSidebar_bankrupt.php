<?php
use frontend\components\NumberWords;
// $etpUrl = (strpos($lot->torgy->etp->tradesite, 'http') === 0)? $lot->torgy->etp->tradesite : 'http://'.$lot->torgy->etp->tradesite;
// foreach ($lot->purchaselots as $key => $value) {
//   if ($value->pheLotNumber == $lot->lotid) {
//     $status = $value->pheLotStatus;
//     $etpUrl = $value->pheLotUrl;
//   }
// }
?>
<aside class=" sidebar-wrapper no-border mt-20 mt-lg-0">

    <div class="booking-box">
    
        <div class="box-heading"><h3 class="h6 text-white text-uppercase">Информация о лоте</h3></div>
        
        <div class="box-content">
            
            <? if ($lot->torg->tradeTypeId == 1) {?>
                <span class="font600 text-muted line-125">Текущая цена</span>
                <h4 class="line-125"> <?= Yii::$app->formatter->asCurrency($lot->price)?> </h4>
            <? } ?>
            <span class="font600 text-muted line-125">Начальная цена</span>
            <h4 class="line-125 <?=($lot->torg->tradeTypeId == 2)? 'text-muted' : ''?>"> <?= Yii::$app->formatter->asCurrency($lot->startPrice)?> </h4>
            
            <!-- <div class="form-group form-spin-group border-top mt-15 pt-10">
                <label class="h6 font-sm">How many guests?</label>
                <input type="text" class="form-control touch-spin-03 form-control-readonly" value="2" readonly />
            </div> -->
            
            <ul class="border-top mt-20 pt-15">
                <li class="clearfix">Статус<span class="float-right"><?=$lot->status?></span></li>
                <li class="clearfix">Шаг<span class="float-right"><?=($lot->stepTypeId == 1)? $lot->step.'% ('.Yii::$app->formatter->asCurrency((($lot->price / 100) * $lot->step)).')' : Yii::$app->formatter->asCurrency($lot->step) ?></span></li>
                <li class="clearfix">Задаток<span class="float-right"><?=($lot->depositTypeId == 1)? $lot->deposit.'% ('.Yii::$app->formatter->asCurrency((($lot->price / 100) * $lot->deposit)).')' : Yii::$app->formatter->asCurrency($lot->deposit) ?></span></li>
                <li class="clearfix">Форма предложения цены <span class="float-right"><?=($lot->torg->info['priceType'] == 'Public')? 'Открытая' : 'Закрытая'?></span></li>
                <li class="clearfix">Тип торгов <span class="float-right text-<?=($lot->torg->tradeTypeId == 2)? 'success' : 'primary'?>"><?=($lot->torg->tradeTypeId == 2)? 'Открытый аукцион' : 'Публичное предложение'?></span></li>
                <li class="clearfix">ЭТП <span class="float-right"><?=$lot->torg->etp->title?></span></li>
                <li class="clearfix border-top"><?= ($lot->torg->etp->url !== 'http://')? '<a href="'.$lot->torg->etp->url.'" target="_blank" rel="nofollow">Ссылка на торги</a>' : null ?></li>
                <li class="clearfix"><a href="https://bankrot.fedresurs.ru/MessageWindow.aspx?ID=<?=$lot->torg->msgId?>" target="_blank" rel="nofollow">Страница лота на ЕФРСБ</a></li>
                <li class="clearfix border-top font700">
                    <div class="border-top mt-1">
                    <span>Цена</span><span class="float-right text-dark"><?=Yii::$app->formatter->asCurrency($lot->price)?></span>
                    </div>
                </li>
            </ul>
            
            <p class="text-right font-sm"></p>

            <? if(empty($lot->archive)): ?>
              <? if (!$lot->archive): ?>
                <a <?=(Yii::$app->user->isGuest)? 'href="#loginFormTabInModal-login" data-toggle="modal" data-target="#loginFormTabInModal" data-backdrop="static" data-keyboard="false"' : 'href="#lotFormTabInModal-service" data-toggle="modal" data-target="#lotFormTabInModal" data-backdrop="static" data-keyboard="false"'?> class="btn btn-primary btn-block">Подать заявку</a>
              <? endif ?>
            <? endif ?>
                        
        </div>
        
        <div class="box-bottom bg-light">
            <h6 class="font-sm">Консультация по лоту</h6>
            <p class="font-sm">Мы ответим на все вопросы по данному лоту: <br><a href="tel:8(800)600-33-05" class="text-primary">8-800-600-33-05</a>.</p>
        </div>
        
    </div>

</aside>