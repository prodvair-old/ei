<?php

use common\models\db\Lot;
use common\models\db\Torg;
use sergmoro1\lookup\models\Lookup;

/* @var $lot Lot */

?>
<aside class=" sidebar-wrapper no-border mt-20 mt-lg-0" itemscope itemtype="">

    <div class="booking-box">

        <div class="box-heading"><h3 class="h6 text-white text-uppercase">Информация о лоте</h3></div>

        <div class="box-content p-0">

            <?if ($lot->newPrice && $lot->torg->offer == 1) { ?>
                <span class="font600 text-muted line-125">Текущая цена</span>
                <h4 itemprop="price" class="text-danger line-125 mt-0" style="font-size: 28px;" title="Текущая цена">
                    <?= Yii::$app->formatter->asCurrency($lot->newPrice->price) ?> 
                    <i class="ion-android-arrow-down" title="Публичное предложение"></i>
                </h4>
                <span class="font600 text-muted line-125">Старая цена</span>
                <h4 itemprop="price" class="text-danger line-125 mt-0" title="Текущая цена">
                    <span class="text-muted" title="Старая цена"><?=Yii::$app->formatter->asCurrency($lot->start_price) ?></span>
                </h4>
            <? } else { ?>
                <span class="font600 text-muted line-125">Текущая цена</span>
                <h4 itemprop="price" class="text-secondary line-125 mt-0" style="font-size: 28px;">
                    <?= Yii::$app->formatter->asCurrency($lot->start_price) ?>
                    <?= ($lot->torg->offer == 1)? '<i class="ion-android-arrow-down" title="Публичное предложение"></i>': ''?>
                    <?= ($lot->torg->offer == 2)? '<i class="ion-android-arrow-up" title="Аукцион"></i>': ''?>
                </h4>
            <? }?>

            <ul class="border-top mt-20 pt-15">
                <li class="clearfix">Статус<span
                            class="float-right"><?= Lookup::item('LotStatus', $lot->status) ?></span></li>
                <li class="clearfix">Шаг
                    <span class="float-right">
                        <?= ($lot->step_measure == 1) ? round($lot->step, 2) . '% (' . Yii::$app->formatter->asCurrency((($lot->start_price / 100) * $lot->step)) . ')' : Yii::$app->formatter->asCurrency($lot->step) ?>                    </span>
                </li>
                <!-- <li class="clearfix">Задаток<span
                            class="float-right"><?= ($lot->deposit_measure == 1) ? round($lot->deposit, 2) . '% (' . Yii::$app->formatter->asCurrency((($lot->start_price / 100) * $lot->deposit)) . ')' : Yii::$app->formatter->asCurrency($lot->deposit) ?></span>
                </li> -->
                <!-- <li class="clearfix">Регион<span class="float-right"><?= $lot->region->name ?></span>
                </li> -->
                <li class="clearfix">Тип торгов <span
                            class="float-right"><?= ($lot->torg->offer == Torg::OFFER_AUCTION_OPEN) ? 'Открытый аукцион' : ' Публичное предложение'; ?></span>
                </li>
                <?php if ($lot->torg->property === Torg::PROPERTY_BANKRUPT) : ?>
                <?php if((Yii::$app->accessManager->isSubscriber(Yii::$app->user->id))) :?>
                        <li class="clearfix">Номер сообщения в ЕФРСБ <span
                                    class="float-right"><?= $lot->torg->msg_id ?></span></li>
                <?php else:?>
                        <li class="clearfix">Номер сообщения в ЕФРСБ <span
                                    class="float-right">Доступно по подписке</span></li>
                <?php endif;?>

                <?php endif; ?>
                <li class="clearfix">Номер лота<span class="float-right"><?= $lot->ordinal_number ?></span></li>
                
            </ul>
            <?php if ($lot->torg->property === Torg::PROPERTY_BANKRUPT) : ?>
            <ul class="mt-20 pt-10 pb-10 bg-green borr-10 pl-10 pr-10">
                <li class="clearfix"><?= $lot->torg->etp->title ?> </li>
                <? if ($lot->url) : ?>
                    <li class="clearfix">
                        <a href="<?= $lot->url ?>" target="_blank" class=" font600" rel="nofollow">Ссылка на торги <i class="fa fa-arrow-right"></i></a>
                    </li>
                <?php endif; ?>
            </ul>
            <? endif; ?>


            <p class="text-right font-sm"></p>

            <? if ($lot->status !== Lot::STATUS_ARCHIVED): ?>
                <a <?= (Yii::$app->user->isGuest) ? 'href="#loginFormTabInModal-login" data-toggle="modal" data-target="#loginFormTabInModal" data-backdrop="static" data-keyboard="false"' : 'href="#lotFormTabInModal-service" data-toggle="modal" data-target="#lotFormTabInModal" data-backdrop="static" data-keyboard="false"' ?>
                        class="btn btn-primary btn-block borr-10">Подать заявку</a>
            <? endif ?>

        </div>

        <div class="box-bottom bg-light borr-10">
            <h6 class="font-sm">У вас есть вопрос?</h6>
            <p class="font-sm">
                <a href="tel:8(800)600-33-05" class="text-primary">8-800-600-33-05</a>
                <br>
                <a href="#buyLotModal" class="font-sm" data-toggle="modal" data-target="#buyLotModal" data-backdrop="static" data-keyboard="false">Как самостоятельно приобрести этот лот</a>
            </p>
        </div>

    </div>

</aside>