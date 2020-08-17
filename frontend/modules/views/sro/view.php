<?php

use common\models\db\Casefile;
use common\models\db\Lot;
use common\models\db\Manager;
use common\models\db\Sro;
use yii\widgets\Breadcrumbs;
use frontend\modules\components\ArbitrBlock;

/** @var $model Sro */
/** @var $lots Lot[] */
/** @var $arbitrs Manager[] */
/** @var $arbitrCount Manager count */

$this->title = $model->organizationRel->title;
$this->params[ 'breadcrumbs' ] = Yii::$app->params[ 'breadcrumbs' ];
$this->registerJsVar('sroId', $model->organizationRel->parent_id , $position = yii\web\View::POS_BEGIN);
?>

    <section class="page-wrapper page-detail">

        <div class="page-title bg-light d-none d-sm-block">

            <div class="container">

                <div class="row gap-15 align-items-center">

                    <div class="col-12">

                        <nav aria-label="breadcrumb">
                            <?= Breadcrumbs::widget([
                                'itemTemplate'       => '<li class="breadcrumb-item">{link}</li>',
                                'encodeLabels'       => false,
                                'tag'                => 'ol',
                                'activeItemTemplate' => '<li class="breadcrumb-item active" aria-current="page">{link}</li>',
                                'homeLink'           => ['label' => '<i class="fas fa-home"></i>', 'url' => '/'],
                                'links'              => isset($this->params[ 'breadcrumbs' ]) ? $this->params[ 'breadcrumbs' ] : [],
                            ]) ?>
                        </nav>

                    </div>

                </div>

            </div>

        </div>

        <div class="container pt-30">

            <div class="row gap-20 gap-lg-40">

                <div class="col-12">

                    <div class="content-wrapper">

                        <div id="desc" class="detail-header mb-30">
                            <h1 class="h3 lh-h1 mt-2"><?= $this->title ?></h1>

                            <div class="d-flex flex-column flex-sm-row align-items-sm-center mb-20">
                                <div class="mr-15 font-lg">
                                    <?= $model->organizationRel->parent_id ?>
                                </div>
                                <div class="mr-15 text-muted">|</div>
                                <div class="mr-15 rating-item rating-inline">
                                    <p class="rating-text font400 text-muted font-12 letter-spacing-1"><?= $model->organizationRel->reg_number ?> </p>
                                </div>
                            </div>

                        </div>

                        <div class="row">

                            <div class="col-md-7">
                                <div id="info" class="fullwidth-horizon--section">

                                    <h4 class="heading-title">Информация</h4>

                                    <ul class="list-icon-absolute what-included-list mb-30">

                                        <li>
                                            <span class="icon-font"><i class="elegent-icon-check_alt2 text-primary"></i> </span>
                                            <h6>
                                                <span class="font400">Рег.номер  </span><?= $model->organizationRel->reg_number ?>
                                            </h6>
                                        </li>

                                        <li>
                                            <span class="icon-font"><i class="elegent-icon-check_alt2 text-primary"></i> </span>
                                            <h6><span class="font400">ИНН </span><?= $model->organizationRel->inn ?>
                                            </h6>
                                        </li>

                                        <li>
                                            <span class="icon-font"><i class="elegent-icon-check_alt2 text-primary"></i> </span>
                                            <h6><span class="font400">ОГРН </span><?= $model->organizationRel->ogrn ?>
                                            </h6>
                                        </li>

                                        <li>
                                            <span class="icon-font"><i class="elegent-icon-check_alt2 text-primary"></i> </span>
                                            <h6><span class="font400">Адрес </span><?= $model->place->address ?></h6>
                                        </li>

                                    </ul>

                                    <div class="mb-50"></div>

                                </div>

                                <div class="slider__item green slider__item-full">
                                    <div class="slider__item__title">Вы арбитражный управляющий?<br> — Получите CRM арбитражника!</div>
                                    <p class="slider__item__text mb-0">Вам будет доступно:</p>
                                    <ul class="slider__item__list">
                                        <li><i class="fa fa-check pr-5"></i>Управление вашими лотами</li>
                                        <li><i class="fa fa-check pr-5"></i>Просмотр статистики и аналитики</li>
                                        <li><i class="fa fa-check pr-5"></i>Добавление отчетов</li>
                                        <li><i class="fa fa-check pr-5"></i>Работа с заявками</li>
                                        <li><i class="fa fa-check pr-5"></i>Управление личными данными</li>
                                    </ul>
                                    <div class="mt-30"></div>
                                    <a href="/contact" class="slider__item__link">
                                        Получить верификацию
                                        <i class="ion-ios-arrow-forward"></i>
                                    </a>
                                    <img src="/uploads/site/4.png" alt="">
                                </div>
                            </div>

                            <div class="col-md-5">
                                <div id="stat" class="fullwidth-horizon--section">

                                    <h4 class="heading-title">Статистика</h4>

                                    <ul class="list-icon-absolute what-included-list mb-30">

                                        <li>
                                            <span class="icon-font">
                                                <i class="elegent-icon-check_alt2 text-primary"></i>
                                            </span>
                                            <h6 id="sro_case_count">
                                                <span class="font400">Дел в управлении </span>
                                            </h6>
                                        </li>
                                        <li>
                                            <span class="icon-font">
                                                <i class="elegent-icon-check_alt2 text-primary"></i>
                                            </span>
                                            <h6 id="sro_lot_count">
                                                <span class="font400">Лотов по банкротному имуществу </span>
                                            </h6>
                                        </li>
                                        <li>
                                            <span class="icon-font"><i class="elegent-icon-check_alt2 text-primary"></i> </span>
                                            <h6>
                                                <span class="font400">Количество арбитражных управляющих </span><?= $arbitrCount ?>
                                            </h6>
                                        </li>

                                    </ul>

                                    <div class="mb-50"></div>

                                </div>
                            </div>
                        </div>


                        <? if ($arbitrs) { ?>

                            <div id="other-lot" class="fullwidth-horizon--section">

                                <h4 class="heading-title">Список арбитражных управляющих <span class="font400">членов СРО</span></h4>

                                <div class="row equal-height cols-1 cols-sm-2 cols-lg-3 gap-30 mb-25">

                                    <? foreach ($arbitrs as $arbitr) {
                                        echo arbitrBlock::widget(['arbitr' => $arbitr]);
                                    } ?>

                                </div>

                                <div class="mb-50"></div>

                            </div>
                        <? } ?>

                    </div>

                </div>

            </div>

        </div>

    </section>

<?php
$this->registerJsFile('js/custom-multiply-.js', $options = ['position' => yii\web\View::POS_END], $key = 'custom-multiply-');
$this->registerJsFile('js/custom-core.js', $options = ['position' => yii\web\View::POS_END], $key = 'custom-core');
?>

<script>
    document.addEventListener("DOMContentLoaded", function () {

        /**
         * @var sroId taken from php
         */

        let url = '/sro/get-case-count?id=' + sroId;

        $.ajax({
            type: "GET",
            url: url,
            success: function (data) {

                $('#sro_case_count').append(data);
                console.log('load success');
            },
            error: function (result) {
                console.log('load error');
                console.log(result);
            }
        });

        url = '/sro/get-lot-count?id=' + sroId

        $.ajax({
            type: "GET",
            url: url,
            success: function (data) {

                $('#sro_lot_count').append(data);
                console.log('load success');
            },
            error: function (result) {
                console.log('load error');
                console.log(result);
            }
        });
    });
</script>
