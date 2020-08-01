<?php

/* @var $this yii\web\View */


use common\models\db\Owner;
use frontend\modules\components\LotBlockSmall;
use frontend\modules\components\SearchForm;
use common\models\Query\LotsCategory;
use common\models\Query\Regions;
use common\models\db\Lot;
use common\models\db\LotPrice;
use common\models\db\Report;
use common\models\db\Torg;
use frontend\modules\models\LotSearch;
use yii\helpers\Html;
use yii\helpers\Url;


$this->title = Yii::$app->params[ 'title' ];
$regions = Regions::find()->orderBy('id ASC')->all();
$lotsCategory = LotsCategory::find()->where(['or', ['not', ['bankrupt_categorys' => null]], ['translit_name' => 'lot-list']])->orderBy('id ASC')->all();

// $LotSearch = new LotSearch();
$lotPopular = Lot::find()->joinWith(['torg']);
$lotPopular->rightJoin(LotPrice::tableName(), LotPrice::tableName() . '.lot_id = ' . Lot::tableName() . '.id');
$today = new \DateTime();
$lotPopular->where([
    'and',
    ['!=', Lot::tableName() . '.status', Lot::STATUS_COMPLETED],
    ['>', Torg::tableName() . '.end_at', time()],
    ['<=', LotPrice::tableName() . '.started_at', \Yii::$app->formatter->asTimestamp($today)],
    ['>=', LotPrice::tableName() . '.end_at', \Yii::$app->formatter->asTimestamp($today)]
]);
$lotPopularList = $lotPopular->orderBy([LotPrice::tableName() . '.id' => SORT_DESC, Torg::tableName() . '.published_at' => SORT_DESC])->limit(7)->all();

$lotNew = Lot::find()->joinWith(['torg']);
$lotNew->where(['!=', Lot::tableName() . '.status', Lot::STATUS_COMPLETED]);
$lotNew->andWhere(['>', Torg::tableName() . '.end_at', time()]);
$lotNew->innerJoin(Report::tableName(), 'report.lot_id = lot.id');
$lotNew->groupBy([
    Lot::tableName() . '.id', 
    Lot::tableName() . '.torg_id', 
    Lot::tableName() . '.ordinal_number', 
    Lot::tableName() . '.title', 
    Lot::tableName() . '.description', 
    Lot::tableName() . '.start_price', 
    Lot::tableName() . '.step', 
    Lot::tableName() . '.step_measure', 
    Lot::tableName() . '.deposit', 
    Lot::tableName() . '.deposit_measure', 
    Lot::tableName() . '.status', 
    Lot::tableName() . '.status_changed_at', 
    Lot::tableName() . '.reason', 
    Lot::tableName() . '.url', 
    Lot::tableName() . '.info', 
    Lot::tableName() . '.created_at', 
    Lot::tableName() . '.updated_at', 
    Torg::tableName() . '.published_at', 
]);
$newLots = $lotNew->orderBy(['count('.Report::tableName() . '.id)' => SORT_DESC, Torg::tableName() . '.published_at' => SORT_DESC])->limit(7)->all();

$lotEnd = Lot::find()->joinWith(['torg']);
$lotEnd->andWhere(['<=', Torg::tableName() . '.end_at', \Yii::$app->formatter->asTimestamp($today)]);
$lotEndList = $lotEnd->orderBy([Torg::tableName() . '.end_at' => SORT_DESC])->limit(3)->all();

$lotLowPrice = Lot::find()->joinWith(['torg']);
$lotLowPrice->where([
    'and',
    ['!=', Lot::tableName() . '.status', Lot::STATUS_COMPLETED],
    ['>', Torg::tableName() . '.end_at', time()],
    ['<', Lot::tableName() . '.start_price', 100],
]);
$lotLowPriceList = $lotLowPrice->orderBy([Torg::tableName() . '.published_at' => SORT_DESC])->limit(3)->all();
?>

