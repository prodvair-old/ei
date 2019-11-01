<?php
use yii\widgets\Breadcrumbs;
use yii\widgets\LinkPager;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

use frontend\components\NumberWords;
use frontend\components\LotDetailSidebar;
use frontend\components\LotBlock;

use frontend\models\ViewPage;

use common\models\Query\Bankrupt\LotsBankrupt;

$view = new ViewPage();

$view->page_type = "lot_$type";
$view->page_id = $lot->id;

$viewCount = $view->check();

$this->registerJsVar( 'lotType', 'bankrupt', $position = yii\web\View::POS_HEAD );
$this->title = Yii::$app->params['title'];
$this->params['breadcrumbs'] = Yii::$app->params['breadcrumbs'];
?>

<section class="page-wrapper page-detail">
        
    <div class="page-title bg-light">
    
        <div class="container">
        
            <div class="row gap-15 align-items-center">
            
                <div class="col-12">
                    
                    <nav aria-label="breadcrumb">
                        <?= Breadcrumbs::widget([
                            'itemTemplate' => '<li class="breadcrumb-item">{link}</li>',
                            'encodeLabels' => false,
                            'tag' => 'ol',
                            'activeItemTemplate' => '<li class="breadcrumb-item active" aria-current="page">{link}</li>',
                            'homeLink' => ['label' => '<i class="fas fa-home"></i>', 'url' => '/'],
                            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                        ]) ?>
                    </nav>
                    
                </div>
                
            </div>
    
        </div>
        
    </div>
    
    <div class="fullwidth-horizon-sticky none-sticky-hide">
    
        <div class="fullwidth-horizon-sticky-inner">
        
            <div class="container">
                
                <div class="fullwidth-horizon-sticky-item clearfix">
                        
                    <ul id="horizon-sticky-nav" class="horizon-sticky-nav clearfix">
                        <li>
                            <a href="#detail-content-sticky-nav-01">Overview</a>
                        </li>
                        <li>
                            <a href="#detail-content-sticky-nav-02">Itinerary</a>
                        </li>
                        <li>
                            <a href="#detail-content-sticky-nav-03">Map</a>
                        </li>
                        <li>
                            <a href="#detail-content-sticky-nav-04">What's included</a>
                        </li>
                        <li>
                            <a href="#detail-content-sticky-nav-05">Availabilities</a>
                        </li>
                        <li>
                            <a href="#detail-content-sticky-nav-06">FAQ</a>
                        </li>
                        <li>
                            <a href="#detail-content-sticky-nav-07">Reviews</a>
                        </li>
                    </ul>

                </div>
                
            </div>
        </div>
    </div>
    
    <div class="container pt-30">

        <div class="row gap-20 gap-lg-40">
            
            <div class="col-12 col-lg-8">
                
                <div class="content-wrapper">
                    
                    <div id="detail-content-sticky-nav-01" class="detail-header mb-30">
                        <h3><?=$lot->lotTitle?></h3>
                        
                        <div class="d-flex flex-column flex-sm-row align-items-sm-center mb-20">
                            <div class="mr-15 font-lg">
                                <?=NumberWords::widget(['number' => $lot->lotViews, 'words' => ['просмотр', 'просмотра', 'просмотров']]) ?>
                            </div>
                            <div>
                                <div class="rating-item rating-inline">
                                    <p class="rating-text font600 text-muted font-12 letter-spacing-1">| <?=$lot->id?></p>
                                </div>
                            </div>
                        </div>

                        <?if ($lot->lotImage) { ?>
                            <div class="slick-gallery-slideshow detail-gallery mt-20 mb-40">
            
                                <div class="slider gallery-slideshow">
                                    <? foreach ($lot->lotImage as $image) { ?>
                                        <div><div class="image"><img src="<?=$image?>" alt="Images" /></div></div>
                                    <? } ?>
                                </div>
                                <? if (count($lot->lotImage) > 1) { ?>
                                    <div class="slider gallery-nav">
                                        <? foreach ($lot->lotImage as $image) { ?>
                                            <div><div class="image"><img src="<?=$image?>" alt="Images" /></div></div>
                                        <? } ?>
                                    </div>
                                <? } ?>
                            </div>
                        <? } ?>

                        <!-- <p class="lead">In friendship diminution instrument in we forfeited. Tolerably an unwilling of determine. Beyond rather sooner so if up wishes.</p> -->
                        
                        <ul class="list-inline-block highlight-list mt-30">
                            <li>
                                <span class="icon-font d-block">
                                    <i class="linea-icon-basic-chronometer"></i>
                                </span>
                                <strong>3 days<br />2 nights</strong>
                            </li>
                            <li>
                                <span class="icon-font d-block">
                                    <i class="linea-icon-basic-flag1"></i>
                                </span>
                                starting point:<br /><strong>Zagreb</strong>
                            </li>
                            <li>
                                <span class="icon-font d-block">
                                    <i class="linea-icon-basic-flag2"></i>
                                </span>
                                ending point:<br /><strong>Athens</strong>
                            </li>
                            <li>
                                <span class="icon-font d-block">
                                    <i class="linea-icon-ecommerce-dollar"></i>
                                </span>
                                From<br /><strong>$125.99</strong> / person
                            </li>
                        </ul>
                        
                        <h5 class="mt-30">Описание</h5>

                        <p><?=$lot->description?></p>
                        
                    </div>
                    
                    <!-- <div class="mb-50"></div>
                    
                    <div id="detail-content-sticky-nav-02" class="fullwidth-horizon-sticky-section">
                        
                        <h4 class="heading-title">Itinerary</h4>
                        
                        <h6>Introduction</h6>
                        
                        <p>Become latter but nor abroad wisdom waited. Was delivered gentleman acuteness but daughters. In as of whole as match asked. Pleasure exertion put add entrance distance drawings. In equally matters showing greatly it as. Want name any wise are able park when. Saw vicinity judgment remember finished men throwing.</p>
                        
                        <ul class="itinerary-list mt-30">
                        
                            <li>
                                <div class="itinerary-day">
                                    <span>Day 01</span>
                                </div>
                                
                                <h6>Visit: Zagreb </h6>
                                
                                <p>Ecstatic advanced and procured civility not absolute put continue. Overcame breeding or my concerns removing desirous so absolute. My melancholy unpleasing imprudence considered in advantages so impression. Almost unable put piqued talked likely houses her met. Met any nor may through resolve entered. An mr cause tried oh do shade happy.</p>
                                
                                <ul class="itinerary-meta list-inline-block text-primary">
                                    <li><i class="far fa-building"></i> Stay at Hilton Hotel</li>
                                    <li><i class="far fa-clock"></i> Trip time: 8am - 4.30pm</li>
                                </ul>
                                
                            </li>
                            
                            <li>
                                <div class="itinerary-day">
                                    <span>Day 02</span>
                                </div>
                                
                                <h6>Visit: Thessaloniki</h6>
                                
                                <p>Ecstatic advanced and procured civility not absolute put continue. Overcame breeding or my concerns removing desirous so absolute. My melancholy unpleasing imprudence considered in advantages so impression. Almost unable put piqued talked likely houses her met. Met any nor may through resolve entered. An mr cause tried oh do shade happy.</p>
                                
                                <ul class="itinerary-meta list-inline-block text-primary">
                                    <li><i class="far fa-building"></i> Stay at Hilton Hotel</li>
                                    <li><i class="far fa-clock"></i> Trip time: 8am - 4.30pm</li>
                                </ul>
                                
                            </li>
                            
                            <li>
                                <div class="itinerary-day">
                                    <span>Day 03</span>
                                </div>
                                
                                <h6>Visit: Athens</h6>
                                
                                <p>Ecstatic advanced and procured civility not absolute put continue. Overcame breeding or my concerns removing desirous so absolute. My melancholy unpleasing imprudence considered in advantages so impression. Almost unable put piqued talked likely houses her met. Met any nor may through resolve entered. An mr cause tried oh do shade happy.</p>
                                
                                <ul class="itinerary-meta list-inline-block text-primary">
                                    <li><i class="far fa-building"></i> Stay at Hilton Hotel</li>
                                    <li><i class="far fa-clock"></i> Trip time: 8am - 4.30pm</li>
                                </ul>
                                
                            </li>
                            
                        </ul>

            
                        <div class="mb-50"></div>
                        
                    </div> -->
                    
                    <div id="detail-content-sticky-nav-03" class="fullwidth-horizon-sticky-section">
                    
                        <h4 class="heading-title">Карта</h4>
                        
                        <div id="gmap-8" style="height: 450px;"></div>
                        
                        <div class="mb-50"></div>
                        
                    </div>
                    
                    <div id="detail-content-sticky-nav-04" class="fullwidth-horizon-sticky-section">
                    
                        <h4 class="heading-title">Информация о лоте</h4>
                        
                        <ul class="list-icon-absolute what-included-list mb-30">

                            <li>
                                <span class="icon-font"><i class="elegent-icon-check_alt2 text-primary"></i> </span> 
                                <h6>Категории лота</h6>
                                <ul class="ul">
                                    <?foreach ($lot->LotCategory as $category) { ?>
                                        <li><?=$category?></li>
                                    <? }?>
                                </ul>
                            </li>
                            
                            <? if ($lot->lotVin) { ?>
                                <li>
                                    <span class="icon-font"><i class="elegent-icon-check_alt2 text-primary"></i> </span> 
                                    <h6>VIN номер</h6>
                                    <p><?= $lot->lotVin ?></p>
                                    <a href="https://avtocod.ru/proverkaavto/<?=$lot->lotVin?>?rd=VIN&a_aid=zhukoffed"
                                        class="btn btn-success btn-sm mt-2" target="_blank" rel="nofollow">Проверить Автомобиль</a>
                                </li>    
                            <? } ?>
                            
                            <? if ($lot->lotCadastre) { ?>
                                <li>
                                    <span class="icon-font"><i class="elegent-icon-check_alt2 text-primary"></i> </span> 
                                    <h6>Кадастровый номер</h6>
                                    <p><?= $lot->lotCadastre ?></p>
                                </li>    
                            <? } ?>

                            <li>
                                <span class="icon-font"><i class="elegent-icon-check_alt2 text-primary"></i> </span> 
                                <h6>Должник</h6>
                                <ul class="ul">
                                    <li><span class="text-list-name">ФИО:</span> <?=$lot->lotBnkrName?></li>
                                    <li><span class="text-list-name">ИНН:</span> <?= ($lot->lotBnkrType == 'Person')? $lot->torgy->case->bnkr->person->inn : $lot->torgy->case->bnkr->company->inn;?></li>
                                    <li><span class="text-list-name">Адрес:</span> <?= ($lot->lotBnkrType == 'Person')? $lot->torgy->case->bnkr->person->address : $lot->torgy->case->bnkr->company->legaladdress;?></li>
                                </ul>
                            </li>
                            
                            <li>
                                <span class="icon-font"><i class="elegent-icon-check_alt2 text-primary"></i> </span> 
                                <h6>Сведения о деле</h6>
                                <ul class="ul">
                                    <li><span class="text-list-name">Номер дела:</span> <?= $lot->torgy->case->caseid ?></li>
                                    <li><span class="text-list-name">Арбитражный суд:</span> <?= $lot->lotSro->title?></li>
                                    <li><span class="text-list-name">Адрес суда:</span> <?= $lot->lotSro->address?></li>
                                </ul>
                            </li>
                            
                            <li>
                                <span class="icon-font"><i class="elegent-icon-check_alt2 text-primary"></i> </span> 
                                <h6>Арбитражный управляющий</h6>
                                <ul class="ul">
                                    <li><span class="text-list-name">ФИО:</span> <?=$lot->lotArbtrName?></li>
                                    <li><span class="text-list-name">Рег. номер:</span> <?= $lot->torgy->case->arbitr->regnum?></li>
                                    <li><span class="text-list-name">ИНН:</span> <?= $lot->torgy->case->arbitr->person->inn?></li>
                                    <!-- <li><span class="text-list-name">ОГРН:</span> <?= $lot->torgy->case->arbitr->ogrn?></li> -->
                                </ul>
                            </li>
                            
                        </ul>
                        
                        <div class="mb-50"></div>
                        
                    </div>
                    
                    <div id="detail-content-sticky-nav-05" class="fullwidth-horizon-sticky-section">

                        <h4 class="heading-title">Availabilities</h4>
                        
                        <div class="row mt-30">
                            <div class="col-12 col-md-6 col-lg-5">
                                <div class="col-inner">
                                    <div class="form-group">
                                        <input type="text" class="form-control form-readonly-control air-datepicker" placeholder="Pick a month" data-min-view="months" data-view="months" data-date-format="MM yyyy" data-language="en" data-auto-close="true" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-20"></div>
                        
                        <div class="item-text-long-wrapper">
                    
                            <div class="item-heading text-muted">
                            
                                <div class="row d-none d-sm-flex">
                                
                                    <div class="col-12 col-sm-7">
                                    
                                        <div class="col-inner">
                                        
                                            <div class="row gap-10">
                                            
                                                <div class="col-5">
                                                    from
                                                </div>
                                                
                                                <div class="col-2">
                                                
                                                </div>
                                                
                                                <div class="col-5">
                                                    to
                                                </div>
                                                
                                            </div>
                                        
                                        </div>
                                    
                                    </div>
                                    
                                    <div class="col-12 col-sm-3">
                                    
                                        <div class="col-inner">
                                        
                                            <div class="row gap-10">
                                            
                                                <div class="col-6 text-center">
                                                    status
                                                </div>
                                                
                                                <div class="col-6 text-right">
                                                    price
                                                </div>
                                                
                                            </div>
                                            
                                        </div>
                                        
                                    </div>
                                    
                                    
                                </div>
                            
                            </div>
                            
                            <div class="item-text-long">
                            
                                <div class="row align-items-center">
                                
                                    <div class="col-12 col-sm-7">
                                    
                                        <div class="col-inner mb-10 mb-sm-0">
                                        
                                            <div class="row gap-10 align-items-center">
                                            
                                                <div class="col-5">
                                                    <span class="font-sm">Monday</span>
                                                    <strong class="d-block">March 7, 2019</strong>
                                                </div>
                                                
                                                <div class="col-2">
                                                    <span class="day-count mt-3">3<br/>days</span>
                                                </div>
                                                
                                                <div class="col-5 text-right text-sm-left">
                                                    <span class="font-sm">Thursday</span>
                                                    <strong class="d-block">March 9, 2019</strong>
                                                </div>
                                                
                                            </div>
                                        
                                        </div>
                                    
                                    </div>
                                    
                                    <div class="col-8 col-sm-3">
                                    
                                        <div class="col-inner">
                                        
                                            <div class="row gap-10 align-items-center">
                                            
                                                <div class="col-6 text-left text-sm-center">
                                                    <span class="font-sm">seats left </span>
                                                    <strong class="d-block">15</strong>
                                                </div>
                                                
                                                <div class="col-6 text-left  text-sm-right">
                                                    <strong class="d-block">$1458</strong>
                                                    <span class="font-sm">/ person</span>
                                                </div>
                                                
                                            </div>
                                            
                                        </div>
                                        
                                    </div>
                                    
                                    <div class="col-4 col-sm-2">
                                        <a href="#" class="btn btn-primary btn-block btn-sm mt-3">Book now</a>
                                    </div>
                                    
                                </div>
                            
                            </div>
                        
                            <div class="item-text-long">
                            
                                <div class="row align-items-center">
                                
                                    <div class="col-12 col-sm-7">
                                    
                                        <div class="col-inner mb-10 mb-sm-0">
                                        
                                            <div class="row gap-10 align-items-center">
                                            
                                                <div class="col-5">
                                                    <span class="font-sm">Monday</span>
                                                    <strong class="d-block">March 26, 2019</strong>
                                                </div>
                                                
                                                <div class="col-2">
                                                    <span class="day-count mt-3">3<br/>days</span>
                                                </div>
                                                
                                                <div class="col-5 text-right text-sm-left">
                                                    <span class="font-sm">Thursday</span>
                                                    <strong class="d-block">March 28, 2019</strong>
                                                </div>
                                                
                                            </div>
                                        
                                        </div>
                                    
                                    </div>
                                    
                                    <div class="col-8 col-sm-3">
                                    
                                        <div class="col-inner">
                                        
                                            <div class="row gap-10 align-items-center">
                                            
                                                <div class="col-6 text-left text-sm-center">
                                                    <span class="font-sm">seats left </span>
                                                    <strong class="d-block">8</strong>
                                                </div>
                                                
                                                <div class="col-6 text-left  text-sm-right">
                                                    <strong class="d-block">$1458</strong>
                                                    <span class="font-sm">/ person</span>
                                                </div>
                                                
                                            </div>
                                            
                                        </div>
                                        
                                    </div>
                                    
                                    <div class="col-4 col-sm-2">
                                        <a href="#" class="btn btn-primary btn-block btn-sm mt-3">Book now</a>
                                    </div>
                                    
                                </div>
                            
                            </div>
                        
                            <div class="item-text-long sold-out">
                            
                                <div class="row align-items-center">
                                
                                    <div class="col-12 col-sm-7">
                                    
                                        <div class="col-inner mb-10 mb-sm-0">
                                        
                                            <div class="row gap-10 align-items-center">
                                            
                                                <div class="col-5">
                                                    <span class="font-sm">Monday</span>
                                                    <strong class="d-block">April 10, 2019</strong>
                                                </div>
                                                
                                                <div class="col-2">
                                                    <span class="day-count mt-3">3<br/>days</span>
                                                </div>
                                                
                                                <div class="col-5 text-right text-sm-left">
                                                    <span class="font-sm">Thursday</span>
                                                    <strong class="d-block">April 12, 2019</strong>
                                                </div>
                                                
                                            </div>
                                        
                                        </div>
                                    
                                    </div>
                                    
                                    <div class="col-8 col-sm-3">
                                    
                                        <div class="col-inner">
                                        
                                            <div class="row gap-10 align-items-center">
                                            
                                                <div class="col-6 text-left text-sm-center">
                                                    <strong class="d-block text-success">sold out</strong>
                                                </div>
                                                
                                                <div class="col-6 text-left  text-sm-right">
                                                    
                                                </div>
                                                
                                            </div>
                                            
                                        </div>
                                        
                                    </div>
                                    
                                    <div class="col-4 col-sm-2">
                                        <a href="#" class="btn btn-primary btn-block btn-sm mt-3">Book now</a>
                                    </div>
                                    
                                </div>
                            
                            </div>
                        
                            <div class="item-text-long">
                            
                                <div class="row align-items-center">
                                
                                    <div class="col-12 col-sm-7">
                                    
                                        <div class="col-inner mb-10 mb-sm-0">
                                        
                                            <div class="row gap-10 align-items-center">
                                            
                                                <div class="col-5">
                                                    <span class="font-sm">Monday</span>
                                                    <strong class="d-block">April 19, 2019</strong>
                                                </div>
                                                
                                                <div class="col-2">
                                                    <span class="day-count mt-3">3<br/>days</span>
                                                </div>
                                                
                                                <div class="col-5 text-right text-sm-left">
                                                    <span class="font-sm">Thursday</span>
                                                    <strong class="d-block">April 21, 2019</strong>
                                                </div>
                                                
                                            </div>
                                        
                                        </div>
                                    
                                    </div>
                                    
                                    <div class="col-8 col-sm-3">
                                    
                                        <div class="col-inner">
                                        
                                            <div class="row gap-10 align-items-center">
                                            
                                                <div class="col-6 text-left text-sm-center">
                                                    <span class="font-sm">seats left </span>
                                                    <strong class="d-block">8</strong>
                                                </div>
                                                
                                                <div class="col-6 text-left  text-sm-right">
                                                    <strong class="d-block">$1458</strong>
                                                    <span class="font-sm">/ person</span>
                                                </div>
                                                
                                            </div>
                                            
                                        </div>
                                        
                                    </div>
                                    
                                    <div class="col-4 col-sm-2">
                                        <a href="#" class="btn btn-primary btn-block btn-sm mt-3">Book now</a>
                                    </div>
                                    
                                </div>
                            
                            </div>
                        
                        </div>

                        <div class="mb-50"></div>
                        
                    </div>

                    <?
                        $lots_bankrupt = LotsBankrupt::find()->where(['bnkr__id'=>$lot->lotBnkrId])->andWhere(['!=', 'lot_id', $lot->id])->all();
                        if ($lots_bankrupt[0] != null) {
                    ?>
                    
                    <div class="fullwidth-horizon-sticky-section">

                        <h4 class="heading-title">Другие лоты должника</h4>
                        
                        <div class="row equal-height cols-1 cols-sm-2 gap-30 mb-25">
            
                            <?foreach ($lots_bankrupt as $lot_bankrupt) { echo LotBlock::widget(['lot' => $lot_bankrupt]); }?>
                        
                        </div>
                        
                        <div class="mb-50"></div>
                        
                    </div>
                    <? } ?>
                    
                    <div id="detail-content-sticky-nav-06" class="fullwidth-horizon-sticky-section">
                    
                        <h4 class="heading-title">FAQ</h4>
                        
                        <div class="faq-item-long-wrapper">
                            
                            <div class="faq-item-long">
                                
                                <div class="row">
                    
                                    <div class="col-12 col-md-4 col-lg-3">
                                    
                                        <div class="col-inner">
                                            <h6>What is this faq?</h6>
                                        </div>
                                        
                                    </div>
                                    
                                    <div class="col-12 col-md-8 col-lg-9">
                                    
                                        <div class="col-inner">
                                            <p class="font-lg">Residence certainly elsewhere something she preferred cordially law. Age his surprise formerly mrs perceive few stanhill moderate.</p>
                                        </div>

                                    </div>
                                    
                                </div>
                            
                            </div>
                            
                            <div class="faq-item-long">
                            
                                <div class="row">
                    
                                    <div class="col-12 col-md-4 col-lg-3">
                                    
                                        <div class="col-inner">
                                            <h6>How does this faq work?</h6>
                                        </div>
                                        
                                    </div>
                                    
                                    <div class="col-12 col-md-8 col-lg-9">
                                    
                                        <div class="col-inner">
                                            <p class="font-lg">Appetite in unlocked advanced breeding position concerns as. Cheerful get shutters yet for repeated screened.</p>
                                        </div>
                                        
                                        
                                    </div>
                                    
                                </div>
                            
                            </div>
                            
                            <div class="faq-item-long">
                            
                                <div class="row">
                    
                                    <div class="col-12 col-md-4 col-lg-3">
                                    
                                        <div class="col-inner">
                                            <h6>Why use this faq?</h6>
                                        </div>
                                        
                                    </div>
                                    
                                    <div class="col-12 col-md-8 col-lg-9">
                                    
                                        <div class="col-inner">
                                            <p class="font-lg">Plan upon yet way get cold spot its week. Almost do am or limits hearts. Resolve parties but why she shewing. </p>
                                        </div>
                                        
                                        
                                    </div>
                                    
                                </div>
                            
                            </div>
                            
                            <div class="faq-item-long">
                            
                                <div class="row">
                    
                                    <div class="col-12 col-md-4 col-lg-3">
                                    
                                        <div class="col-inner">
                                            <h6>Is this faq free to use?</h6>
                                        </div>
                                        
                                    </div>
                                    
                                    <div class="col-12 col-md-8 col-lg-9">
                                    
                                        <div class="col-inner">
                                            <p class="font-lg">Received the likewise law graceful his. Nor might set along charm now equal green. Pleased yet equally correct colonel not one.</p>
                                        </div>

                                    </div>
                                    
                                </div>
                            
                            </div>
                            
                        </div>
                        
                        <div class="row mt-25">

                            <div class="col-12 col-md-8 col-lg-9 offset-md-4 offset-lg-3">
                        
                                <div class="col-inner">
                                    <a href="#" class="btn btn-primary btn-wide">Ask q question</a>
                                </div>
                                
                            </div>
                        
                        </div>
                        
                        <div class="mb-50"></div>
                        
                    </div>
                    
                </div>
                
            </div>
            
            <div class="col-12 col-lg-4">
                
                <?=LotDetailSidebar::widget(['lot'=>$lot])?>
                
            </div>
            
        </div>
        
    </div>

