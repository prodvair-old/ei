<?php

/* @var $this yii\web\View */

use yii\helpers\Url;
use frontend\components\LotBlock;
use frontend\components\SearchForm;
use frontend\components\LotDetailSidebar;
use common\models\Query\Settings;



$this->title = Yii::$app->params['title'];


$title = 'Имущество организации <span class="font200 block">Банка Открытие</span>';
$description = ''; //'В нашей базе собрана исключительно актуальная информация об имущество банкротов и должников, выставленном и тендеры и торги на ЭТП и публичных аукционах.';

?>

<div class="otkrytie">

<div class="hero-banner hero-banner-01 overlay-light opacity-2 overlay-relative overlay-gradient gradient-white alt-option-03" style="background-image:url(https://cdn.open.ru/storage/top_picture/39964/609796910__1__2bd3.png); background-position: top  center;" >
        
    <div class="overlay-holder bottom"></div>	
    
    <div class="hero-inner">
    
        <div class="container">

            <div class="hero-header">

                <div class="hero-header__logo">
                
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 2000 2000">
                        <path fill="#fff" d="M1655.5 1048.54c0,235.52 -122.55,446.84 -320.02,551.43l-45.27 21.27c-14.93,6.93 -31.42,10.83 -47.22,10.83 -30.83,0 -55.32,-13.07 -69.86,-36.88 -11.61,-19.71 -14.83,-40.97 -10.44,-65.37 6.93,-33.66 33.27,-50.63 41.08,-54.83 116.49,-52.88 191.32,-171.8 191.32,-303.13l0 -343.52c0,-131.52 -74.54,-250.16 -189.86,-302.36 -0.19,-0.29 -34.64,-16.78 -42.54,-55.81 -4.39,-24.29 -1.17,-45.76 10.44,-65.17 14.54,-23.71 39.03,-36.98 69.86,-36.98 15.8,0 32.29,3.61 47.12,10.63 0,0 42.64,20.2 44.3,20.88 198.35,105.18 321.09,316.51 321.09,551.93l0 97.08zm-859.36 426.45l0 0c7.9,4.2 34.14,21.17 41.17,54.83 4.49,24.4 1.27,45.66 -10.44,65.37 -14.54,23.81 -39.03,36.88 -69.86,36.88 -15.8,0 -32.19,-3.9 -47.12,-10.83l-45.47 -21.27c-197.37,-104.59 -319.92,-315.91 -319.92,-551.43l0 -97.08c0,-235.42 122.84,-446.75 320.99,-551.93 1.86,-0.68 44.4,-20.88 44.4,-20.88 14.93,-7.02 31.32,-10.63 47.02,-10.63 30.74,0 55.42,13.27 69.96,36.98 11.71,19.41 14.93,40.88 10.44,65.17 -7.81,39.03 -42.15,55.52 -42.54,55.81 -115.23,52.2 -189.67,170.84 -189.67,302.36l0 343.52c0,131.33 74.54,250.25 191.04,303.13zm203.81 -1442.89l0 0c-534.56,0 -968.05,433.29 -968.05,967.86 0,534.64 433.49,967.93 968.05,967.93 534.66,0 968.15,-433.29 968.15,-967.93 0,-534.57 -433.49,-967.86 -968.15,-967.86z"/>
                    </svg>

                </div>
            
                <h1><?=$title?></h1>
                <p class="font-lg spacing-1 "><?=$description?></p>

                <?= SearchForm::widget(['type' => $type])?>
            
            </div>
            
        </div>
        
    </div>
    
</div>

<section class="pt-0 pb-0">

    <div class="container">

        <div class="clear mb-100"></div>

        <div class="row cols-1 cols-sm-2 cols-lg-4 gap-2 mb-20">
        
            <div class="col">
            
                <figure class="category__item color-1">
                    <a href="/bankrupt/transport-i-tehnika">
                        <div class="image">
                            <img src="https://yt3.ggpht.com/a/AGF-l7-EVhBEj7aPvzyeC9QuZqwSPa8SgyuT-Ixttg=s800-mo-c-c0xffffffff-rj-k-no"alt="image"/>
                        </div>
                        <figcaption class="content">
                            <h6>Автомобили</h6>
                            <!-- <p class="text-muted">25 лотов</p> -->
                        </figcaption>
                    </a>
                </figure>
                
            </div>
            
            <div class="col">
            
                <figure class="category__item color-2">
                    <a href="/bankrupt/nedvizhimost">
                        <div class="image">
                            <img src="https://i.diymall.co/diygoods/1281/plitka_dekorativnaya_london_brik_tsvet_multikolor_116_m2_1.jpg"alt="image"/>
                        </div>
                        <figcaption class="content">
                            <h6>Недвижимость</h6>
                            <!-- <p class="text-muted">36 лотов</p> -->
                        </figcaption>
                    </a>
                </figure>
                
            </div>
            
            <div class="col">
            
                <figure class="category__item color-3">
                    <a href="/bankrupt/oborudovanie">
                        <div class="image">
                            <img src="https://www.talenthero.de/wp-content/uploads/Metall-Glockengießer-2-800x800.jpg"alt="image"/>
                        </div>
                        <figcaption class="content">
                            <h6>Оборудование</h6>
                            <!-- <p class="text-muted">40 лотов</p> -->
                        </figcaption>
                    </a>
                </figure>
                
            </div>
            
            <div class="col">
          
                <figure class="category__item color-4">
                    <a href="/bankrupt/debitorskaya-zadolzhennost">
                        <div class="image">
                            <img src="https://ae01.alicdn.com/kf/HTB15d0Gq1SSBuNjy0Flq6zBpVXad/-.jpg"alt="image"/>
                        </div>
                        <figcaption class="content">
                            <h6>Дебиторская задолженность</h6>
                            <!-- <p class="text-muted">10 лотов</p> -->
                        </figcaption>
                    </a>
                </figure>
                
            </div>
            
        </div>
    
        <div class="clear mb-100"></div>
                
        <!-- <div class="section-title">
            <h2><span><span>Горячие</span> Предложения Дня</span></h2>
        </div>
        
        <div class="row equal-height cols-1 cols-sm-2 cols-lg-3 gap-20 mb-30">

            <?//foreach ($lots as $lot) { echo LotBlock::widget(['lot' => $lot]); }?>
            
        </div>

        <div class="clear mb-100"></div> -->
                
        <div class="section-title">
            <h2><span><span>Интересные</span> предложения дня</span></h2>
        </div>
        
        <div class="row equal-height cols-1 cols-sm-2 cols-lg-3 gap-20 mb-30">

            <?foreach ($lotsFovarit as $lotFovarit) { echo LotBlock::widget(['lot' => $lotFovarit]); }?>
            
        </div>
        <div class="clear mb-100"></div>
        
    </div>
    
</section>

<!-- <div class="bg-white-gradient-top-bottom pt-0 mt-40">

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

</section> -->

</div>
</div>