<div class="hero-banner hero-banner-01 overlay-light opacity-2 overlay-relative overlay-gradient gradient-white alt-option-03"
    style="background-color: #E6EFF4">

    <div class="overlay-holder bottom"></div>

    <div class="hero-inner">

        <div class="container" style="padding-top: 120px;padding-bottom: 60px;">

            <h1>
                <!-- <span class="font700 main-page__title">Единый информатор<br><span class="main-page__subtitle">Полный каталог реализуемого имущества организаций, должников и банков России</span> </span></h1> -->
                <span class="font700 main-page__title">Все торги в одном месте <span class="font100"> — банкротные и арестованные торги, имущество банков и лизинговых компаний</span></span>
            </h1>
            <!-- <p class="main-page__subtitle"></p> -->

            <!-- </span><span class="font200">торгов<span class="block"> <span class="font700">по</span> банкротству</span></span> -->
            <?= SearchForm::widget(['type' => 'bankrupt', 'url' => 'all']) ?>

        </div>

    </div>

</div>

<div class="slick-list-visible">
			
    <div class="container">

        <div class="slick-carousel-wrapper gap-5">
            
            <div class="slick-carousel-inner slider">

                <div class="slick-top-destination">
                    
                    <div class="slick-item d-flex justify-content-center">
                        <div class="slider__item pink">
                            <div class="slider__item__title">У вас имеется неликвидное имущество? Размещайте его у нас!</div>
                            <p class="slider__item__text">В нашем маркетплейсе имущество тысячи организаций</p>
                            <a href="/contact" class="slider__item__link">
                                Узнать условия
                                <i class="ion-ios-arrow-forward"></i>
                            </a>
                            <img src="./img/mercedes.png" alt="">
                        </div>
                    </div>
                    <div class="slick-item d-flex justify-content-center">
                        <div class="slider__item green">
                            <div class="slider__item__title">Хотите заработать? Создавайте отчеты — мы покупаем дорого!</div>
                            <p class="slider__item__text">В нашем маркетплейсе имущество тысячи организаций</p>
                            <a href="/contact" class="slider__item__link">
                                Стать агентом ei
                                <i class="ion-ios-arrow-forward"></i>
                            </a>
                            <img src="./img/mercedes.png" alt="">
                        </div>
                    </div>
                    <div class="slick-item d-flex justify-content-center">
                        <div class="slider__item orange">
                            <div class="slider__item__title">Вы арбитражный управляющий? </div>
                            <p class="slider__item__text">Пройдите авторизацию и управляйте публикацией своего имущества и не только</p>
                            <a href="/contact" class="slider__item__link">
                                Пройти модерацию в CRM арбитражника
                                <i class="ion-ios-arrow-forward"></i>
                            </a>
                            <img src="./img/mercedes.png" alt="">
                        </div>
                    </div>
                    <div class="slick-item d-flex justify-content-center">
                        <div class="slider__item blue">
                            <div class="slider__item__title">Нашли имущество. которое хотите купить?</div>
                            <p class="slider__item__text">Выкупим для Вас любое имущество от 3500 руб. за лот</p>
                            <a href="/contact" class="slider__item__link">
                                Узнайте подробности
                                <i class="ion-ios-arrow-forward"></i>
                            </a>
                            <img src="./img/mercedes.png" alt="">
                        </div>
                    </div>
                    <div class="slick-item d-flex justify-content-center">
                        <div class="slider__item yellow">
                            <div class="slider__item__title">Следите за лотами бесплатно!</div>
                            <p class="slider__item__text">Добавляйте лот в избранное и получайте уведомления о снижении цены, новых материалах и доступных отчетах по лоту</p>
                            <a href="/contact" class="slider__item__link">
                                Узнайте подробности
                                <i class="ion-ios-arrow-forward"></i>
                            </a>
                            <img src="./img/mercedes.png" alt="">
                        </div>
                    </div>
                    <div class="slick-item d-flex justify-content-center">
                        <div class="slider__item red">
                            <div class="slider__item__title">Хочется дом у воды?  Все лоты на карте </div>
                            <p class="slider__item__text">Лоты по всем типам имущества на карте!</p>
                            <a href="/contact" class="slider__item__link">
                                Узнайте подробности
                                <i class="ion-ios-arrow-forward"></i>
                            </a>
                            <img src="./img/mercedes.png" alt="">
                        </div>
                    </div>
                    
                    
                </div>
            
            </div>
            
        </div>
        
    </div>
    
</div>

<div class="mb-50"></div>