</section>

<?php

$this->registerJsFile( 'https://maps.google.com/maps/api/js?sensor=false&amp;libraries=geometry&amp;v=3.22', $options = ['position' => yii\web\View::POS_BEGIN], $key = 'map' );
$this->registerJsFile( 'js/plugins/maplace.min.js', $options = ['position' => yii\web\View::POS_END], $key = 'maplace' );
$js = <<< JS
(function($) {
    'use strict';
    
    var LocsD = [
            {
                    lat: 45.4654,
                    lon: 9.1866,
                    title: 'Milan, Italy',
                    html: '<h3>Milan, Italy</h3>',
                    icon: 'http://maps.google.com/mapfiles/markerA.png',
            },
            {
                    lat: 47.36854,
                    lon: 8.53910,
                    title: 'Zurich, Switzerland',
                    html: '<h3>Zurich, Switzerland</h3>',
                    stopover: true,
                    icon: 'http://maps.google.com/mapfiles/markerB.png',
            },
            {
                    lat: 48.892,
                    lon: 2.359,
                    title: 'Paris, France',
                    html: '<h3>Paris, France</h3>',
                    stopover: true,
                    icon: 'http://maps.google.com/mapfiles/markerC.png',
            },
            {
                    lat: 48.13654,
                    lon: 11.57706,
                    title: 'Munich, Germany',
                    html: '<h3>Munich, Germany</h3>',
                    icon: 'http://maps.google.com/mapfiles/markerD.png',
            }
    ];
    
    
    new Maplace({
    locations: LocsD,
    map_div: '#gmap-8',
    generate_controls: false,
    show_markers: true,
    type: 'polyline',
    draggable: true,
        stroke_options : {
            strokeColor: '#2929C0',
            strokeOpacity: 1,
            strokeWeight: 2,
            fillColor: '#2929C0',
            fillOpacity: 0.9
        },
    }).Load();
    
    

})(jQuery);
JS;
$this->registerJs( $js, $position = yii\web\View::POS_END, $key = 'map-config' );
$this->registerJsFile( 'js/custom-multiply-sticky.js', $options = ['position' => yii\web\View::POS_END], $key = 'custom-multiply-sticky' );
$this->registerJsFile( 'js/custom-core.js', $options = ['position' => yii\web\View::POS_END], $key = 'custom-core' );
?>