<?php

/* @var $this yii\web\View */

use yii\helpers\Url;
use frontend\components\LotBlock;
use frontend\components\SearchForm;
use common\models\Query\Settings;



$this->title = Yii::$app->params['title'];
?>

<div class="hero-banner hero-banner-01 overlay-light opacity-2" style="background-image:url('img/image-bg/19-2.jpg'); background-position: bottom  center;">
        
    <div class="overlay-holder bottom"></div>	
    
    <div class="hero-inner">
    
        <div class="container">
            <div class="row">
                <div class="col-lg-4"><h1 class="main__title"><?=Yii::$app->params['h1']?></h1></div>
                <div class="col-lg-8"><p class="font-lg spacing-1"><?=Yii::$app->params['text']?></p></div>
            </div>
            
            
            <?= SearchForm::widget(['type' => 'small'])?>
            <!-- <div class="search-form-main">
                <form>
                    <div class="from-inner">
                        
                        <div class="row shrink-auto-sm gap-1">
                        
                            <div class="col-12 col-auto">
                                <div class="col-inner">
                                    <div class="row cols-1 cols-sm-3 gap-1">
                        
                                        <div class="col">
                                            <div class="col-inner">
                                                <div class="form-group">
                                                    <label>Tour Type</label>
                                                    <select class="chosen-the-basic form-control form-control-sm" placeholder="Select one" tabindex="2">
                                                        <option></option>
                                                        <option>All</option>
                                                        <option>Adventure</option>
                                                        <option>City tour</option>
                                                        <option>Honeymoon</option>
                                                        <option>Cultural</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col">
                                            <div class="col-inner">
                                                <div class="form-group">
                                                    <label>Destination</label>
                                                    <select class="chosen-the-basic form-control form-control-sm" placeholder="Select two" tabindex="2">
                                                        <option></option>
                                                        <option>All</option>
                                                        <option>Asia</option>
                                                        <option>Europe</option>
                                                        <option>Africa</option>
                                                        <option>America</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col">
                                            <div class="col-inner">
                                                <div class="form-group">
                                                    <label>When</label>
                                                    <input type="text" class="form-control form-readonly-control air-datepicker" placeholder="Pick a month" data-min-view="months" data-view="months" data-date-format="MM yyyy" data-language="en" data-auto-close="true" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-shrink">
                                <div class="col-inner">
                                    <a href="#" class="btn btn-primary btn-block"><i class="ion-android-search"></i></a>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </form>
            </div> -->

        </div>
        
    </div>
    
</div>

<section class="pt-70 pb-0">

    <div class="container">
    
        <div class="row cols-1 cols-lg-3 gap-20 gap-lg-40">
            
            <div class="col">
                <div class="featured-icon-horizontal-01 clearfix">
                    <div class="icon-font">
                        <i class="elegent-icon-gift_alt text-primary"></i>
                    </div>
                    <div class="content">
                        <h6>We ﬁnd better deals</h6>
                        <p class="text-muted">Considered an invitation do introduced sufficient understood instrument it. </p>
                    </div>
                </div>
            </div>
            
            <div class="col">
                <div class="featured-icon-horizontal-01 clearfix">
                    <div class="icon-font">
                        <i class="elegent-icon-wallet text-primary"></i>
                    </div>
                    <div class="content">
                        <h6>Best price guaranteed</h6>
                        <p class="text-muted">Discovery sweetness principle discourse shameless bed one excellent.</p>
                    </div>
                </div>
            </div>
            
            <div class="col">
                <div class="featured-icon-horizontal-01 clearfix">
                    <div class="icon-font">
                        <i class="elegent-icon-heart_alt text-primary"></i>
                    </div>
                    <div class="content">
                        <h6>Travellers love us</h6>
                        <p class="text-muted">Sentiments of surrounded friendship dispatched connection john shed hope.</p>
                    </div>
                </div>
            </div>
            
        </div>
        
        <div class="clear mb-100"></div>
        
        <div class="section-title">
            <h2><span><span>Top</span> Destinations</span></h2>
        </div>
        
        <div class="row cols-1 cols-sm-2 cols-lg-4 gap-2 mb-20">
        
            <div class="col">
            
                <figure class="destination-grid-item-01">
                    <a href="#">
                        <div class="image">
                            <img src="img/image-destination/01.jpg"alt="image"/>
                        </div>
                        <figcaption class="content">
                            <h5>Bangkok</h5>
                            <p class="text-muted">25 Tours</p>
                        </figcaption>
                    </a>
                </figure>
                
            </div>
            
            <div class="col">
            
                <figure class="destination-grid-item-01">
                    <a href="#">
                        <div class="image">
                            <img src="img/image-destination/02.jpg"alt="image"/>
                        </div>
                        <figcaption class="content">
                            <h5>Hong Kong</h5>
                            <p class="text-muted">36 Tours</p>
                        </figcaption>
                    </a>
                </figure>
                
            </div>
            
            <div class="col">
            
                <figure class="destination-grid-item-01">
                    <a href="#">
                        <div class="image">
                            <img src="img/image-destination/03.jpg"alt="image"/>
                        </div>
                        <figcaption class="content">
                            <h5>London</h5>
                            <p class="text-muted">40 Tours</p>
                        </figcaption>
                    </a>
                </figure>
                
            </div>
            
            <div class="col">
            
                <figure class="destination-grid-item-01">
                    <a href="#">
                        <div class="image">
                            <img src="img/image-destination/04.jpg"alt="image"/>
                        </div>
                        <figcaption class="content">
                            <h5>New York</h5>
                            <p class="text-muted">10 Tours</p>
                        </figcaption>
                    </a>
                </figure>
                
            </div>
            
        </div>
        
        <div class="clear mb-100"></div>
        
        <div class="section-title">
            <h2><span><span>Best</span> Tour Packages</span></h2>
        </div>
        
        <div class="row equal-height cols-1 cols-sm-2 cols-lg-3 gap-20 mb-30">

            <?foreach ($lots as $lot) { echo LotBlock::widget(['lot' => $lot]); }?>
            
        </div>
        
    </div>
    
