<?php

/* @var $this yii\web\View */

use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use frontend\components\LotBlock;
use frontend\components\SearchForm;
use frontend\components\LotDetailSidebar;

use common\models\Query\Settings;
use common\models\Query\LotsCategory;
use common\models\Query\Regions;



$this->title = Yii::$app->params['title'];
$regions = Regions::find()->orderBy('id ASC')->all();
$lotsCategory = LotsCategory::find()->where(['or', ['not', ['bankrupt_categorys' => null]], ['translit_name' => 'lot-list']])->orderBy('id ASC')->all();
?>



<div class="hero-banner hero-banner-01 overlay-light opacity-2 overlay-relative overlay-gradient gradient-white alt-option-03" style="background-image:url('img/01.jpg'); background-position: top  center;">

  <div class="overlay-holder bottom"></div>

  <div class="hero-inner">

    <div class="container pt5">

        <h1>
            <!-- <span class="font700 main-page__title">Единый информатор<br><span class="main-page__subtitle">Полный каталог реализуемого имущества организаций, должников и банков России</span> </span></h1> -->
            <span class="font700 main-page__title">Имущество организаций России<br></span>
        </h1>
            <p class="main-page__subtitle">Агрегатор банкротных и арестованных торгов, имущества банков, лизинговых компаний, залоговое имущество</p> 
                
        <!-- </span><span class="font200">торгов<span class="block"> <span class="font700">по</span> банкротству</span></span> -->
      <?= SearchForm::widget(['type' => 'bankrupt', 'url' => 'all']) ?>

    </div>

  </div>

</div>

<section class="pt-0 pb-0 p-20">
    <div class="container main-page">
        <div class="row main-page__category" itemscope itemtype="https://schema.org/AggregateOffer">

            <div class="col-lg-4 main-page__link-item">
                <div class="main-page__category__block">
                    <p class="h4">Секции имущества</p>
                    <hr>
                    <ul>
                        <li><a href="/all/lot-list"> Все имущество <!--<span>1999</span>--></a></li>
                        <li><a href="/bankrupt/lot-list" itemprop="https://schema.org/itemOffered"> Банкротное имущество <!--<span>1999</span>--></a></li>
                        <li><a href="/arrest/lot-list" itemprop="https://schema.org/itemOffered"> Арестованное имущество <!--<span>1999</span>--></a></li>
                        <li><a href="/caterpillar/lot-list" itemprop="https://schema.org/itemOffered">Caterpillar<!--<span>1999</span>--></a></li>
                        <!-- <li><a href="/portal-da">Portal Da</a></li> -->
                        <li><a href="/open-bank/lot-list" itemprop="https://schema.org/itemOffered">Банк Открытие<!--<span>1999</span>--></a></li>
                        <li><a href="/gilfondrt/lot-list" itemprop="https://schema.org/itemOffered">ГЖФ при Президенте РТ<!--<span>1999</span>--></a></li>
                        <li><a href="/greentau/lot-list" itemprop="https://schema.org/itemOffered">Гринтау<!--<span>1999</span>--></a></li>
                        <li><a href="/rosselkhozbank/lot-list" itemprop="https://schema.org/itemOffered">Россельхозбанк<!--<span>1999</span>--></a></li>
                        <li><a href="/sberbank/lot-list" itemprop="https://schema.org/itemOffered">Сберабанк<!--<span>1999</span>--></a></li>
                        <li><a href="/cdtrf/lot-list" itemprop="https://schema.org/itemOffered">Центр дистанционных торгов<!--<span>1999</span>--></a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-4 main-page__link-item">
                <div class="main-page__category__block">
                    <p class="h4">Категории</p>
                    <hr>
                    <ul>
                        <? foreach ($lotsCategory as $category) {
                            echo '<li><a href="/all/'.$category['translit_name'].'" itemprop="category">'.$category['name'].'   <!--<span>1999</span>--></a></li>';
                        } ?>
                    </ul>
                </div>
            </div>
            <div class="col-lg-4 main-page__link-item">
                <div class="main-page__category__block">
                    <p class="h4">Регионы</p>
                    <hr>
                    <ul>
                        <li><a href="/all/lot-list"> Россия <!--<span>1999</span>--></a></li>
                        <li><a href="/all/lot-list?SearchLot%5Bregion%5D=77"> Москва <!--<span>1999</span>--></a></li>
                        <li><a href="/all/lot-list?SearchLot%5Bregion%5D=50"> Московская область <!--<span>1999</span>--></a></li>
                        <li><a href="/all/lot-list?SearchLot%5Bregion%5D=78"> Санкт-Петербург <!--<span>1999</span>--></a></li>
                        <li><a href="/all/lot-list?SearchLot%5Bregion%5D=47"> Ленинградская область <!--<span>1999</span>--></a></li>
                        <li><a href="/all/lot-list?SearchLot%5Bregion%5D=23"> Краснодарский край <!--<span>1999</span>--></a></li>
                        <li><a href="/all/lot-list?SearchLot%5Bregion%5D=66"> Свердловская область <!--<span>1999</span>--></a></li>
                        <li><a href="/all/lot-list?SearchLot%5Bregion%5D=16"> Республика Татарстан <!--<span>1999</span>--></a></li>
                        <li><a href="/all/lot-list?SearchLot%5Bregion%5D=52"> Нижегородская область <!--<span>1999</span>--></a></li>
                        <li><a href="/all/lot-list?SearchLot%5Bregion%5D=61"> Ростовская область <!--<span>1999</span>--></a></li>
                        <li><a href="/all/lot-list?SearchLot%5Bregion%5D=74"> Челябинская область <!--<span>1999</span>--></a></li>
                        <li><a href="/all/lot-list"> Другие регионы <!--<span>1999</span>--></a></li>
                        <? //foreach ($regions as $region) {
                           // echo '<li><a href="/all/lot-list?SearchLot%5Bregion%5D='.$region['id'].'"> '.$region['name'].' <!--<span>1999</span>--></a></li>';
                        //} ?>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</section>

