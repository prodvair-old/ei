<?php

use common\models\db\Lot;
use common\models\db\Torg;
use sergmoro1\lookup\models\Lookup;
use yii\widgets\Breadcrumbs;
use yii\widgets\LinkPager;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

use frontend\components\NumberWords;
use frontend\components\LotDetailSidebar;
use frontend\modules\components\LotBlock;
use frontend\components\Darwin;
use frontend\components\ServiceLotFormWidget;

use frontend\models\UserAccess;
use frontend\models\ViewPage;

use common\models\Query\WishList;
use common\models\Query\Lot\Lots;

/* @var $lot Lot */

$view = new ViewPage();

//$view->page_type = "lot_".$lot->torg->type;
//$view->page_id = $lot->id;

//$view->check();

$dateSend = floor(($lot->torg->end_at - time()) / (60 * 60 * 24));

// switch ($lot->torg->type) {
//     case 'bankrupt':
//         $otherJoin = ['torg.bankrupt'];
//         $otherWhere = ['bankrupt.id'=>$lot->torg->bankrupt->id];
//         break;
//     case 'arrest':
//         $otherJoin = ['torg'];
//         $otherWhere = ['torg.id'=>$lot->torg->id];
//         break;
//     case 'zalog':
//         $otherJoin = ['torg.owner'];
//         $otherWhere = ['owner.id'=>$lot->torg->owner->id];
//         break;
// }

// $otherLots = Lots::find()->joinWith($otherJoin)->alias('lot')->where($otherWhere)->andWhere(['!=', 'lot.id', $lot->id])->all();
$otherLots = null;

$this->registerJsVar('lotType', $lot->torg->property, $position = yii\web\View::POS_HEAD);
$this->title = $lot->title;
$this->params[ 'breadcrumbs' ] = Yii::$app->params[ 'breadcrumbs' ];