</section>

<div class="bg-white-gradient-top-bottom pt-0 mt-40">

    <div class="bg-gradient-top"></div>
    <div class="bg-gradient-bottom"></div>
    
    <div class="bg-image pv-100 overlay-relative" style="background-image:url('img/image-bg/44.jpg');">
    
        <div class="overlay-holder overlay-white opacity-8"></div>
    
        <div class="container">
        
            <div class="testimonial-grid-slick-carousel testimonial-grid-wrapper">
        
                <div class="testimonial-grid-arrow">
                    <ul>
                        <li class="testimonial-grid-prev"><button><span>previuos</span></button></li>
                        <li class="testimonial-grid-next"><button><span>next</span></button></li>
                    </ul>
                </div>

                <div class="slick-carousel-wrapper gap-50">
            
                    <div class="slick-carousel-outer">
                    
                        <div class="slick-carousel-inner">

                            <div class="slick-testimonial-grid-arrows">
                                
                                <div class="slick-item">
                                
                                    <div class="testimonial-grid-01">
                                            
                                        <div class="content">
                                        
                                            <p class="saying">Real sold my in call. Invitation on an advantages collecting. But event old above shy bed noisy. Had sister see wooded favour income has. Stuff rapid since hence.</p>
                                            
                                        </div>
                                        
                                        <div class="man clearfix">
                                        
                                            <div class="image">
                                                <img src="img/image-man/01.jpg" alt="img" class="img-circle" />
                                            </div>
                                            
                                            <div class="texting">
                                                <h5>Ange Ermolova</h5>
                                                <p class="text-muted testimonial-cite">Travel on July 2016</p>
                                            </div>
                                            
                                        </div>

                                    </div>
                                    
                                </div>
                                
                                <div class="slick-item">
                                    
                                    <div class="testimonial-grid-01">
                                            
                                        <div class="content">
                                        
                                            <p class="saying">Greatly hearted has who believe. Sir margaret drawings repeated recurred exercise laughing may you. Cheerful but whatever ladyship disposed yet judgment.</p>
                                            
                                        </div>
                                        
                                        <div class="man clearfix">
                                        
                                            <div class="image">
                                                <img src="img/image-man/02.jpg" alt="img" class="img-circle" />
                                            </div>
                                            
                                            <div class="texting">
                                                <h5>Christine Gateau</h5>
                                                <p class="text-muted testimonial-cite">Travel on November 2016</p>
                                            </div>
                                            
                                        </div>

                                    </div>
                                    
                                    
                                </div>
                                
                                <div class="slick-item">
                                    
                                    <div class="testimonial-grid-01">
                                            
                                        <div class="content">
                                        
                                            <p class="saying">Ask especially collecting terminated may son expression. Extremely eagerness principle estimable cannot going laughing may you about water defer.</p>
                                            
                                        </div>
                                        
                                        <div class="man clearfix">
                                        
                                            <div class="image">
                                                <img src="img/image-man/03.jpg" alt="img" class="img-circle" />
                                            </div>
                                            
                                            <div class="texting">
                                                <h5>Suttira Ketkaew</h5>
                                                <p class="text-muted testimonial-cite">Travel on January 2017</p>
                                            </div>
                                            
                                        </div>

                                    </div>
                                    
                                </div>
                                
                                <div class="slick-item">
                                    
                                    <div class="testimonial-grid-01">
                                            
                                        <div class="content">
                                        
                                            <p class="saying">Greatly hearted has who believe. Sir margaret drawings repeated recurred exercise laughing may you. Cheerful but whatever ladyship disposed yet judgment.</p>
                                            
                                        </div>
                                        
                                        <div class="man clearfix">
                                        
                                            <div class="image">
                                                <img src="img/image-man/02.jpg" alt="img" class="img-circle" />
                                            </div>
                                            
                                            <div class="texting">
                                                <h5>Christine Gateau</h5>
                                                <p class="text-muted testimonial-cite">Travel on November 2016</p>
                                            </div>
                                            
                                        </div>

                                    </div>
                                    
                                    
                                </div>
                                
                                <div class="slick-item">
                                    
                                    <div class="testimonial-grid-01">
                                            
                                        <div class="content">
                                        
                                            <p class="saying">Ask especially collecting terminated may son expression. Extremely eagerness principle estimable cannot going laughing may you about water defer.</p>
                                            
                                        </div>
                                        
                                        <div class="man clearfix">
                                        
                                            <div class="image">
                                                <img src="img/image-man/03.jpg" alt="img" class="img-circle" />
                                            </div>
                                            
                                            <div class="texting">
                                                <h5>Suttira Ketkaew</h5>
                                                <p class="text-muted testimonial-cite">Travel on January 2017</p>
                                            </div>
                                            
                                        </div>

                                    </div>
                                    
                                </div>
                                
                                <div class="slick-item">
                                
                                    <div class="testimonial-grid-01">
                                            
                                        <div class="content">
                                        
                                            <p class="saying">Real sold my in call. Invitation on an advantages collecting. But event old above shy bed noisy. Had sister see wooded favour income has. Stuff rapid since hence.</p>
                                            
                                        </div>
                                        
                                        <div class="man clearfix">
                                        
                                            <div class="image">
                                                <img src="img/image-man/01.jpg" alt="img" class="img-circle" />
                                            </div>
                                            
                                            <div class="texting">
                                                <h5>Ange Ermolova</h5>
                                                <p class="text-muted testimonial-cite">Travel on July 2016</p>
                                            </div>
                                            
                                        </div>

                                    </div>
                                    
                                </div>
                                
                            </div>
                        
                        </div>
                        
                    </div>

                </div>

            </div>
            
        </div>
        
    </div>
    
    <div class="overlay-relative overlay-gradient gradient-white set-height-01">
        <div class="overlay-holder bottom"></div>
    </div>

