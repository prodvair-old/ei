<?php

/* @var $this yii\web\View */

use yii\helpers\Url;
use frontend\components\LotBlock;
use frontend\components\SearchForm;
use frontend\components\LotDetailSidebar;
use common\models\Query\Settings;



$this->title = Yii::$app->params['title'];

if ($type == 'bankrupt') {
    $title = 'Единая база торгоа <span class="font200 block">Имущество банкротов</span>';
    $description = 'В нашей базе собрана исключительно актуальная информация об имущество банкротов и должников, выставленном и тендеры и торги на ЭТП и публичных аукционах.';
} else {
    $title = 'Единая база торгоа <span class="font200">Арестованное имущество</span>';
    $description = '';
}
?>

<div class="hero-banner hero-banner-01 overlay-light opacity-2 overlay-relative overlay-gradient gradient-white alt-option-03" style="background-image:url('img/01.jpg'); background-position: top  center;">
        
    <div class="overlay-holder bottom"></div>	
    
    <div class="hero-inner">
    
        <div class="container">
            <h1><?=$title?></h1>
            <p class="font-lg spacing-1"><?=$description?></p>
            
            <?= SearchForm::widget(['type' => $type])?>

        </div>
        
    </div>
    
</div>

<section class="pt-70 pb-0">

    <div class="container">

        <div class="clear mb-100"></div>

        <div class="section-title">
            <h2><span><span>Популярные Категории </span> Лотов <?=($type == 'bankrupt')? 'Банкротного имущества': 'Арестованного имущество'?></span></h2>
        </div>
        
        <div class="row cols-1 cols-sm-2 cols-lg-4 gap-2 mb-20">
        
            <div class="col">
            
                <figure class="destination-grid-item-01">
                    <a href="/bankrupt/transport-i-tehnika">
                        <div class="image">
                            <img src="img/image-destination/01.jpg"alt="image"/>
                        </div>
                        <figcaption class="content">
                            <h5>Автомобили</h5>
                            <!-- <p class="text-muted">25 лотов</p> -->
                        </figcaption>
                    </a>
                </figure>
                
            </div>
            
            <div class="col">
            
                <figure class="destination-grid-item-01">
                    <a href="/bankrupt/nedvizhimost">
                        <div class="image">
                            <img src="img/image-destination/02.jpg"alt="image"/>
                        </div>
                        <figcaption class="content">
                            <h5>Недвищимость</h5>
                            <!-- <p class="text-muted">36 лотов</p> -->
                        </figcaption>
                    </a>
                </figure>
                
            </div>
            
            <div class="col">
            
                <figure class="destination-grid-item-01">
                    <a href="/bankrupt/oborudovanie">
                        <div class="image">
                            <img src="img/image-destination/03.jpg"alt="image"/>
                        </div>
                        <figcaption class="content">
                            <h5>Оборудование</h5>
                            <!-- <p class="text-muted">40 лотов</p> -->
                        </figcaption>
                    </a>
                </figure>
                
            </div>
            
            <div class="col">
            
                <figure class="destination-grid-item-01">
                    <a href="/bankrupt/debitorskaya-zadolzhennost">
                        <div class="image">
                            <img src="img/image-destination/04.jpg"alt="image"/>
                        </div>
                        <figcaption class="content">
                            <h5>Дебиторская задолженность</h5>
                            <!-- <p class="text-muted">10 лотов</p> -->
                        </figcaption>
                    </a>
                </figure>
                
            </div>
            
        </div>
    
        <div class="clear mb-100"></div>
                
        <div class="section-title">
            <h2><span><span>Горячие</span> Предложения Дня</span></h2>
        </div>
        
        <div class="row equal-height cols-1 cols-sm-2 cols-lg-3 gap-20 mb-30">

            <?foreach ($lots as $lot) { echo LotBlock::widget(['lot' => $lot]); }?>
            
        </div>

        <div class="clear mb-100"></div>
                
        <div class="section-title">
            <h2><span><span>Интересные</span> Предложения Дня</span></h2>
        </div>
        
        <div class="row equal-height cols-1 cols-sm-2 cols-lg-3 gap-20 mb-30">

            <?foreach ($lotsFovarit as $lotFovarit) { echo LotBlock::widget(['lot' => $lotFovarit]); }?>
            
        </div>
        
        <? if ($type = 'bankrupt') { ?>
        <div class="clear mb-100"></div>

        <div class="row cols-1 cols-sm-2 cols-lg-2 gap-2 mb-20">
        
            <div class="col-lg-1"></div>

            <div class="col-lg-5 col-md">
            
                <figure class="destination-grid-item-01">
                    <a href="<?=Url::to(['arrest/list'])?>">
                        <div class="image">
                            <img src="img/image-destination/01.jpg"alt="image"/>
                        </div>
                        <figcaption class="content">
                            <h5>Арбитражные управляющие</h5>
                            <p class="text-muted">25 человек</p>
                        </figcaption>
                    </a>
                </figure>
                
            </div>
            
            <div class="col-lg-5 col-md">
            
                <figure class="destination-grid-item-01">
                    <a href="<?=Url::to(['doljnik/list'])?>">
                        <div class="image">
                            <img src="img/image-destination/02.jpg"alt="image"/>
                        </div>
                        <figcaption class="content">
                            <h5>Должники</h5>
                            <p class="text-muted">36 человек</p>
                        </figcaption>
                    </a>
                </figure>
                
            </div>

            <div class="col-lg-1"></div>
            
        </div>
        <? } ?>
        
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