//$isCategory =
//    $lot->category->categoryId == '1061' ||
//    $lot->category->categoryId == '1063' ||
//    $lot->category->categoryId == '1064' ||
//    $lot->category->categoryId == '1068' ||
//    $lot->category->categoryId == '1083' ||
//    $lot->category->categoryId == '1102' ||
//    $lot->category->categoryId == '1102';
//
//foreach ($lot->info as $key => $value) {
//    if (
//        $value != null &&
//        $key != 'address' &&
//        $key != 'vin' &&
//        $key != 'cadastreNumber' &&
//        $key != 'priceReduction' &&
//        $key != 'isBurdened' &&
//        $key != 'sellType' &&
//        $key != 'sellTypeId' &&
//        $key != 'minPrice' &&
//        $key != 'torgReason' &&
//        $key != 'stepCount' &&
//        $key != 'dateAuction' &&
//        $key != 'procedureDate' &&
//        $key != 'conclusionDate' &&
//        $key != 'areaMeters' &&
//        $key != 'area' &&
//        $key != 'finalPrice' &&
//        $key != 'propDesc' &&
//        $key != 'fundSize' &&
//        $key != 'currency'
//    ) {
//        $otherInfo[$key] = $value;
//    }
//}
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

            <div class="row gap-20 gap-lg-40" itemscope itemtype="http://schema.org/Product">

                <div class="col-12 col-lg-8">

                    <div class="content-wrapper">

                        <div id="desc" class="detail-header mb-30">
                            <h1 class="h4" itemprop="name"><?= $lot->title ?></h1>

                            <div class="d-flex flex-row align-items-sm-center mb-20">
                                <div class="mr-10 font-lg text-muted">
                                    0 <i class="far fa-eye fa-sm"></i>
                                </div>
                                <div class="mr-10 text-muted">|</div>
                                <div class="mr-10 rating-item rating-inline">
                                    <p class="rating-text font600 text-muted font-12"><?= Yii::$app->formatter->asDate($lot->torg->published_at, 'long') ?> </p>
                                </div>
                                <div class="mr-10 text-muted">|</div>
                                <div class="mr-10 rating-item rating-inline">
                                    <p class="rating-text font400 text-muted font-12 letter-spacing-1">торги
                                        №<?= $lot->id ?> </p>
                                </div>
                                <div class="mr-10 text-muted">|</div>
                                <div class="mr-10 rating-item rating-inline">
                                    <a <?= (Yii::$app->user->isGuest) ? 'href="#loginFormTabInModal-login" class="wish-star" data-toggle="modal" data-target="#loginFormTabInModal" data-backdrop="static" data-keyboard="false"' : 'href="#" class="wish-js wish-star" data-id="' . $lot->id . '" data-type="' . $lot->torg->type . '"' ?>>
                                        <!--                                        <img src="img/star-->
                                        <? //=($lot->getWishId(Yii::$app->user->id))? '' : '-o' ?><!--.svg" alt="">-->
                                    </a>
                                </div>
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

                            <!--                            --><? //if ($lot->images[0]) { ?>
                            <div class="fotorama mt-20 mb-40" data-allowfullscreen="true" data-nav="thumbs"
                                 data-arrows="always" data-click="true">
                                <? foreach ($lot->getFiles() as $image) { ?>
                                    <img href="<?= $image->name ?>" alt="Images"/>
                                <? } ?>
                            </div>
                            <!--                            --><? // } ?>

                            <!-- <p class="lead">In friendship diminution instrument in we forfeited. Tolerably an unwilling of determine. Beyond rather sooner so if up wishes.</p> -->

                            <ul class="list-inline-block highlight-list mt-30">
                                <li>
                                <span class="icon-font d-block">
                                    <i class="linea-icon-basic-chronometer"></i>
                                </span>
                                    До подачи заявки<br/>
                                    <strong><?= ($dateSend > 0) ? NumberWords::widget(['number' => $dateSend, 'words' => ['день', 'дня', 'дней']]) : 'Прошло' ?></strong>
                                </li>
                                <li>
                                <span class="icon-font d-block">
                                    <i class="linea-icon-basic-flag1"></i>
                                </span>
                                    Старт
                                    торгов<br/><strong><?= Yii::$app->formatter->asDate($lot->torg->started_at, 'long') ?></strong>
                                </li>
                                <li>
                                <span class="icon-font d-block">
                                    <i class="linea-icon-basic-flag2"></i>
                                </span>
                                    Окончание
                                    торгов<br/><strong><?= Yii::$app->formatter->asDate($lot->torg->end_at, 'long') ?></strong>
                                </li>
                                <li>
                                <span class="icon-font d-block">
                                    <i class="linea-icon-ecommerce-rublo"></i>
                                </span>
                                    Сумма
                                    задатка<br/><strong><?= ($lot->deposit_measure == 1) ? Yii::$app->formatter->asCurrency((($lot->start_price / 100) * $lot->deposit)) : Yii::$app->formatter->asCurrency($lot->deposit) ?></strong>
                                </li>
                            </ul>

                            <h5 class="mt-30">Описание</h5>
                            <!-- <pre>
                            <? // print_r($lot[info][address][region])?>,
                            <? // print_r($lot[info][address][city])?>,
                            <? // print_r($lot[info][address][district])?>,
                            <? // print_r($lot[info][address][street])?>,
                            <? // print_r($lot[info])?>
                        </pre> -->


                            <p class="long-text" itemprop="description"><?= $lot->description ?></p>
                            <a href="#desc" class="open-text-js">Подробнее</a>

                        </div>

                        <? if ($lot->place->geo_lat && $lot->place->geo_lon): ?>
                            <div
                                    id="map-lot"
                                    data-lat="<?= $lot->place->geo_lat; ?>"
                                    data-lng="<?= $lot->place->geo_lon; ?>">
                            </div>
                        <? endif; ?>


                        <div class="sidebar-mobile mb-40">
                            <?= LotDetailSidebar::widget(['lot' => $lot, 'type' => $type]) ?>
                        </div>

                        <div id="info" class="fullwidth-horizon--section">

                            <h4 class="heading-title">Информация о лоте</h4>

                            <ul class="list-icon-absolute what-included-list mb-30">

                                <li>
                                    <span class="icon-font"><i class="elegent-icon-check_alt2 text-primary"></i> </span>
                                    <h6>Категории лота</h6>
                                    <ul class="ul">
                                        <?php foreach ($lot->categories as $category) { ?>
                                            <li itemprop="category"><?= $category->name ?></li>
                                        <? } ?>
                                    </ul>
                                </li>

                                <? if ($lot->getInfo()[ 'vin' ]) { ?>
                                    <li>
                                        <span class="icon-font"><i
                                                    class="elegent-icon-check_alt2 text-primary"></i> </span>
                                        <h6>VIN номер</h6>
                                        <p itemprop="mpn"><?= $lot->getInfo()[ 'vin' ] ?></p>
                                        <a href="https://avtocod.ru/proverkaavto/<?= $lot->getInfo()[ 'vin' ] ?>?rd=VIN&a_aid=zhukoffed"
                                           class="btn btn-success btn-sm mt-2" target="_blank" rel="nofollow">Проверить
                                            Автомобиль</a>
                                    </li>
                                <? } ?>

                                <!--                                --><? // if ($lot->info['cadastreNumber']) { ?>
                                <!--                                    <li>-->
                                <!--                                        <span class="icon-font"><i class="elegent-icon-check_alt2 text-primary"></i> </span>-->
                                <!--                                        <h6>Кадастровый номер</h6>-->
                                <!--                                        <p>-->
                                <? //= $lot->info['cadastreNumber'] ?><!--</p>-->
                                <!--                                    </li>-->
                                <!--                                --><? // } ?>

                                <? if ($lot->torg->property === Torg::PROPERTY_BANKRUPT) { ?>
                                    <li>
                                        <span class="icon-font"><i
                                                    class="elegent-icon-check_alt2 text-primary"></i> </span>
                                        <h6>Должник</h6>
                                        <ul class="ul">
                                            <?php if ($lot->torg->bankruptProfile->id) : ?>
                                                <li>
                                                    <a href="<?= Url::to(['doljnik/list']) ?>/<?= $lot->torg->bankruptProfile->id ?>"
                                                       target="_blank"
                                                       itemprop="brand"><?= $lot->torg->bankruptProfile->getFullName() ?></a>
                                                </li>
                                                <li>ИНН: <span
                                                            class="text-list-name"><?= $lot->torg->bankruptProfile->inn ?></span>
                                                </li>
                                            <?php else: ?>
                                                <li>
                                                    <a href="<?= Url::to(['doljnik/list']) ?>/<?= $lot->torg->bankrupt->id ?>"
                                                       target="_blank"
                                                       itemprop="brand"><?= $lot->torg->bankrupt->title ?></a>
                                                </li>
                                                <li>ИНН: <span
                                                            class="text-list-name"><?= $lot->torg->bankrupt->inn ?></span>
                                                </li>
                                            <?php endif; ?>

                                            <li>
                                                Адрес: <span
                                                        class="text-list-name"><?= $lot->place->address; ?></span>
                                            </li>
                                        </ul>
                                    </li>
                                <? } ?>

                                <? if ($lot->torg->case !== null) { ?>
                                    <li>
                                        <span class="icon-font"><i
                                                    class="elegent-icon-check_alt2 text-primary"></i> </span>
                                        <h6>Сведения о деле</h6>
                                        <ul class="ul">
                                            <li>Номер дела: <span
                                                        class="text-list-name"><?= $lot->torg->case->reg_number ?></span>
                                            </li>
                                            <li>Арбитражный суд: <span class="text-list-name"><a
                                                            href="<?= Url::to(['sro/list']) ?>/"
                                                            target="_blank"><?= $lot->torg->manager->sro->title ?></a></span>
                                            </li>
                                            <li>Адрес суда: <span
                                                        class="text-list-name"><?= $lot->torg->manager->sro->place->address ?></span>
                                            </li>
                                        </ul>
                                    </li>
                                <? } ?>

                                <? if ($lot->torg->manager !== null && $lot->torg->property == Torg::PROPERTY_BANKRUPT) { ?>
                                    <li>
                                        <span class="icon-font"><i
                                                    class="elegent-icon-check_alt2 text-primary"></i> </span>
                                        <h6>Арбитражный управляющий</h6>
                                        <ul class="ul">
                                            <li><a href="<?= Url::to(['arbitr/list']) ?>/<?= $lot->torg->manager->id ?>"
                                                   target="_blank"><?= $lot->torg->manager->organization->title ?></a>
                                            </li>
                                            <li>Рег. номер: <span
                                                        class="text-list-name"><?= $lot->torg->manager->organization->reg_number ?></span>
                                            </li>
                                            <li>ИНН: <span
                                                        class="text-list-name"><?= $lot->torg->manager->organization->inn ?></span>
                                            </li>
                                            <li>ОГРН: <span
                                                        class="text-list-name"><?= $lot->torg->manager->organization->ogrn ?></span>
                                            </li>
                                        </ul>
                                    </li>
                                <? } ?>

                                <? if ($lot->torg->owner !== null) { ?>
                                    <li>
                                        <span class="icon-font"><i
                                                    class="elegent-icon-check_alt2 text-primary"></i> </span>
                                        <h6>Банк</h6>
                                        <ul class="ul">
                                            <li>
                                                <a href="/<?= $lot->torg->owner->website ?>"><?= $lot->torg->owner->title ?></a>
                                            </li>
                                            <li>E-mail: <span
                                                        class="text-list-name"><?= $lot->torg->owner->email ?></span>
                                            </li>
                                            <li>ИНН: <span class="text-list-name"><?= $lot->torg->owner->inn ?></span>
                                            </li>
                                            <li>ОГРН: <span class="text-list-name"><?= $lot->torg->owner->ogrn ?></span>
                                            </li>
                                            <li>Адрес: <span
                                                        class="text-list-name"><?= $lot->torg->owner->place->address ?></span>
                                            </li>
                                        </ul>
                                    </li>
                                <? } ?>

                            </ul>

                            <div class="mb-50"></div>

                        </div>


                        <?php if ($lot->torg->offer == Torg::OFFER_PUBLIC) { ?>

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

                                    <?php

                                    $prices = $lot->prices;

                                    if ($prices) : ?>
                                        <?php foreach ($prices as $key => $value) : ?>
                                            <?php $date = time(); ?>
                                            <div class="item-text-long <?= (($value->started_at <= $date) && $value->end_at >= $date) ? '' : 'sold-out' ?>">

                                                <div class="row align-items-center">

                                                    <div class="col-12 col-sm-6">

                                                        <div class="col-inner mb-10 mb-sm-0">

                                                            <div class="row gap-10 align-items-center">

                                                                <div class="col-6">
                                                                    <span class="font-sm">Начало</span>
                                                                    <strong class="d-block"><?= Yii::$app->formatter->asDate($value->started_at, 'long') ?></strong>
                                                                </div>

                                                                <div class="col-6 text-right text-sm-left">
                                                                    <span class="font-sm">Конец</span>
                                                                    <strong class="d-block"><?= Yii::$app->formatter->asDate($value->end_at, 'long') ?></strong>
                                                                </div>

                                                            </div>

                                                        </div>

                                                    </div>

                                                    <div class="col-12 col-sm-6">

                                                        <div class="col-inner">

                                                            <div class="row gap-10 align-items-center">

                                                                <div class="col-6 text-left text-sm-center">
                                                                    <span class="font-sm">Цена </span>
                                                                    <strong class="d-block"><?= Yii::$app->formatter->asCurrency($value->price) ?></strong>
                                                                </div>

                                                                <div class="col-6 text-left  text-sm-right">
                                                                    <span class="font-sm">Задаток</span>
                                                                    <strong class="d-block"><?= ($lot->deposit_measure == 1) ? Yii::$app->formatter->asCurrency((($value->price / 100) * $lot->deposit)) : Yii::$app->formatter->asCurrency($lot->deposit) ?></strong>
                                                                </div>

                                                            </div>

                                                        </div>

                                                    </div>

                                                </div>

                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <div class="item-text-long">

                                            <div class="row align-items-center">

                                                <div class="col-7 col-sm-9">
                                                    <strong class="text-primary">Этапы снижения цены в данной момент
                                                        находятся в обработке</strong>
                                                </div>

                                                <div class="col-5 col-sm-3">
                                                    <a href="#" class="btn btn-primary btn-block btn-sm mt-3">Задать
                                                        вопрос</a>
                                                </div>
                                            </div>

                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="mb-50"></div>

                            </div>

                        <? } ?>

                        <?= Darwin::widget() ?>

                        <? if ($lot->torg->case) { ?>
                            <? if ($lot->torg->case->documents) { ?>

                                <div id="docs" class="fullwidth-horizon--section">
                                    <h5 class="heading-title">Документы по делу должника</h5>
                                    <ul class="list-icon-absolute what-included-list mb-30 long-text">
                                        <? foreach ($lot->torg->case->documents as $document) { ?>

                                            <?
                                            switch ($document->ext) {
                                                case 'doc':
                                                    $icon = '<i class="far fa-file-word"></i>';
                                                    break;
                                                case 'docs':
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
                                                <a href="<?= $document->url ?>"
                                                   target="_blank"><?= $document->name ?></a>
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
                                            case 'doc':
                                                $icon = '<i class="far fa-file-word"></i>';
                                                break;
                                            case 'docs':
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

                        <? if ($lot->documents) { ?>
                            <div id="docs" class="fullwidth-horizon--section">
                                <h5 class="heading-title">Документы по лоту</h5>

                                <ul class="list-icon-absolute what-included-list mb-30 long-text">
                                    <? foreach ($lot->documents as $document) { ?>

                                        <?
                                        switch ($document->ext) {
                                            case 'doc':
                                                $icon = '<i class="far fa-file-word"></i>';
                                                break;
                                            case 'docs':
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

                        <?php $otherLots = $lot->torg->getOtherLots($lot->id); ?>

                        <? if ($otherLots) { ?>
                            <div id="other-lot" class="fullwidth-horizon--section">

                                <h5 class="heading-title">Другие лоты должника</h5>

                                <div class="row equal-height cols-1 cols-sm-2 gap-30 mb-25">

                                    <? foreach ($otherLots as $otherLot) {
                                        echo LotBlock::widget(['lot' => $otherLot]);
                                    } ?>

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

                        <!--                        TODO-->
                        <!--                        --><? // if ($lot->info['torgReason'] || $lot->torg->info['rules']) { ?>
                        <!--                            <div id="roles" class="detail-header mb-30">-->
                        <!--                                <h5 class="mt-30">Правила подачи заявок</h5>-->
                        <!--                                <p class="long-text">-->
                        <? //=($lot->torg->typeId == 1)? $lot->torg->info['rules'] : $lot->info['torgReason'] ?><!--</p>-->
                        <!--                                <a href="#roles" class="open-text-js">Подробнее</a>-->
                        <!--                            </div>-->
                        <!--                        --><? // } ?>

                    </div>

                </div>

                <div class="col-12 col-lg-4">

                    <div class="sidebar-desktop">
                        <?= \ymaker\social\share\widgets\SocialShare::widget([
                            'configurator' => 'socialShare',
                            'url'          => ('https://ei.ru' . Yii::$app->request->url),
                            'title'        => 'Посмотри лот на ei.ru: ' . Yii::$app->params[ 'h1' ],
                            // 'description'   => $lot->description,
//                            'imageUrl'     => Url::to($lot->images[ 0 ][ 'max' ], true),
                        ]); ?>
                        <?= LotDetailSidebar::widget(['lot' => $lot, 'type' => $type]) ?>
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

                <?= ServiceLotFormWidget::widget(['lotId' => $lot->id, 'lotType' => $type]) ?>

                <div class="text-center pb-20">
                    <button type="button" class="close" data-dismiss="modal" aria-labelledby="Close">
                        <span aria-hidden="true"><i class="far fa-times-circle"></i></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- end lot form modal -->

<?php
$this->registerJsFile('js/custom-multiply-.js', $options = ['position' => yii\web\View::POS_END], $key = 'custom-multiply-');
$this->registerJsFile('js/custom-core.js', $options = ['position' => yii\web\View::POS_END], $key = 'custom-core'); ?>