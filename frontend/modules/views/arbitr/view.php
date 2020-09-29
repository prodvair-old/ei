<?php

use common\models\db\Casefile;
use common\models\db\Lot;
use common\models\db\Manager;
use frontend\modules\components\LotBlockSmall;
use yii\widgets\Breadcrumbs;
use yii\helpers\Url;

/** @var $model Manager */
/** @var $lots Lot[] */
/** @var $caseCount Casefile count */

$this->title = $model->getFullName();
$this->params[ 'breadcrumbs' ] = Yii::$app->params[ 'breadcrumbs' ];
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
                            <span class="text-muted h3 mb-20 d-block">Арбитражный управляющий</span>
                            <?php if ($model->arbitrator) : ?>
                                <span class="elegent-icon-check_alt2 text-green" data-toggle="tooltip"
                                      title="Арбитражный управляющий верифицирован"></span>
                            <?php endif; ?>
                            <h1 class="h3 lh-h1 mt-2"><?= $this->title ?></h1>
                            <hr>
                            <div class="d-flex flex-column flex-sm-row align-items-sm-center mb-20">
                                <div class="mr-15 font-lg">
                                    <?= $model->id ?>
                                </div>
                                <div class="mr-15 text-muted">|</div>
                                <div class="mr-15 rating-item rating-inline">
                                    <p class="rating-text font400 text-muted font-12 letter-spacing-1"><?= $model->reg_number ?> </p>
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
                                            <h6><span class="font400">Рег.номер  </span><?= $model->reg_number ?>
                                            </h6>
                                        </li>

                                        <li>
                                            <span class="icon-font"><i class="elegent-icon-check_alt2 text-primary"></i> </span>
                                            <h6><span class="font400">ИНН </span><?= $model->profileRel->inn ?></h6>
                                        </li>

                                        <li>
                                            <span class="icon-font"><i class="elegent-icon-check_alt2 text-primary"></i> </span>
                                            <h6><span class="font400">ОГРН </span><?= $model->sro->ogrn ?></h6>
                                        </li>

                                        <li>
                                            <span class="icon-font"><i class="elegent-icon-check_alt2 text-primary"></i> </span>
                                            <h6><span class="font400">Адрес </span><?= $model->placeRel->address ?></h6>
                                        </li>

                                        <li>
                                            <span class="icon-font"><i class="elegent-icon-check_alt2 text-primary"></i> </span>
                                            <h6><span class="font400">СРО </span> <a
                                                        href="<?= Url::to(['/sro']) ?>/<?= $model->sro->parent_id ?>"
                                                        target="_blank"><?= $model->sro->title ?></a></h6>
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
                                            <span class="icon-font"><i class="elegent-icon-check_alt2 text-primary"></i> </span>
                                            <h6><span class="font400">Дел в управлении </span><?= $caseCount ?></h6>
                                        </li>

                                        <li>
                                            <span class="icon-font"><i class="elegent-icon-check_alt2 text-primary"></i> </span>
                                            <h6>
                                                <span class="font400">Количество опубликованных лотов </span><?= $lotsCount ?>
                                            </h6>
                                        </li>

                                    </ul>

                                    <div class="mb-50"></div>

                                </div>
                            </div>
                        </div>

                        <?php if ($lots) : ?>

                            <div id="other-lot" class="fullwidth-horizon--section">

                                <h4 class="heading-title">Лоты <span class="font400">Арбитражного управляющего</span>
                                </h4>

                                <div class="row">

                                    <?php foreach ($lots as $item) { ?>
                                        <div class="col-lg-3 col-sm-6 mb-40" itemscope itemtype="http://schema.org/Product">
                                            <?= LotBlockSmall::widget(['lot' => $item]); ?>
                                        </div>
                                    <? } ?>

                                </div>

                                <div class="mb-50"></div>

                            </div>
                        <? endif; ?>

                    </div>

                </div>

            </div>

        </div>

    </section>

<?php
$this->registerJsFile('js/custom-multiply-.js', $options = ['position' => yii\web\View::POS_END], $key = 'custom-multiply-');
$this->registerJsFile('js/custom-core.js', $options = ['position' => yii\web\View::POS_END], $key = 'custom-core');
?>