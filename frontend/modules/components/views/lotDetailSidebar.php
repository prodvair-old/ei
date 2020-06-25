<?php

use common\models\db\Lot;
use common\models\db\Torg;
use sergmoro1\lookup\models\Lookup;

/* @var $lot Lot */

?>
<aside class=" sidebar-wrapper no-border mt-20 mt-lg-0" itemscope itemtype="">

    <div class="booking-box">

        <div class="box-heading"><h3 class="h6 text-white text-uppercase">Информация о лоте</h3></div>

        <div class="box-content">

            <span class="font600 text-muted line-125">Текущая цена</span>
            <h4 class="line-125"> <?= Yii::$app->formatter->asCurrency($lot->start_price) ?> </h4>

            <ul class="border-top mt-20 pt-15">
                <li class="clearfix">Статус<span
                            class="float-right"><?= Lookup::item('LotStatus', $lot->status) ?></span></li>
                <li class="clearfix">Шаг
                    <span class="float-right">
                        <?= ($lot->step_measure == 1) ? round($lot->step, 2) . '% (' . Yii::$app->formatter->asCurrency((($lot->start_price / 100) * $lot->step)) . ')' : Yii::$app->formatter->asCurrency($lot->step) ?>                    </span>
                </li>
                <li class="clearfix">Задаток<span
                            class="float-right"><?= ($lot->deposit_measure == 1) ? round($lot->deposit, 2) . '% (' . Yii::$app->formatter->asCurrency((($lot->start_price / 100) * $lot->deposit)) . ')' : Yii::$app->formatter->asCurrency($lot->deposit) ?></span>
                </li>
                <li class="clearfix">Регион<span class="float-right"><?= $lot->region->name ?></span>
                </li>
                <li class="clearfix">Тип торгов <span
                            class="float-right text-<?= ($lot->torg->offer == Torg::OFFER_AUCTION_OPEN) ? 'success' : 'primary' ?>"><?= ($lot->torg->offer == Torg::OFFER_AUCTION_OPEN) ? 'Открытый аукцион' : ' Публичное предложение'; ?></span>
                </li>
                <li class="clearfix">ЭТП <span class="float-right"><?= $lot->torg->etp->title ?></span></li>
                <?php if ($lot->torg->property === Torg::PROPERTY_BANKRUPT) : ?>
                    <li class="clearfix">Номер сообщения в ЕФРСБ <span
                                class="float-right"><?= $lot->torg->msg_id ?></span></li>
                <?php endif; ?>
                <li class="clearfix">Номер лота<span class="float-right"><?= $lot->ordinal_number ?></span></li>
                <? if ($lot->url) : ?>
                    <li class="clearfix border-top">
                        <a href="<?= $lot->url ?>" target="_blank" rel="nofollow">Ссылка на торги</a>
                    </li>
                <? endif; ?>

                <li class="clearfix border-top font700">
                    <div class="border-top mt-1">
                        <span>Цена</span><span class="float-right text-dark"
                                               itemprop="https://schema.org/price"><?= Yii::$app->formatter->asCurrency($lot->start_price) ?></span>
                    </div>
                </li>
            </ul>

            <p class="text-right font-sm"></p>

            <? if ($lot->status !== Lot::STATUS_ARCHIVED): ?>
                <a <?= (Yii::$app->user->isGuest) ? 'href="#loginFormTabInModal-login" data-toggle="modal" data-target="#loginFormTabInModal" data-backdrop="static" data-keyboard="false"' : 'href="#lotFormTabInModal-service" data-toggle="modal" data-target="#lotFormTabInModal" data-backdrop="static" data-keyboard="false"' ?>
                        class="btn btn-primary btn-block">Подать заявку</a>
            <? endif ?>

        </div>

        <div class="box-bottom bg-light">
            <h6 class="font-sm">Техническая поддержка пользователей</h6>
            <p class="font-sm">Мы ответим на все вопросы по данному лоту: <br><a href="tel:8(800)600-33-05"
                                                                                 class="text-primary">8-800-600-33-05</a>.
            </p>
            <p><a href="#buyLotModal" class="font-sm" data-toggle="modal" data-target="#buyLotModal"
                  data-backdrop="static" data-keyboard="false">Как самостоятельно приобрести этот лот</a></p>
        </div>

    </div>

</aside>