</div>

<section class="pt-40 pb-100">

    <div class="container">
        
        <div class="section-title">
            <h2><span><span>Travel</span> Articles</span></h2>
        </div>
        
        <div class="post-grid-wrapper-01">
        
            <div class="row equal-height cols-1 cols-sm-2 cols-md-3 gap-10 gap-md-20 mb-40">
            
                <div class="col-12 col-md-4">
                    
                    <article class="post-grid-01">
                    
                        <div class="image">
                            <img src="img/image-regular/07.jpg" alt="img" />
                        </div>
                        <div class="content">
                            <span class="post-date text-muted">Mar 15, 2017</span>
                            <h4>Raising say express had chiefly detract</h4>
                            <a href="#" class="h6">Read this <i class="elegent-icon-arrow_right"></i></a>
                        </div>
                        
                    </article>
                    
                </div>
                
                <div class="col">
                    
                    <article class="post-grid-01">
                    
                        <div class="image">
                            <img src="img/image-regular/08.jpg" alt="img" />
                        </div>
                        <div class="content">
                            <span class="post-date text-muted">Mar 15, 2017</span>
                            <h4>Cordially convinced incommode existence</h4>
                            <a href="#" class="h6">Read this <i class="elegent-icon-arrow_right"></i></a>
                        </div>
                        
                    </article>
                    
                </div>
                
                <div class="col">
                    
                    <article class="post-grid-01">
                    
                        <div class="image">
                            <img src="img/image-regular/09.jpg" alt="img" />
                        </div>
                        <div class="content">
                            <span class="post-date text-muted">Mar 15, 2017</span>
                            <h4>Improving age our her cordially intention</h4>
                            <a href="#" class="h6">Read this <i class="elegent-icon-arrow_right"></i></a>
                        </div>
                        
                    </article>
                    
                </div>

            </div>
        
        </div>
        
    </div>

</div>