<section class="pt-0 pb-0">

  <div class="container">

    <div class="clear mb-50"></div>

    <div class="row cols-1 cols-sm-2 cols-lg-4 gap-10 mb-20">

      <div class="col">

        <figure style="background-color: #234559;" class="category__item">
          <a href="/bankrupt/lot-list">
            <div class="image">
              <img src="http://www.femak-kazan.com/wp-content/uploads/2018/06/bigstock-Chemical-Plant-Structure-47755258-400x400.jpg" alt="image" />
            </div>
            <figcaption class="content">
              <div class="content__wrapper">
                <h6>Банкротное<br>имущество</h6>
                <!-- <p><? //$lotsBankruptCount ?> лотов</p> -->
              </div>
            </figcaption>
          </a>
        </figure>

      </div>

      <div class="col">


        <figure class="category__item" style="background-color: #005639;">
          <a href="/arrest/lot-list">
            <div class="image">
              <img src="https://cdn-st4.rtr-vesti.ru/vh/pictures/bq/142/045/9.jpg" alt="image" />
            </div>
            <figcaption class="content">
              <div class="content__wrapper">
                <h6>Арестованное <br>имущество</h6>
                <!-- <p><? //$lotsArrestCount ?> лотов</p> -->
                <div>
            </figcaption>
          </a>
        </figure>

      </div>

      <div class="col">

        <figure class="category__item" style="background-color:#2b8ac6;">
            <a href="/zalog/lot-list">
                <div class="image">
                <img src="https://images.unsplash.com/photo-1513496335913-a9aab0fc1318?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=500&q=80" alt="image"/>
                </div>
                <figcaption class="content">
                    <div class="content__wrapper">
                    <h6>Имущество организаций</h6>
                    <!-- <p ><?// $lotsZalogCount ?> лотов</p> -->
                    </div>
                </figcaption>
            </a>
        </figure>
        
    </div>

      <div class="col">
        <figure class="category__item" style="background-color: #555e63;">
          <div href="/bankrupt/debitorskaya-zadolzhennost">
            <div class="image">
              <img src="https://cdn.govexec.com/media/img/upload/2015/09/03/090415EIG_personnel_files/open-graph.jpg" alt="image" />
            </div>
            <figcaption class="content">
              <div class="content__wrapper">
                <h6>Реестры</h6>
                <ul class="category__links">
                  <li><a href="/arbitrazhnye-upravlyayushchie">Арбитражные управляющие<!--<span>1999</span>--></a></li>
                  <li><a href="/dolzhniki">Должники<!--<span>1999</span>--></a></li>
                  <li><a href="/sro">СРО<!--<span>1999</span>--></a></li>
                </ul>
              </div>
            </figcaption>
          </div>
        </figure>

      </div>

    </div>

    <!-- <div class="section-title">
            <h2><span><span>Горячие</span> Предложения Дня</span></h2>
        </div>
        
        <div class="row equal-height cols-1 cols-sm-2 cols-lg-3 gap-20 mb-30">

            <? //foreach ($lots as $lot) { echo LotBlock::widget(['lot' => $lot]); }
            ?>
            
        </div>

        <div class="clear mb-100"></div> -->


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

</div> -->

<!-- <section class="pt-40 pb-100">

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