<section class="pt-0 pb-0 mb-50">
    <div class="container">
        <div class="clear"></div>
        <!-- <h2 class="h3 mt-40 line-125 ">Категории</h2> -->

        <div class="row">
            <div class="col-lg-2 col-sm-4 col-6">
                <a href="/all/transport-i-tehnika" class="category">
                    <div class="category__img">
                        <img src="./img/category/auto.png" alt="">
                    </div>
                    <span class="category__name">Транспорт</span>
                </a>
            </div>
            <div class="col-lg-2 col-sm-4 col-6">
                <a href="/all/transport-i-tehnika" class="category">
                    <div class="category__img">
                        <img src="./img/category/build.png" alt="">
                    </div>
                    <span class="category__name">Недвижимость</span>
                </a>
            </div>
            <div class="col-lg-2 col-sm-4 col-6">
                <a href="/all/transport-i-tehnika" class="category">
                    <div class="category__img">
                        <img src="./img/category/plant.png" alt="">
                    </div>
                    <span class="category__name">Земельные участки</span>
                </a>
            </div>
            <div class="col-lg-2 col-sm-4 col-6">
                <a href="/all/transport-i-tehnika" class="category">
                    <div class="category__img">
                        <img src="./img/category/track.png" alt="">
                    </div>
                    <span class="category__name">Спецтехника</span>
                </a>
            </div>
            <div class="col-lg-2 col-sm-4 col-6">
                <a href="/all/transport-i-tehnika" class="category">
                    <div class="category__img">
                        <img src="./img/category/combain.png" alt="">
                    </div>
                    <span class="category__name">Сельхозтехника</span>
                </a>
            </div>
            <div class="col-lg-2 col-sm-4 col-6">
                <a href="/all/transport-i-tehnika" class="category">
                    <div class="category__img">
                        <img src="./img/category/money.png" alt="">
                    </div>
                    <span class="category__name">Дебиторская задолженность</span>
                </a>
            </div>
        </div>
    </div>
</section>

<? if (count($lots = $newLots) > 0) { ?>
<section class="pt-0 pb-0">
    <div class="container">
        <h2 class="h3 mt-40 line-125 ">Лоты с отчётами экспертов</h2>


        <div class="row">
            <? foreach ($lots as $lot) { ?>
                <div class="col-lg-3 col-sm-6 mb-40" itemscope itemtype="http://schema.org/Product">
                    <?= LotBlockSmall::widget(['lot' => $lot, 'url' => $url]) ?>
                </div>
            <? } ?>
            <div class="col-lg-3 col-sm-6 mb-40 lot_next__btn">
                <a href="/all/lot-list?LotSearch%5Bsearch%5D=&LotSearch%5Bregion%5D=&LotSearch%5BminPrice%5D=&LotSearch%5BmaxPrice%5D=&LotSearch%5Betp%5D=&LotSearch%5BtradeType%5D=&LotSearch%5BandArchived%5D=0&LotSearch%5BhaveImage%5D=0&LotSearch%5BhasReport%5D=1&LotSearch%5BpriceDown%5D=0&LotSearch%5Befrsb%5D=&LotSearch%5BbankruptName%5D=&LotSearch%5BtorgDateRange%5D=&LotSearch%5BstartApplication%5D=0&LotSearch%5BcompetedApplication%5D=0" class="btn btn-primary borr-10">
                    Больше предложений
                    <i class="fa fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <div class="clear mb-50"></div>
    </div>
</section>
<? } ?>

<? if (count($lots = $lotPopularList) > 0) { ?>
<section class="pt-0 pb-0">
    <div class="container">
        <h2 class="h3 mt-40 line-125 ">Цена снижена</h2>


        <div class="row">
            <? foreach ($lots as $lot) { ?>
                <div class="col-lg-3 col-sm-6 mb-40" itemscope itemtype="http://schema.org/Product">
                    <?= LotBlockSmall::widget(['lot' => $lot, 'url' => $url]) ?>
                </div>
            <? } ?>
            <div class="col-lg-3 col-sm-6 mb-40 lot_next__btn">
                <a href="/bankrupt/lot-list?LotSearch%5Bsearch%5D=&LotSearch%5Bregion%5D=&LotSearch%5BminPrice%5D=&LotSearch%5BmaxPrice%5D=&LotSearch%5Betp%5D=&LotSearch%5BtradeType%5D=&LotSearch%5BandArchived%5D=0&LotSearch%5BhaveImage%5D=0&LotSearch%5BhasReport%5D=0&LotSearch%5BpriceDown%5D=1&LotSearch%5Befrsb%5D=&LotSearch%5BbankruptName%5D=&LotSearch%5BtorgDateRange%5D=&LotSearch%5BstartApplication%5D=0&LotSearch%5BcompetedApplication%5D=0" class="btn btn-primary borr-10">
                    Больше предложений
                    <i class="fa fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <div class="clear mb-50"></div>
    </div>
</section>
<? } ?>


