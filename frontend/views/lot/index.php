<?php

/* @var $this yii\web\View */

use yii\helpers\Url;
use frontend\components\LotBlock;
use frontend\components\SearchForm;
use frontend\components\LotDetailSidebar;
use common\models\Query\Settings;
use common\models\Query\Zalog\OwnerProperty;


$this->title = Yii::$app->params['title'];

if ($type == 'bankrupt') {
    $title = 'Единая база торгов <span class="font200 block">Имущество банкротов</span>';
    $imgBG = 'img/01.jpg';
    $description = (Yii::$app->params['text'])? Yii::$app->params['text'] : 'В нашей базе собрана исключительно актуальная информация об имущество банкротов и должников, выставленном и тендеры и торги на ЭТП и публичных аукционах.';
} else if ($type == 'zalog') {
    $title = 'Единая база торгов <span class="font200"><br>Залогового имущество</span>';
    $imgBG = 'img/01.jpg';
    $description = Yii::$app->params['text'];
}else if ($type == 'arrest') {
    $title = 'Единая база торгов <span class="font200"><br>Арестованное имущество</span>';
    $imgBG = 'img/01.jpg';
    $description = Yii::$app->params['text'];
} else {
    $title = 'Единая база торгов <span class="font200"'.(($owner->tamplate['color-5'])? 'style="color: '.$owner->tamplate['color-5'].'"': '').'><br>'.$owner->name.'</span>';
    $imgBG = 'http://n.ei.ru'.$owner->tamplate['bg'];
    $description = $owner->description;
    
    // ID организации: $owner->id
    // Название: $owner->name
    // Логотип: $owner->logo
    // Ссылка: $owner->link
    // Описание: $owner->description
    // Телефон: $owner->phone
    // E-mail: $owner->email
    // Страна: $owner->country
    // Город: $owner->city
    // Адрес: $owner->address
    // Ссылка на нашем сайте: $owner->linkForEi
    // Фоновая картинка: $owner->tamplate['bg']
    // Цвет 1: $owner->tamplate['color-1']
    // Цвет 2: $owner->tamplate['color-2']
    // Цвет 3: $owner->tamplate['color-3']
    // Цвет 4: $owner->tamplate['color-4']
    // Цвет 5: $owner->tamplate['color-5']
    // Цвет 6: $owner->tamplate['color-6']
    // Дата добавления: $owner->createdAt
}
?>

<div class="hero-banner hero-banner-01 overlay-light opacity-2 overlay-relative overlay-gradient gradient-white alt-option-03" style="background-image:url('<?=$imgBG?>'); background-position: top  center;">
        
    <div class="overlay-holder bottom"></div>	
    
    <div class="hero-inner">
    
        <div class="container">
            <h1><?=$title?></h1>
            <p class="font-lg spacing-1" <?=($owner->tamplate['color-5'])? 'style="color: '.$owner->tamplate['color-5'].'"': ''?>><?=$description?></p>
            
            <?= SearchForm::widget(['type' => (($type == 'bankrupt' || $type == 'arrest' || $type == 'zalog')? $type : 'zalog'), 'typeZalog' => ($type !== 'bankrupt' || $type !== 'arrest' || $type !== 'zalog')? $type : null, 'btnColor' => $owner->tamplate['color-1'], 'color' => $owner->tamplate['color-4']])?>

        </div>
        
    </div>
    
</div>

<section class="pt-70 pb-0">

    <div class="container">

        <div class="clear mb-100"></div>

        <div class="section-title-">
            <h2 class="h3 mb-20 font-weight-400">Популярные категории</h2>
        </div>
        
        <div class="row cols-1 cols-sm-2 cols-lg-4 gap-2 mb-20">
        
        <div class="col">
            
            <figure class="category__item color-1" <?=($owner->tamplate['color-2'])? 'style="background-color: '.$owner->tamplate['color-2'].'"': ''?>>
                <a href="<?=$type?>/transport-i-tehnika">
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
        
            <figure class="category__item color-2" <?=($owner->tamplate['color-3'])? 'style="background-color: '.$owner->tamplate['color-3'].'"': ''?>>
                <a href="/<?=$type?>/nedvizhimost">
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
        
            <figure class="category__item color-3" <?=($owner->tamplate['color-1'])? 'style="background-color: '.$owner->tamplate['color-1'].'"': ''?>>
                <a href="/<?=$type?>/oborudovanie">
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
        
            <figure class="category__item color-4" <?=($owner->tamplate['color-4'])? 'style="background-color: '.$owner->tamplate['color-4'].'"': ''?>>
                <a href="/<?=$type?>/debitorskaya-zadolzhennost">
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
                
        <div class="section-title-">
            <h2 class="h3 mb-20">Горячие предложения дня</h2>
        </div>
        
        <div class="row equal-height cols-1 cols-sm-2 cols-lg-3 gap-20 mb-30">

            <?foreach ($lots as $lot) { echo LotBlock::widget(['lot' => $lot, 'color' => $owner->tamplate['color-4']]); }?>
            
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