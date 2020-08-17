<?php

use common\models\db\Lot;
use common\models\db\Torg;
use frontend\modules\components\ReportWidget;
use yii\widgets\Breadcrumbs;
use yii\helpers\Html;
use yii\helpers\Url;
use frontend\components\NumberWords;
use frontend\modules\components\LotDetailSidebar;
use frontend\modules\components\LotBlockSmall;
use frontend\modules\components\ServiceLotFormWidget;
use frontend\models\UserAccess;
use ymaker\social\share\widgets\SocialShare;
use common\models\db\WishList;
use frontend\modules\components\SliderServices;

/* @var $lot Lot */
/* @var $type */

$dateSend = floor(($lot->torg->end_at - time()) / (60 * 60 * 24));
$otherLots = null;
$wishListCheck = WishList::find()->where(['lot_id' => $lot->id, 'user_id' => \Yii::$app->user->id])->one();
$wishListAll = WishList::find()->where(['lot_id' => $lot->id])->count();

$this->registerJsVar('lotType', $lot->torg->property, $position = yii\web\View::POS_HEAD);
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

        <?= (!empty($lot->archive)) ? ($lot->archive) ? '<span class="h3 text-primary">Архив</span><hr>' : '' : '' ?>

        <div class="row">
            <div class="col-lg-8 col-12">
                <div class="d-flex justify-content-between bg-gray pt-15 pb-15 pl-15 pr-15 borr-20 mb-20">
                    <div class="d-flex">
                        <div class="mr-30">
                            <?= ($lot->getTraces()->count() > 0) ? $lot->getTraces()->count() : '' ?> <i class="far fa-eye fa-sm ml-5"></i>
                        </div>
                        <?= SocialShare::widget([
                            'configurator' => 'socialShare',
                            'url'          => ('https://ei.ru' . Yii::$app->request->url),
                            'title'        => 'Посмотри лот на ei.ru: ' . Yii::$app->params[ 'h1' ]
                        ]); ?>
                    </div>
                    <div class="mr-10 rating-item rating-inline">
                        <a title="Добавить в избранные"
                            <?= (Yii::$app->user->isGuest) ? 'href="#loginFormTabInModal-login" class="wish-star  d-flex" data-toggle="modal" data-target="#loginFormTabInModal" data-backdrop="static" data-keyboard="false"' : 'href="#" class="wish-js wish-star d-flex" data-id="' . $lot->id . '"' ?>>
                            <span class="pr-5"> <?=($wishListAll > 0)? $wishListAll : ''?></span>
                            <img src="img/star<?= ($wishListCheck->lot_id) ? '' : '-o' ?>.svg" alt="">
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-12">
                <ul class="mb-20 pl-10">
                    <li class="clearfix">№ <?= $lot->id ?></li>
                    <li class="clearfix">Опубликован: <?= Yii::$app->formatter->asDate($lot->torg->published_at, 'long') ?></li>
                </ul>
            </div>
        </div>

        <div class="row gap-20 gap-lg-40" itemscope itemtype="http://schema.org/Product">

            <div class="col-12 col-lg-8">

                <div class="content-wrapper">

                    <div id="desc" class="detail-header mb-30">
                        <div class="lot__block__info__content__offer"><?= $lot->region->name ?></div>
                        <h1 class="h3 lh-h1 mt-5" itemprop="name"><?= $lot->title ?></h1>

                        <div class="d-flex flex-row align-items-sm-center mb-20">
                            <?
                                if (!Yii::$app->user->isGuest) {
                                    if (Yii::$app->user->identity->role !== 'user' && UserAccess::forManager('lots', 'edit')) {
                                        ?>
                            <div class="mr-10 text-muted">|</div>
                            <div class="mr-10 rating-item rating-inline">
                                <a href="<?= Yii::$app->params[ 'backLink' ] . '/login?token=' . Yii::$app->user->identity->auth_key . '&link[to]=lots&link[page]=update&link[id]=' . $lot->id ?>"
                                    target="_blank">
                                    Редактировать
                                </a>
                            </div>
                            <? }
                                } ?>

                        </div>

                        <?php
                            $image = $lot->getImage('original');
                            if ($image) : ?>
                        <div class="fotorama mt-20 mb-40" data-allowfullscreen="true" data-nav="thumbs"
                            data-arrows="always" data-click="true">
                            <?php
                                    while ($image) {
                                        echo Html::img($image, ['alt' => 'Images']);
                                        $image = $lot->getNextImage('original');
                                    }
                                    ?>
                        </div>
                        <?php endif; ?>

                            <ul class="list-inline-block highlight-list mt-30">
                                <li>
                                <span class="icon-font d-block">
                                    <i class="linea-icon-basic-flag1 text-green"></i>
                                </span>
                                    Старт
                                    торгов<br/><strong><?= Yii::$app->formatter->asDate($lot->torg->started_at, 'long') ?></strong>
                                </li>
                                <li>
                                <span class="icon-font d-block">
                                    <i class="ri-arrow-right"></i>
                                </span>
                                </br>
                                    <strong class="text-green"><?= ($dateSend > 0) ? NumberWords::widget(['number' => $dateSend, 'words' => ['день', 'дня', 'дней']]) : 'Прошло' ?></strong>
                                </li>
                                <li>
                                <span class="icon-font d-block">
                                    <i class="linea-icon-basic-flag2 text-danger"></i>
                                </span>
                                Окончание
                                торгов<br /><strong><?= Yii::$app->formatter->asDate($lot->torg->completed_at, 'long') ?></strong>
                            </li>
                            <li class="ml-50">
                                <span class="icon-font d-block">
                                    <i class="linea-icon-ecommerce-rublo"></i>
                                </span>
                                    Сумма
                                    задатка<br/><strong><?= ($lot->deposit_measure == 1) ? Yii::$app->formatter->asCurrency((($lot->start_price / 100) * $lot->deposit)) : Yii::$app->formatter->asCurrency($lot->deposit) ?></strong>
                                </li>
                            </ul>

                            <h4 class="mt-50 mb-5">Описание</h4>
                            <p class="long-text" itemprop="description"><?= $lot->description ?></p>
                            <a href="#desc" class="open-text-js">Подробнее</a>

                            <div class="mt-50"></div>
                            <? if ($lot->report) {
                                try {
                                    echo ReportWidget::widget(['reports' => $lot->report, 'lot' => $lot]);
                                } catch (\Exception $e) {
                                    echo (YII_ENV_PROD) ? 'Ошибка загрузки отчетов' : $e->getMessage();
                                }

                            } else { ?>

                            <div class="slider__item green slider__item-full">
                                <div class="slider__item__title">У этого лота пока нет отчета <br> Добавьте информацию и заработайте!</div>
                                <ul class="slider__item__list">
                                    <li><i class="fa fa-check pr-5"></i>Загрузите фотографии</li>
                                    <li><i class="fa fa-check pr-5"></i>Добавьте описание</li>
                                    <li><i class="fa fa-check pr-5"></i>Прикрепите документы</li>
                                    <li><i class="fa fa-check pr-5"></i>Назначьте цену </li>
                                </ul>
                                <p class="slider__item__text mb-0 mt-10">Получайте деньги с продажи отчета!</p>
                                <div class="mt-30"></div>
                                <a href="/contact" class="slider__item__link">
                                        Стать агентом ei
                                    <i class="ion-ios-arrow-forward"></i>
                                </a>
                                <img src="/uploads/site/3.png" alt="">
                            </div>

                            <? } ?>

                        <!-- </div> -->
                    </div>

                    <div class="sidebar-mobile mb-50">
                        <?= LotDetailSidebar::widget(['lot' => $lot]) ?>
                    </div>

                    <div class="mt-50"></div>
                    <div id="info" class="fullwidth-horizon--section">

                        <h4 class="heading-title">Информация о лоте</h4>

                        <ul class="list-icon-absolute what-included-list row">
                        <? if ($lot->torg->property === Torg::PROPERTY_BANKRUPT) { ?>
                            <li class="col-12 col-md-6 pl-15 pr-15">
                                <figure class="tour-grid-item-01 box-shadow borr-10">

                                    <a href="<?= ($lot->torg->bankruptProfile->id) ? Url::to(['/bankrupt']).'/'.$lot->torg->bankruptProfile->id : Url::to(['/bankrupt']).'/'.$lot->torg->bankrupt->id ?>">
                                            
                                        <figcaption class="content">
                                            <div class="lot__block__info__content__offer">Должник</div>
                                            <?php if ($lot->torg->bankruptProfile->id) : ?>
                                                <h5><?=$lot->torg->bankruptProfile->getFullName()?></h5>
                                                <ul class="item-meta mt-10 pl-0">
                                                    <li class="pl-0">
                                                        <span class="font500">ИНН</span> <?=$lot->torg->bankruptProfile->inn?>
                                                    </li>
                                                </ul>
                                            <?php else: ?>
                                                <h5><?=$lot->torg->bankrupt->title?></h5>
                                                <ul class="item-meta mt-10 pl-0">
                                                    <li class="pl-0">
                                                        <span class="font500">ИНН</span> <?=$lot->torg->bankrupt->inn?>
                                                    </li>
                                                </ul>
                                            <?php endif; ?>
                                            <small class="text-green font600">Cмотреть профиль <i class="fa fa-arrow-right"></i></small>
                                        </figcaption>
                                    
                                    </a>
                                    
                                </figure>
                               
                            </li>
                            <? } ?>

                            <? if ($lot->torg->manager !== null && $lot->torg->property == Torg::PROPERTY_BANKRUPT) { ?>
                            <li class="col-12 col-md-6 mt-0 pl-15 pr-15">
                                <figure class="tour-grid-item-01 box-shadow borr-10 <?= ($lot->torg->manager->arbitrator)? 'bg-green' : ''?>">

                                    <a href="<?= Url::to(['/arbitr']) ?>/<?= $lot->torg->manager->id ?>">

                                        <figcaption class="content">
                                            <div class="lot__block__info__content__offer">Арбитражный управляющий</div>
                                            <h5>
                                                <?php if ($arbitr->arbitrator) : ?>
                                                    <span class="elegent-icon-check_alt2 text-green" data-toggle="tooltip"
                                                        title="Арбитражный управляющий верифицирован"></span>
                                                <?php endif; ?>
                                                <?= $lot->torg->manager->profile->getFullName() ?>
                                            </h5>

                                            <ul class="item-meta mt-10 pl-0">
                                                <li class="pl-0">
                                                    <i class="elegent-icon-pin_alt text-warning"></i> <?= $lot->torg->manager->placeRel->address ?>
                                                </li>
                                            </ul>
                                            <small class="text-green font600">Cмотреть профиль <i class="fa fa-arrow-right"></i></small>
                                        </figcaption>

                                    </a>

                                </figure>
                            </li>
                            <? } ?>

                            <li class="col-12">
                                <span class="icon-font"><i class="elegent-icon-check_alt2 text-primary"></i> </span>
                                <h6 class="mt-0">Категории лота</h6>
                                <ul class="ul">
                                    <?php foreach ($lot->categories as $category) { ?>
                                    <li itemprop="category"><?= $category->name ?></li>
                                    <? } ?>
                                </ul>
                            </li>

                            <? if ($lot->getInfo()[ 'vin' ]) { ?>
                            <li class="col-12">
                                <span class="icon-font"><i class="elegent-icon-check_alt2 text-primary"></i> </span>
                                <h6 class="mt-0">VIN номер</h6>
                                <p itemprop="mpn"><?= $lot->getInfo()[ 'vin' ] ?></p>
                                <a href="https://avtocod.ru/proverkaavto/<?= $lot->getInfo()[ 'vin' ] ?>?rd=VIN&a_aid=zhukoffed"
                                    class="btn btn-success btn-sm mt-2" style="max-width: 120px" target="_blank" rel="nofollow">Проверить
                                    Автомобиль</a>
                            </li>
                            <? } ?>

                            <? if ($lot->torg->casefile !== null) { ?>
                            <li class="col-12">
                                <span class="icon-font"><i class="elegent-icon-check_alt2 text-primary"></i> </span>
                                <h6 class="mt-0">Сведения о деле</h6>
                                <ul class="ul">
                                    <li>Номер дела: <span
                                            class="text-list-name"><?= $lot->torg->casefile->reg_number ?></span>
                                    </li>
                                    <li>Арбитражный суд: <span class="text-list-name"><a
                                                href="<?= Url::to(['/sro']) ?>/<?= $lot->torg->manager->sro->parent_id ?>"
                                                target="_blank"><?= $lot->torg->manager->sro->title ?></a></span>
                                    </li>
                                    <!--                                            <li>Адрес суда: <span-->
                                    <!--                                                        class="text-list-name">-->
                                    <? //= $lot->torg->manager->sro->place->address ?>
                                    <!--</span>-->
                                    <!--                                            </li>-->
                                </ul>
                            </li>
                            <? } ?>

                            <? if ($lot->torg->owner !== null) { ?>
                            <li class="col-12">
                                <span class="icon-font"><i class="elegent-icon-check_alt2 text-primary"></i> </span>
                                <h6 class="mt-0">Банк</h6>
                                <ul class="ul">
                                    <li>
                                        <a href="/<?= $lot->torg->owner->website ?>"><?= $lot->torg->owner->title ?></a>
                                    </li>
                                    <li>E-mail: <span class="text-list-name"><?= $lot->torg->owner->email ?></span>
                                    </li>
                                    <li>ИНН: <span class="text-list-name"><?= $lot->torg->owner->inn ?></span>
                                    </li>
                                    <li>ОГРН: <span class="text-list-name"><?= $lot->torg->owner->ogrn ?></span>
                                    </li>
                                    <!--                                            <li>Адрес: <span-->
                                    <!--                                                        class="text-list-name">-->
                                    <? //= $lot->torg->owner->place->address ?>
                                    <!--</span>-->
                                    <!--                                            </li>-->
                                </ul>
                            </li>
                            <? } ?>

                        </ul>

                        <div class="mb-50"></div>

                    </div>

                    <?php $prices = $lot->prices; ?>
                    <?php if ($lot->torg->offer == Torg::OFFER_PUBLIC && $prices) : ?>

                    <div id="price-history" class="fullwidth-horizon-sticky-section">

                        <h5 class="heading-title">Этапы снижения цены</h5>

                        <div class="mb-20"></div>

                        <div class="item-text-long-wrapper">

                            <div class="item-heading text-muted">

                                <div class="row d-none d-sm-flex">

                                    <div class="col-12 col-sm-6">

                                        <div class="col-inner">

                                            <div class="row gap-10">

                                                <div class="col-5">
                                                    Начало периода
                                                </div>

                                                <div class="col-2">

                                                </div>

                                                <div class="col-5">
                                                    Окончание периода
                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                    <div class="col-12 col-sm-6">

                                        <div class="col-inner">

                                            <div class="row gap-10">

                                                <div class="col-6 text-center">
                                                    Текущая цена, руб.
                                                </div>

                                                <div class="col-6 text-right">
                                                    Размер задатка, руб.
                                                </div>

                                            </div>

                                        </div>

                                    </div>


                                </div>

                            </div>

                            <?php foreach ($prices as $key => $value) : ?>
                            <?php $date = time(); ?>
                            <div
                                class="item-text-long <?= (($value->started_at <= $date) && $value->end_at >= $date) ? '' : 'sold-out' ?>">

                                <div class="row align-items-center">

                                    <div class="col-12 col-sm-6">

                                        <div class="col-inner mb-10 mb-sm-0">

                                            <div class="row gap-10 align-items-center">

                                                <div class="col-6">
                                                    <span class="font-sm">Начало</span>
                                                    <strong
                                                        class="d-block"><?= Yii::$app->formatter->asDate($value->started_at, 'long') ?></strong>
                                                </div>

                                                <div class="col-6 text-right text-sm-left">
                                                    <span class="font-sm">Конец</span>
                                                    <strong
                                                        class="d-block"><?= Yii::$app->formatter->asDate($value->end_at, 'long') ?></strong>
                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                    <div class="col-12 col-sm-6">

                                        <div class="col-inner">

                                            <div class="row gap-10 align-items-center">

                                                <div class="col-6 text-left text-sm-center">
                                                    <span class="font-sm">Цена </span>
                                                    <strong
                                                        class="d-block"><?= Yii::$app->formatter->asCurrency($value->price) ?></strong>
                                                </div>

                                                <div class="col-6 text-left  text-sm-right">
                                                    <span class="font-sm">Задаток</span>
                                                    <strong
                                                        class="d-block"><?= ($lot->deposit_measure == 1) ? Yii::$app->formatter->asCurrency((($value->price / 100) * $lot->deposit)) : Yii::$app->formatter->asCurrency($lot->deposit) ?></strong>
                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="mb-50"></div>

                    </div>

                    <? endif; ?>

                    <? if ($lot->torg->casefile) { ?>
                    <? if ($lot->torg->casefile->documents) { ?>

                    <div id="docs" class="fullwidth-horizon--section">
                        <h5 class="heading-title">Документы по делу должника</h5>
                        <ul class="list-icon-absolute what-included-list mb-30 long-text">
                            <? foreach ($lot->torg->casefile->documents as $document) { ?>

                            <?
                                            switch ($document->ext) {
                                                case 'docs':
                                                case 'doc':
                                                    $icon = '<i class="far fa-file-word"></i>';
                                                    break;
                                                case 'xlsx':
                                                case 'xls':
                                                    $icon = '<i class="far fa-file-excel"></i>';
                                                    break;
                                                case 'pdf':
                                                    $icon = '<i class="far fa-file-pdf"></i>';
                                                    break;
                                                case 'zip':
                                                    $icon = '<i class="far fa-file-archive"></i>';
                                                    break;
                                                default:
                                                    $icon = '<i class="far fa-file"></i>';
                                                    break;
                                            }
                                            ?>
                            <li>
                                <span class="icon-font"><?= $icon ?></span>
                                <a href="<?= $document->url ?>" target="_blank"><?= $document->name ?></a>
                            </li>

                            <? } ?>
                        </ul>

                        <a href="#docs" class="open-text-js">Все документы</a>
                        <div class="mb-50"></div>
                    </div>

                    <? } ?>
                    <? } ?>

                    <? if ($lot->torg->documents) { ?>
                    <div id="docs" class="fullwidth-horizon--section">
                        <h5 class="heading-title">Документы по торгу</h5>

                        <ul class="list-icon-absolute what-included-list mb-30 long-text">
                            <? foreach ($lot->torg->documents as $document) { ?>

                            <?
                                        switch ($document->ext) {
                                            case 'docs':
                                            case 'doc':
                                                $icon = '<i class="far fa-file-word"></i>';
                                                break;
                                            case 'xlsx':
                                            case 'xls':
                                                $icon = '<i class="far fa-file-excel"></i>';
                                                break;
                                            case 'pdf':
                                                $icon = '<i class="far fa-file-pdf"></i>';
                                                break;
                                            case 'zip':
                                                $icon = '<i class="far fa-file-archive"></i>';
                                                break;
                                            default:
                                                $icon = '<i class="far fa-file"></i>';
                                                break;
                                        }
                                        ?>
                            <li>
                                <span class="icon-font"><?= $icon ?></span>
                                <a href="<?= $document->url ?>" target="_blank"><?= $document->name ?></a>
                            </li>

                            <? } ?>
                        </ul>

                        <a href="#docs" class="open-text-js">Все документы</a>
                        <div class="mb-50"></div>
                    </div>
                    <? } ?>

                    <? if ($lot->documents) { ?>
                    <div id="docs" class="fullwidth-horizon--section">
                        <h5 class="heading-title">Документы по лоту</h5>

                        <ul class="list-icon-absolute what-included-list mb-30 long-text">
                            <? foreach ($lot->documents as $document) { ?>

                            <?
                                        switch ($document->ext) {
                                            case 'docs':
                                            case 'doc':
                                                $icon = '<i class="far fa-file-word"></i>';
                                                break;
                                            case 'xls':
                                                $icon = '<i class="far fa-file-excel"></i>';
                                                break;
                                            case 'xlsx':
                                                $icon = '<i class="far fa-file-excel"></i>';
                                                break;
                                            case 'pdf':
                                                $icon = '<i class="far fa-file-pdf"></i>';
                                                break;
                                            case 'zip':
                                                $icon = '<i class="far fa-file-archive"></i>';
                                                break;
                                            default:
                                                $icon = '<i class="far fa-file"></i>';
                                                break;
                                        }
                                        ?>
                            <li>
                                <span class="icon-font"><?= $icon ?></span>
                                <a href="<?= $document->url ?>" target="_blank"><?= $document->name ?></a>
                            </li>

                            <? } ?>
                        </ul>

                        <a href="#docs" class="open-text-js">Все документы</a>
                        <div class="mb-50"></div>
                    </div>
                    <? } ?>

                    <?php $otherLots = $lot->torg->getOtherLots($lot->id, 2); ?>

                    <? if ($otherLots) { ?>
                    <div id="other-lot" class="fullwidth-horizon--section">

                        <h5 class="heading-title">Другие лоты должника</h5>

                        <div class="row">

                            <? foreach ($otherLots as $otherLot) { ?>
                                <div class="col-lg-4 col-sm-6 mb-30" itemscope itemtype="http://schema.org/Product">
                                    <?= LotBlockSmall::widget(['lot' => $otherLot, 'url' => $url]) ?>
                                </div>
                            <? } ?>

                            <div class="col-lg-4 col-sm-6 mb-30 lot_next__btn">
                                <a href="<?= ($lot->torg->bankruptProfile->id)? Url::to(['/bankrupt']).'/'.$lot->torg->bankruptProfile->id : Url::to(['/bankrupt']).'/'.$lot->torg->bankrupt->id ?>" class="btn btn-primary borr-10">
                                    Другие лоты
                                    <i class="fa fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>

                        <div class="mb-50"></div>

                    </div>
                    <? } ?>

                    <? if ($lot->torg->description) { ?>
                    <div id="torg" class="detail-header mb-30">
                        <h5 class="mt-30">Информация о торге</h5>
                        <p class="long-text"><?= $lot->torg->description ?></p>
                        <a href="#torg" class="open-text-js">Подробнее</a>
                        <div class="mb-50"></div>
                    </div>
                    <? } ?>

                    <div class="box-content text-muted">
                        <ul class="pt-15">
                            <li class="clearfix"> Обнавлено: <?= Yii::$app->formatter->asDatetime($lot->status_changed_at) ?></li>
                        </ul>
                    </div>

                </div>

            </div>

            <div class="col-12 col-lg-4">

                <div class="sidebar-desktop border-dots borr-20">
                    <?= LotDetailSidebar::widget(['lot' => $lot]) ?>
                </div>

            </div>

        </div>

    </div>

</section>


<!-- start lot form modal -->
<div class="modal fade modal-with-tabs form-login-modal" id="lotFormTabInModal" tabindex="-1" role="dialog"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content shadow-lg">
            <?= ServiceLotFormWidget::widget(['lot' => $lot, 'lotType' => $type]) ?>
        </div>
    </div>
</div>
<!-- end lot form modal -->

<?php
$this->registerJsFile('js/custom-multiply-sticky.js', $options = ['position' => yii\web\View::POS_END], $key = 'custom-multiply-');
?>