<? if (count($lots = $lotEndList) > 0) { ?>
<section class="pt-0 pb-0">
    <div class="container">
        <h2 class="h3 mt-40 line-125 ">Торги закончены</h2>


        <div class="row">
            <? foreach ($lots as $lot) { ?>
                <div class="col-lg-3 col-sm-6 mb-40" itemscope itemtype="http://schema.org/Product">
                    <?= LotBlockSmall::widget(['lot' => $lot, 'url' => $url]) ?>
                </div>
            <? } ?>
            <div class="col-lg-3 col-sm-6 mb-40 lot_next__btn">
                <a href="/all/lot-list?LotSearch%5Bsearch%5D=&LotSearch%5Bregion%5D=&LotSearch%5BminPrice%5D=&LotSearch%5BmaxPrice%5D=&LotSearch%5BtradeType%5D=&LotSearch%5BandArchived%5D=1&LotSearch%5BhaveImage%5D=0&LotSearch%5BhasReport%5D=0" class="btn btn-primary borr-10">
                    Перейти в архив
                    <i class="fa fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <div class="clear mb-50"></div>
    </div>
</section>
<? } ?>

<? if (count($lots = $lotLowPriceList) > 0) { ?>
<section class="pt-0 pb-0">
    <div class="container">
        <h2 class="h3 mt-40 line-125 ">Лоты дешевле 100 руб.</h2>


        <div class="row">
            <? foreach ($lots as $lot) { ?>
                <div class="col-lg-3 col-sm-6 mb-40" itemscope itemtype="http://schema.org/Product">
                    <?= LotBlockSmall::widget(['lot' => $lot, 'url' => $url]) ?>
                </div>
            <? } ?>
            <div class="col-lg-3 col-sm-6 mb-40 lot_next__btn">
                <a href="/all/lot-list?LotSearch%5Bsearch%5D=&LotSearch%5Bregion%5D=&LotSearch%5BminPrice%5D=&LotSearch%5BmaxPrice%5D=100&LotSearch%5BtradeType%5D=&LotSearch%5BandArchived%5D=0&LotSearch%5BhaveImage%5D=0&LotSearch%5BhasReport%5D=0" class="btn btn-primary borr-10">
                    Большое лотов
                    <i class="fa fa-arrow-right"></i>
                </a>
            </div>
        </div>
        

        <div class="clear mb-50"></div>
    </div>
</section>
<? } ?>

<section class="pt-0 pb-0">

    <div class="container">

        <div class="clear mb-50"></div>

        <div class="row cols-1 cols-sm-2 cols-lg-4 gap-10 mb-20">

            <div class="col">

                <figure style="background-color: #234559;" class="category__item">
                    <a href="/bankrupt/lot-list">
                        <div class="image">
                            <img src="img/bankrupt.jpg" alt="image" />
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
                            <img src="https://images.unsplash.com/photo-1513496335913-a9aab0fc1318?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=500&q=80"
                                alt="image" />
                        </div>
                        <figcaption class="content">
                            <div class="content__wrapper">
                                <h6>Имущество организаций</h6>
                                <!-- <p ><? // $lotsZalogCount ?> лотов</p> -->
                            </div>
                        </figcaption>
                    </a>
                </figure>

            </div>

            <div class="col">
                <figure class="category__item" style="background-color: #555e63;">
                    <div href="/bankrupt/debitorskaya-zadolzhennost">
                        <div class="image">
                            <img src="https://cdn.govexec.com/media/img/upload/2015/09/03/090415EIG_personnel_files/open-graph.jpg"
                                alt="image" />
                        </div>
                        <figcaption class="content">
                            <div class="content__wrapper">
                                <h6>Реестры</h6>
                                <ul class="category__links">
                                    <li><a href="/arbitrazhnye-upravlyayushchie">Арбитражные управляющие
                                            <!--<span>1999</span>--></a></li>
                                    <li><a href="/dolzhniki">Должники
                                            <!--<span>1999</span>--></a></li>
                                    <li><a href="/sro">СРО
                                            <!--<span>1999</span>--></a></li>
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

