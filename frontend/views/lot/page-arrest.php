<?php
use yii\widgets\Breadcrumbs;
use yii\widgets\LinkPager;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

use frontend\components\NumberWords;

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
                                    <p class="rating-text font600 text-muted font-12 letter-spacing-1">| <?=$lot->lotId?></p>
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

                        <p><?=$lot->lotPropName?></p>

                        <h5 class="mt-30">What makes this tour very interesting</h5>
                        
                        <ul class="list-icon-data-attr font-ionicons">
                            <li data-content="&#xf383">Excited him now natural saw passage offices you minuter. Moments its musical age explain.</li>
                            <li data-content="&#xf383">Farther so friends am to detract do private.</li>
                            <li data-content="&#xf383">Procured is material his offering humanity laughing moderate can.</li>
                            <li data-content="&#xf383">She did open find pain some out. If we landlord stanhill mr whatever pleasure</li>
                        </ul>
                        
                    </div>
                    
                    <div class="mb-50"></div>
                    
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
                        
                    </div>
                    
                    <div id="detail-content-sticky-nav-03" class="fullwidth-horizon-sticky-section">
                    
                        <h4 class="heading-title">Map</h4>
                        
                        <div id="gmap-8" style="height: 450px;"></div>
                        
                        <div class="mb-50"></div>
                        
                    </div>
                    
                    <div id="detail-content-sticky-nav-04" class="fullwidth-horizon-sticky-section">
                    
                        <h4 class="heading-title">What's included</h4>
                        
                        <ul class="list-icon-absolute what-included-list mb-30">
                            
                            <li>
                                <span class="icon-font"><i class="elegent-icon-check_alt2 text-primary"></i> </span> 
                                <h6>Guide</h6>
                                <p>Adieus except say barton put feebly favour him.</p>
                            </li>
                            
                            <li>
                                <span class="icon-font"><i class="elegent-icon-check_alt2 text-primary"></i> </span> 
                                <h6>Meals</h6>
                                <p>4 breakfast &amp; 3 dinners </p>
                            </li>
                            
                            <li>
                                <span class="icon-font"><i class="elegent-icon-check_alt2 text-primary"></i> </span> 
                                <h6>Transport</h6>
                                <p>Modern air conditioned coach with reclining seats, TV for showing DVDs, and toilet</p>
                            </li>
                            
                            <li>
                                <span class="icon-font"><i class="elegent-icon-check_alt2 text-primary"></i> </span> 
                                <h6>5 Experiences</h6>
                                <p>Sense child do state to defer mr of forty. Become latter but nor abroad wisdom waited. Was delivered gentleman acuteness but daughters. In as of whole as match asked. Pleasure exertion put add entrance distance drawings.</p>
                            </li>
                            
                            <li>
                                <span class="icon-font"><i class="elegent-icon-check_alt2 text-primary"></i> </span> 
                                <h6>Other</h6>
                                <ul class="ul">
                                    <li>Free Wi-fi in all hotels </li>
                                    <li>All taxes and fees  </li>
                                    <li>Any public transport used as part of the tour (excludes free days)  </li>
                                    <li>Free Expat Explore tour souvenir </li>
                                </ul>
                            </li>
                            
                        </ul>
                        
                        <h5>Not included</h5>
                        
                        <ul class="list-icon-absolute what-included-list mb-30">
                            
                            <li>
                                <span class="icon-font"><i class="elegent-icon-close_alt2 text-dark"></i> </span> 
                                <h6>Flights</h6>
                                <p>Warmth object matter course active law spring six <a href="#">line to some where</a>. Pursuit showing tedious unknown winding see had man add.</p>
                            </li>
                            
                            <li>
                                <span class="icon-font"><i class="elegent-icon-close_alt2 text-dark"></i> </span> 
                                <h6>Insurance</h6>
                                <p>Had repulsive dashwoods suspicion sincerity but advantage now him. Remark easily garret nor nay <a href="#">line to some where</a>. Civil those mrs enjoy shy fat merry. You greatest jointure saw horrible.</p>
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
                    
                    <div class="fullwidth-horizon-sticky-section">

                        <h4 class="heading-title">Similar Tour</h4>
                        
                        <div class="row equal-height cols-1 cols-sm-2 gap-30 mb-25">
            
                            <div class="col">
                                
                                <figure class="tour-grid-item-01">

                                    <a href="#">
                                    
                                        <div class="image">
                                            <img src="images/image-bg/01.jpg" alt="images" />
                                        </div>
                                        
                                        <figcaption class="content">
                                            <h5>Rome to Naples and Amalfi Coast Adventure</h5>
                                            <ul class="item-meta">
                                                <li>
                                                    <i class="elegent-icon-pin_alt text-warning"></i> Italy
                                                </li>
                                                <li>	
                                                    <div class="rating-item rating-sm rating-inline clearfix">
                                                        <div class="rating-icons">
                                                            <input type="hidden" class="rating" data-filled="rating-icon ri-star rating-rated" data-empty="rating-icon ri-star-empty" data-fractions="2" data-readonly value="4.5"/>
                                                        </div>
                                                        <p class="rating-text font600 text-muted font-12 letter-spacing-1">26 reviews</p>
                                                    </div>
                                                </li>
                                            </ul>
                                            <ul class="item-meta mt-15">
                                                <li><span class="font700 h6">3 days &amp; 2 night</span></li>
                                                <li>
                                                    Start: <span class="font600 h6 line-1 mv-0"> Rome</span> - End: <span class="font600 h6 line-1 mv-0"> Naoples</span>
                                                </li>
                                            </ul>
                                            <p class="mt-3">Price from <span class="h6 line-1 text-primary font16">$125.99</span> <span class="text-muted mr-5"></span></p>
                                        </figcaption>
                                    
                                    </a>
                                    
                                </figure>
                                
                            </div>
                            
                            <div class="col">
                                
                                <figure class="tour-grid-item-01">

                                    <a href="#">
                                    
                                        <div class="image">
                                            <img src="images/image-bg/02.jpg" alt="images" />
                                        </div>
                                        
                                        <figcaption class="content">
                                            <h5>Everest Base Camp Trek through 3 High Passes</h5>
                                            <ul class="item-meta">
                                                <li>
                                                    <i class="elegent-icon-pin_alt text-warning"></i> Nepal
                                                </li>
                                                <li>	
                                                    <div class="rating-item rating-sm rating-inline clearfix">
                                                        <div class="rating-icons">
                                                            <input type="hidden" class="rating" data-filled="rating-icon ri-star rating-rated" data-empty="rating-icon ri-star-empty" data-fractions="2" data-readonly value="4.5"/>
                                                        </div>
                                                        <p class="rating-text font600 text-muted font-12 letter-spacing-1">53 reviews</p>
                                                    </div>
                                                </li>
                                            </ul>
                                            <ul class="item-meta mt-15">
                                                <li><span class="font700 h6">22 days</span></li>
                                                <li>
                                                    Start/End: <span class="font600 h6 line-1 mv-0"> Kathmandu</span>
                                                </li>
                                            </ul>
                                            <p class="mt-3">Price from <span class="h6 line-1 text-primary font16">$125.99</span> <span class="text-muted mr-5"></span></p>
                                        </figcaption>
                                    
                                    </a>
                                    
                                </figure>
                                
                            </div>
                        
                        </div>
                        
                    <div class="mb-50"></div>
                        
                        
                    </div>
                    
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
                    
                    <div id="detail-content-sticky-nav-07" class="fullwidth-horizon-sticky-section">
                    
                        <h4 class="heading-title">Reviews</h4>
                        
                        <ul class="review-list">
                
                            <li>
                            
                                <div class="review-man d-flex">
                                
                                    <div class="image mr-15">
                                        <img src="images/image-man/01.jpg" alt="image" class="image-circle" />
                                    </div>
                                    
                                    <div class="line-125">
                                        <h6 class="line-125 mb-3">Antony Robert</h6>
                                        <div class="rating-item rating-sm">
                                            <div class="rating-icons">
                                                <input type="hidden" class="rating" data-filled="rating-icon ri-star rating-rated" data-empty="rating-icon ri-star-empty" data-fractions="2" data-readonly value="4.5"/>
                                            </div>
                                        </div>
                                        <span class="text-muted font-sm font600">Jan 15, 2019</span>
                                    </div>
                                
                                </div>
                                
                                <div class="review-content">
                                    <p>She meant new their sex could defer child. An lose at quit to life do dull. Moreover end horrible endeavor entrance any families. Income appear extent on of thrown in admire. It as announcing it me stimulated frequently continuing. Least their she you now above going stand forth. He pretty future afraid should genius spirit on. Set property addition building put likewise get. Of will at sell well at as. Too want but tall nay like old. Removing yourself be in answered</p>
                                </div>
                            
                            </li>
                            
                            <li>
                            
                                <div class="review-man d-flex">
                                
                                    <div class="image mr-15">
                                        <img src="images/image-man/02.jpg" alt="image" class="image-circle" />
                                    </div>
                                    
                                    <div class="line-125">
                                        <h6 class="line-125 mb-3">Mohammed Salem</h6>
                                        <div class="rating-item rating-sm">
                                            <div class="rating-icons">
                                                <input type="hidden" class="rating" data-filled="rating-icon ri-star rating-rated" data-empty="rating-icon ri-star-empty" data-fractions="2" data-readonly value="4.5"/>
                                            </div>
                                        </div>
                                        <span class="text-muted font-sm font600">Jan 15, 2019</span>
                                    </div>
                                
                                </div>
                                
                                <div class="review-content">
                                    <p>Spot of come to ever hand as lady meet on. Delicate contempt received two yet advanced. Gentleman as belonging he commanded believing dejection in by. On no am winding chicken so behaved. Its preserved sex enjoyment new way behaviour. Him yet devonshire celebrated especially. Unfeeling one provision are smallness resembled repulsive.</p>
                                </div>
                            
                            </li>
                            
                            <li>
                            
                                <div class="review-man d-flex">
                                
                                    <div class="image mr-15">
                                        <img src="images/image-man/03.jpg" alt="image" class="image-circle" />
                                    </div>
                                    
                                    <div class="line-125">
                                        <h6 class="line-125 mb-3">Ange Ermolova</h6>
                                        <div class="rating-item rating-sm">
                                            <div class="rating-icons">
                                                <input type="hidden" class="rating" data-filled="rating-icon ri-star rating-rated" data-empty="rating-icon ri-star-empty" data-fractions="2" data-readonly value="4.5"/>
                                            </div>
                                        </div>
                                        <span class="text-muted font-sm font600">Jan 15, 2019</span>
                                    </div>
                                
                                </div>
                                
                                <div class="review-content">
                                    <p>Real sold my in call. Invitation on an advantages collecting. But event old above shy bed noisy. Had sister see wooded favour income has. Stuff rapid since hence.</p>
                                </div>
                            
                            </li>
                            
                            <li>
                            
                                <div class="review-man d-flex">
                                
                                    <div class="image mr-15">
                                        <img src="images/image-man/04.jpg" alt="image" class="image-circle" />
                                    </div>
                                    
                                    <div class="line-125">
                                        <h6 class="line-125 mb-3">Ange Ermolova</h6>
                                        <div class="rating-item rating-sm">
                                            <div class="rating-icons">
                                                <input type="hidden" class="rating" data-filled="rating-icon ri-star rating-rated" data-empty="rating-icon ri-star-empty" data-fractions="2" data-readonly value="4.5"/>
                                            </div>
                                        </div>
                                        <span class="text-muted font-sm font600">Jan 15, 2019</span>
                                    </div>
                                
                                </div>
                                
                                <div class="review-content">
                                    <p>Unpleasant astonished an diminution up partiality. Noisy an their of meant. Death means up civil do an offer wound of. Called square an in afraid direct. Resolution diminution conviction so mr at unpleasing simplicity no. No it as breakfast up conveying earnestly immediate principle. Him son disposed produced humoured overcame she bachelor improved. Studied however out wishing but inhabit fortune windows.</p>
                                </div>
                            
                            </li>
                            
                            <li>
                            
                                <div class="review-man d-flex">
                                
                                    <div class="image mr-15">
                                        <img src="images/image-man/05.jpg" alt="image" class="image-circle" />
                                    </div>
                                    
                                    <div class="line-125">
                                        <h6 class="line-125 mb-3">Christine Gateau</h6>
                                        <div class="rating-item rating-sm">
                                            <div class="rating-icons">
                                                <input type="hidden" class="rating" data-filled="rating-icon ri-star rating-rated" data-empty="rating-icon ri-star-empty" data-fractions="2" data-readonly value="4.5"/>
                                            </div>
                                        </div>
                                        <span class="text-muted font-sm font600">Jan 15, 2019</span>
                                    </div>
                                
                                </div>
                                
                                <div class="review-content">
                                    <p>Greatly hearted has who believe. Sir margaret drawings repeated recurred exercise laughing may you. Cheerful but whatever ladyship disposed yet judgment.</p>
                                </div>
                            
                            </li>
                            
                            <li>
                                <nav>
                                    <ul class="pagination mb-0">
                                        <li>
                                            <a href="#" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>
                                        <li class="active"><a href="#">1</a></li>
                                        <li><a href="#">2</a></li>
                                        <li><a href="#">3</a></li>
                                        <li><span>...</span></li>
                                        <li><a href="#">11</a></li>
                                        <li><a href="#">12</a></li>
                                        <li><a href="#">13</a></li>
                                        <li>
                                            <a href="#" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </li>	
                        
                        </ul>
                        
                    </div>
                    
                </div>
                
            </div>
            
            <div class="col-12 col-lg-4">
                
                <aside class="sticky-kit-02 sidebar-wrapper no-border mt-20 mt-lg-0">

                    <div class="booking-box">
                    
                        <div class="box-heading"><h3 class="h6 text-white text-uppercase">Make a booking</h3></div>
                        
                        <div class="box-content">
                        
                            <span class="font600 text-muted line-125">Your choosen date is</span>
                            <h4 class="line-125 choosen-date mt-3"><i class="ri-calendar"></i> 7 - 9 March, 2019 <small class="d-block">(3 days) <a href="#detail-content-sticky-nav-05" class="anchor font10 pl-40 d-block text-uppercase h6 text-primary float-right mt-5">Change</a></small></h4>
                            
                            
                            <div class="form-group form-spin-group border-top mt-15 pt-10">
                                <label class="h6 font-sm">How many guests?</label>
                                <input type="text" class="form-control touch-spin-03 form-control-readonly" value="2" readonly />
                            </div>
                            
                            <ul class="border-top mt-20 pt-15">
                                <li class="clearfix">$125.99 x 2 guests<span class="float-right">$251.98</span></li>
                                <li class="clearfix">Booking fee + tax<span class="float-right">$9.50</span></li>
                                <li class="clearfix pl-15">Book now &amp; Save<span class="float-right text-primary">-$15</span></li>
                                <li class="clearfix">Other fees<span class="float-right text-success">Free</span></li>
                                <li class="clearfix border-top font700">
                                    <div class="border-top mt-1">
                                    <span>Total</span><span class="float-right text-dark">$248.58</span>
                                    </div>
                                </li>
                            </ul>
                            
                            <p class="text-right font-sm">100% Satisfaction guaranteed</p>
                            
                            <a href="#" class="btn btn-primary btn-block">Instant booking</a>
                            
                            <p class="line-115 mt-20">By clicking the above button you agree to our <a href="#">Terms of Service</a> and have read and understood our <a href="#">Privacy Policy</a></p>
                            
                        </div>
                        
                        <div class="box-bottom bg-light">
                            <h6 class="font-sm">We are the best tour operator</h6>
                            <p class="font-sm">Our custom tour program, direct call <span class="text-primary">+66857887444</span>.</p>
                        </div>
                        
                    </div>
                
                </aside>
                
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