<section class="pt-0 pb-0 p-20">
    <div class="container main-page">
        <div class="row main-page__category" itemscope itemtype="https://schema.org/AggregateOffer">

            <div class="col-lg-4 main-page__link-item">
                <div class="main-page__category__block">
                    <p class="h4 font600">Секции имущества</p>
                    <hr>
                    <ul>
                        <li><a href="/all/lot-list"> Все имущество
                                <!--<span>1999</span>--></a></li>
                        <li><a href="/bankrupt/lot-list" itemprop="https://schema.org/itemOffered"> Банкротное имущество
                                <!--<span>1999</span>--></a></li>
                        <li><a href="/arrest/lot-list" itemprop="https://schema.org/itemOffered"> Арестованное имущество
                                <!--<span>1999</span>--></a></li>
                        <li><a href="/municipal/lot-list" itemprop="https://schema.org/itemOffered"> Муниципальное
                                имущество
                                <!--<span>1999</span>--></a></li>
                        <?php $ownerList = Owner::getOrganizationList(); ?>
                        <?php foreach ($ownerList as $key => $item) : ?>
                        <li><a href="<?= Url::to(['zalog/lot-list', 'LotSearch[owner]' => $key]) ?>"
                                itemprop="https://schema.org/itemOffered"><?= $item ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <div class="col-lg-4 main-page__link-item">
                <div class="main-page__category__block">
                    <p class="h4 font600">Категории</p>
                    <hr>
                    <ul>
                        <?php foreach ($lotsCategory as $category) {
                            echo '<li><a href="/all/' . $category[ 'translit_name' ] . '" itemprop="category">' . $category[ 'name' ] . '   <!--<span>1999</span>--></a></li>';
                        } ?>
                    </ul>
                </div>
            </div>
            <div class="col-lg-4 main-page__link-item">
                <div class="main-page__category__block">
                    <p class="h4 font600">Регионы</p>
                    <hr>
                    <ul>
                        <li><a href="/all/lot-list"> Россия
                                <!--<span>1999</span>--></a></li>
                        <li><a href="/all/lot-list?LotSearch%5Bregion%5D=77"> Москва
                                <!--<span>1999</span>--></a></li>
                        <li><a href="/all/lot-list?LotSearch%5Bregion%5D=50"> Московская область
                                <!--<span>1999</span>--></a></li>
                        <li><a href="/all/lot-list?LotSearch%5Bregion%5D=78"> Санкт-Петербург
                                <!--<span>1999</span>--></a></li>
                        <li><a href="/all/lot-list?LotSearch%5Bregion%5D=47"> Ленинградская область
                                <!--<span>1999</span>--></a></li>
                        <li><a href="/all/lot-list?LotSearch%5Bregion%5D=23"> Краснодарский край
                                <!--<span>1999</span>--></a></li>
                        <li><a href="/all/lot-list?LotSearch%5Bregion%5D=66"> Свердловская область
                                <!--<span>1999</span>--></a></li>
                        <li><a href="/all/lot-list?LotSearch%5Bregion%5D=16"> Республика Татарстан
                                <!--<span>1999</span>--></a></li>
                        <li><a href="/all/lot-list?LotSearch%5Bregion%5D=52"> Нижегородская область
                                <!--<span>1999</span>--></a></li>
                        <li><a href="/all/lot-list?LotSearch%5Bregion%5D=61"> Ростовская область
                                <!--<span>1999</span>--></a></li>
                        <li><a href="/all/lot-list?LotSearch%5Bregion%5D=74"> Челябинская область
                                <!--<span>1999</span>--></a></li>
                        <li><a href="/all/lot-list"> Другие регионы
                                <!--<span>1999</span>--></a></li>
                        <? //foreach ($regions as $region) {
                        // echo '<li><a href="/all/lot-list?LotSearch%5Bregion%5D='.$region['id'].'"> '.$region['name'].' <!--<span>1999</span>--></a></li>';
                        //} ?>
                    </ul>
                </div>
            </div>

        </div>
    </div>
    <div class="clear mb-50"></div>
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