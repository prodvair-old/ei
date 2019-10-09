<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;
use yii\widgets\Menu;
use common\models\Query\Settings;

$setting = Settings::find()->orderBy('id ASC')->all();
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>

	<!-- Title Of Site -->
    <title><?= Html::encode($this->title) ?></title>

	<meta name="description" content="Tour and Travel Bootstrap 4 HTML Template" />
	<meta name="keywords" content="tour, tour agency, tour operator, tour package, tourism, travel, travel agency, trip, vacation, holiday, travel booking, tour booking, booking, " />
	<meta name="author" content="zh-ar.ru">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	
	<!-- Fav and Touch Icons -->
	<link rel="apple-touch-icon" sizes="180x180" href="<?= Url::to('/img/favicon/apple-touch-icon.png', true)?>">
    <link rel="icon" type="image/png" sizes="32x32" href="">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= Url::to('/img/favicon/favicon-16x16.png', true)?>">
    <link rel="manifest" href="<?= Url::to('/img/favicon/site.webmanifest', true)?>">
    <link rel="mask-icon" href="<?= Url::to('/img/favicon/safari-pinned-tab.svg', true)?>" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">

    <link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css" media="screen">	

    <?php $this->head() ?>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body class="with-waypoint-sticky">
<?php $this->beginBody() ?>

<!-- start Body Inner -->
<div class="body-inner">
	
	
    <!-- start Header -->
    <header id="header-waypoint-sticky" class="header-main header-mobile-menu with-absolute-navbar">
  
        <div class="header-outer clearfix">

            <div class="header-inner">
                            
                <div class="row shrink-auto-lg gap-0 align-items-center">
                
                    <div class="col-5 col-shrink">
                        <div class="col-inner">
                            <div class="main-logo">
                                <a href="<?=Url::to(['site/index'])?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="logo" width="168" height="150" viewBox="0 0 1680 1500"
                                        shape-rendering="geometricPrecision">
                                        <path class="logo-icon"
                                        d="M753.7 88.8c350.6 0 637.5 272.7 660.2 617.6h-132.7c-22.4-271.7-250-485.3-527.5-485.3-292.4 0-529.4 237-529.4 529.4 0 291 234.8 527.1 525.3 529.3 1.3.1 2.7.2 4.1.2 24.4 0 44.1-19.8 44.1-44.2 0-24.3-19.7-44.1-44.1-44.1v-.1c-243.6 0-441.1-197.5-441.1-441.1 0-243.7 197.5-441.2 441.1-441.2 228.8 0 416.8 174.2 438.9 397.1h-133.2C1038 556.8 909.3 441.7 753.7 441.7c-170.5 0-308.8 138.2-308.8 308.8 0 169.2 136.1 306.6 304.8 308.7 2.6.2 5.1.2 7.6 0 14.3-.1 28.5-1.3 42.6-3.4 23.4-1.1 42-20.4 42-44.1 0-24.3-19.7-44.1-44.1-44.1-1.9 0-3.9.1-5.8.4-12.6 2.2-25.5 3.2-38.3 3.2-121.8 0-220.6-98.8-220.6-220.6S631.9 530 753.7 530c106.7 0 195.7 75.8 216.1 176.4H753.7c-24.3 0-44.1 19.9-44.1 44.1 0 24.3 19.8 44.2 44.1 44.2h705.8c24.3 0 44.1-19.9 44.1-44.2C1503.6 336.3 1167.9.6 753.7.6S3.8 336.3 3.8 750.5c0 414.1 335.7 749.9 749.9 749.9 30.2 0 60.4-1.8 90.4-5.5 23.3-1.1 41.9-20.4 41.9-44 0-24.4-19.7-44.1-44.1-44.1-2.6 0-5.1.2-7.5.6-26.8 3.3-53.7 4.8-80.7 4.8-365.4 0-661.7-296.3-661.7-661.7C92 385 388.3 88.8 753.7 88.8z" />
                                        <path class="logo-icon"
                                        d="M753.7 88.8c350.6 0 637.5 272.7 660.2 617.6h-132.7c-22.4-271.7-250-485.3-527.5-485.3-292.4 0-529.4 237-529.4 529.4 0 291 234.8 527.1 525.3 529.3 1.3.1 2.7.2 4.1.2 24.4 0 44.1-19.8 44.1-44.2 0-24.3-19.7-44.1-44.1-44.1v-.1c-243.6 0-441.1-197.5-441.1-441.1 0-243.7 197.5-441.2 441.1-441.2 228.8 0 416.8 174.2 438.9 397.1h-133.2C1038 556.8 909.3 441.7 753.7 441.7c-170.5 0-308.8 138.2-308.8 308.8 0 169.2 136.1 306.6 304.8 308.7 2.6.2 5.1.2 7.6 0 14.3-.1 28.5-1.3 42.6-3.4 23.4-1.1 42-20.4 42-44.1 0-24.3-19.7-44.1-44.1-44.1-1.9 0-3.9.1-5.8.4-12.6 2.2-25.5 3.2-38.3 3.2-121.8 0-220.6-98.8-220.6-220.6S631.9 530 753.7 530c106.7 0 195.7 75.8 216.1 176.4H753.7c-24.3 0-44.1 19.9-44.1 44.1 0 24.3 19.8 44.2 44.1 44.2h705.8c24.3 0 44.1-19.9 44.1-44.2C1503.6 336.3 1167.9.6 753.7.6S3.8 336.3 3.8 750.5c0 414.1 335.7 749.9 749.9 749.9 30.2 0 60.4-1.8 90.4-5.5 23.3-1.1 41.9-20.4 41.9-44 0-24.4-19.7-44.1-44.1-44.1-2.6 0-5.1.2-7.5.6-26.8 3.3-53.7 4.8-80.7 4.8-365.4 0-661.7-296.3-661.7-661.7C92 385 388.3 88.8 753.7 88.8z" />
                                        <path class="logo-acronym"
                                        d="M1636 883c24.2 0 44.1 19.8 44.1 44.1v529.3c0 24.3-19.9 44.1-44.1 44.1-24.3 0-44.2-19.8-44.2-44.1V927.1c0-24.3 19.9-44.1 44.2-44.1zm-215.5 542c10.3-8 17-20.6 17-34.7 0-24.4-19.8-44.2-44.2-44.2-11.4 0-21.9 4.4-29.8 11.6l-.1-.1c-49 35.6-108 54.7-168.6 54.7v88.3c81.1 0 160-26.3 224.9-75l.8-.6zm83.1-233.2c0-170.6-138.2-308.8-308.8-308.8-170.5 0-308.8 138.2-308.8 308.8 0 170.5 138.3 308.8 308.8 308.8v-88.3c-106.7 0-195.7-75.7-216.1-176.4h480.8c24.3 0 44.1-19.9 44.1-44.1zm-524.9-44.1c20.4-100.7 109.4-176.5 216.1-176.5 106.7 0 195.7 75.8 216.2 176.5H978.7zM1636 707.1c24.3 0 44.1 19.7 44.1 44.1 0 24.3-19.8 44.1-44.1 44.1-24.4 0-44.2-19.8-44.2-44.1 0-24.4 19.8-44.1 44.2-44.1z" />
                                        <path class="logo-acronym"
                                        d="M1636 883c24.2 0 44.1 19.8 44.1 44.1v529.3c0 24.3-19.9 44.1-44.1 44.1-24.3 0-44.2-19.8-44.2-44.1V927.1c0-24.3 19.9-44.1 44.2-44.1zm-215.5 542c10.3-8 17-20.6 17-34.7 0-24.4-19.8-44.2-44.2-44.2-11.4 0-21.9 4.4-29.8 11.6l-.1-.1c-49 35.6-108 54.7-168.6 54.7v88.3c81.1 0 160-26.3 224.9-75l.8-.6zm83.1-233.2c0-170.6-138.2-308.8-308.8-308.8-170.5 0-308.8 138.2-308.8 308.8 0 170.5 138.3 308.8 308.8 308.8v-88.3c-106.7 0-195.7-75.7-216.1-176.4h480.8c24.3 0 44.1-19.9 44.1-44.1zm-524.9-44.1c20.4-100.7 109.4-176.5 216.1-176.5 106.7 0 195.7 75.8 216.2 176.5H978.7zM1636 707.1c24.3 0 44.1 19.7 44.1 44.1 0 24.3-19.8 44.1-44.1 44.1-24.4 0-44.2-19.8-44.2-44.1 0-24.4 19.8-44.1 44.2-44.1z" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-7 col-shrink order-last-lg">
                        <div class="col-inner">
                            <ul class="nav-mini-right">
                                <? if (Yii::$app->user->isGuest) { ?>
                                    <li class="d-none d-sm-block">
                                        <a href="#loginFormTabInModal-register" data-toggle="modal" data-target="#loginFormTabInModal" data-backdrop="static" data-keyboard="false">
                                            <span class="icon-font"><i class="icon-user-follow"></i></span> Зарегистрироваться
                                        </a>
                                    </li>
                                    <li class="d-none d-sm-block">
                                        <a href="#loginFormTabInModal-login" data-toggle="modal" data-target="#loginFormTabInModal" data-backdrop="static" data-keyboard="false">
                                            <span class="icon-font"><i class="icon-login"></i></span> Войти
                                        </a>
                                    </li>
                                    <li class="d-block d-sm-none">
                                        <a href="#loginFormTabInModal-register" data-toggle="modal" data-target="#loginFormTabInModal" data-backdrop="static" data-keyboard="false">
                                            Войти / Зарегистрироваться
                                        </a>
                                    </li>
                                    <li>
                                        <button class="btn btn-toggle collapsed" data-toggle="collapse" data-target="#mobileMenu"></button>
                                    </li>
                                <? } else { ?>

                                <? } ?>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="col-12 col-auto">
                        
                        <div class="navbar-wrapper">
                            
                            <div class="navbar navbar-expand-lg">
                            
                                <div id="mobileMenu" class="collapse navbar-collapse"> 

                                    <nav class="main-nav-menu main-menu-nav navbar-arrow">
                                    
                                        <?= Menu::widget([
                                            'items' => [
                                                ['label' => 'Главная', 'url' => ['site/index']],
                                                ['label' => 'О компании', 'url' => ['pages/about']],
                                                ['label' => 'Услуги', 'url' => ['pages/services']],
                                                ['label' => 'Контакты', 'url' => ['pages/contacts']],
                                            ],
                                            'options' => [
                                                'class' => 'main-nav',
                                            ],
                                            'activeCssClass'=>'active',
                                        ]); ?>

                                        <!-- <ul class="main-nav">
                                            <li><a href="index.html">Home</a>
                                                <ul>
                                                    <li><a href="index.html">Home 01</a></li>
                                                    <li><a href="index-02.html">Home 02</a></li>
                                                    <li><a href="index-03.html">Home 03</a></li>
                                                    <li><a href="index-04.html">Home 04</a></li>
                                                    <li><a href="index-05.html">Home 05</a></li>
                                                    <li><a href="index-06.html">Home 06</a></li>
                                                    <li>
                                                        <a href="javascript:void(0)">Sub-menu</a>
                                                         <ul>
                                                            <li><a href="javascript:void(0)">Sub-menu 2</a></li>
                                                            <li><a href="javascript:void(0)">Sub-menu 2</a></li>
                                                            <li><a href="javascript:void(0)">Sub-menu 2</a></li>
                                                        </ul>
                                                    </li>
                                                </ul>
                                            </li>
                                            <li><a href="result-grid.html">Tour Package</a>
                                                <ul>
                                                    <li><a href="tour-result-list.html">Result - List</a></li>
                                                    <li><a href="tour-result-grid.html">Result - Grid</a></li>
                                                    <li><a href="tour-detail.html">Detail 01</a></li>
                                                    <li><a href="tour-detail-02.html">Detail 02</a></li>
                                                    <li><a href="tour-detail-03.html">Detail 03</a></li>
                                                    <li><a href="tour-detail-04.html">Detail 04</a></li>
                                                    <li><a href="tour-detail-05.html">Detail 05</a></li>
                                                    <li><a href="tour-detail-empty-booking.html">Detail - empty booking</a></li>
                                                    <li><a href="tour-payment.html">Payment</a></li>
                                                    <li><a href="tour-conformation.html">Conformation</a></li>
                                                    <li><a href="destinations-01.html">Destinations 01</a></li>
                                                    <li><a href="destinations-02.html">Destinations 02</a></li>
                                                </ul>
                                            </li>
                                            <li><a href="javascript:void(0)">Blog</a>
                                                <ul>
                                                    <li><a href="blog-01.html"> Blog - Grid Full 01</a></li>
                                                    <li><a href="blog-02.html"> Blog - Grid Full 02</a></li>
                                                    <li><a href="blog-03.html"> Blog - Long Full 01</a></li>
                                                    <li><a href="blog-04.html"> Blog - Long Full 02</a></li>
                                                    <li><a href="blog-05.html"> Blog - Grid Right Sidebar</a></li>
                                                    <li><a href="blog-06.html"> Blog - Long Right Sidebar</a></li>
                                                    <li><a href="blog-single-01.html">Blog Single - Full</a></li>
                                                    <li><a href="blog-single-02.html">Blog Single - Right Sidebar</a></li>
                                                </ul>
                                            </li>
                                            <li><a href="javascript:void(0)">Pages</a>
                                                <ul>
                                                    <li><a href="about-us.html">About Us</a></li>
                                                    <li><a href="service.html">Service</a></li>
                                                    <li><a href="service-single.html">Service Single</a></li>
                                                    <li><a href="faq.html">FAQ</a></li>
                                                    <li><a href="privacy-and-term.html">Privacy and Term</a></li>
                                                    <li><a href="error-404.html">Error 404</a></li>
                                                    <li><a href="dashboard.html">Dashboard</a></li>
                                                    <li>
                                                        <a href="javascript:void(0)">Shortcode</a>
                                                        <ul>
                                                            <li><a href="shortcode-typography.html">Typography</a></li>
                                                            <li><a href="shortcode-element.html">Element</a></li>
                                                            <li><a href="shortcode-layout-fullwidth.html">Layout Fullwidth</a></li>
                                                            <li><a href="shortcode-layout-left-sidebar.html">Layout Left Sidebar</a></li>
                                                            <li><a href="shortcode-layout-right-sidebar.html">Layout Right Sidebar</a></li>
                                                        </ul>
                                                    </li>
                                                </ul>
                                            </li>
                                            <li><a href="contact-us.html">Contact us</a></li>
                                        
                                        </ul> -->
                                    
                                    </nav><!--/.nav-collapse -->
                                
                                </div>
                                
                            </div>

                        </div>
                        
                    </div>
                    
                </div>
                
            </div>
            
        </div>

    </header>
    <!-- start Header -->

    <!-- start Main Wrapper -->
    <div class="main-wrapper scrollspy-container">
        <?= $content ?>
    </div>
    <!-- end Main Wrapper -->


    <!-- start Footer Wrapper -->
    <footer class="footer-wrapper light scrollspy-footer">
		
        <div class="footer-top">
            
            <div class="container">
            
                <div class="row shrink-auto-md align-items-lg-center gap-10">

                    <div class="col-12 col-shrink order-last-md">
                    
                        <!-- <div class="col-inner">
                        
                            <div class="footer-dropdowns">
                        
                                <div class="row shrink-auto gap-30 align-items-center">
                    
                                    <div class="col-auto">
                                    
                                        <div class="col-inner">
                                            
                                            <div class="dropdown dropdown-smooth-01 dropdown-language">
                                                <a href="#" class="btn btn-text-inherit btn-interactive" id="dropdownLangauge" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <span class="image"><img src="font-icons/flaticon-flags-4/png/260-united-kingdom.png" alt="image" /></span> English
                                                </a>
                                                <div class="dropdown-menu" aria-labelledby="dropdownLangauge">
                                                    <div class="dropdown-menu-inner">
                                                        <a class="dropdown-item" href="#"><span class="image"><img src="font-icons/flaticon-flags-4/png/260-united-kingdom.png" alt="image" /></span>English</a>
                                                        <a class="dropdown-item" href="#"><span class="image"><img src="font-icons/flaticon-flags-4/png/013-italy.png" alt="image" /></span>Italiano</a>
                                                        <a class="dropdown-item" href="#"><span class="image"><img src="font-icons/flaticon-flags-4/png/063-japan.png" alt="image" /></span>日本語</a>
                                                        <a class="dropdown-item" href="#"><span class="image"><img src="font-icons/flaticon-flags-4/png/162-germany.png" alt="image" /></span>Deutsch</a>
                                                        <a class="dropdown-item" href="#"><span class="image"><img src="font-icons/flaticon-flags-4/png/218-turkey.png" alt="image" /></span>Türkçe</a>
                                                        <a class="dropdown-item" href="#"><span class="image"><img src="font-icons/flaticon-flags-4/png/238-thailand.png" alt="image" /></span>ภาษาไทย</a>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                        </div>
                                        
                                    </div>
                                    
                                    <div class="col-shrink">
                                    
                                        <div class="col-inner">
                                        
                                            <div class="dropdown dropdown-smooth-01 dropdown-currency">
                                                <a href="#" class="btn btn-text-inherit btn-interactive" id="dropdownCurrency" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <span class="icon-font"><i class="oi oi-dollar text-primary mr-5"></i></span> US dollar
                                                </a>
                                                <div class="dropdown-menu" aria-labelledby="dropdownCurrency">
                                                    <div class="dropdown-menu-inner">
                                                        <a class="dropdown-item" href="#"><span class="icon-font"><i class="oi oi-dollar text-primary mr-10"></i></span>US Dollar</a>
                                                        <a class="dropdown-item" href="#"><span class="icon-font"><i class="oi oi-british-pound text-primary mr-10"></i></span>UK Pound</a>
                                                        <a class="dropdown-item" href="#"><span class="icon-font"><i class="oi oi-euro text-primary mr-10"></i></span>EU Euro</a>
                                                        <a class="dropdown-item" href="#"><span class="icon-font"><i class="oi oi-yen text-primary mr-10"></i></span>JP Yen</a>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                        </div>
                                        
                                    </div>
                                    
                                </div>
                            
                            </div>

                        </div> -->
                        
                    </div>
                    
                    <div class="col-12 col-auto">
                    
                        <div class="col-inner">
                            <ul class="footer-contact-list">
                                <li>
                                    <a href="tel:<?=$setting[8]->value?>"><span class="icon-font text-primary inline-block-middle mr-5 font16"><i class="fa fa-phone"></i></span> <span class="font700 text-black"><?=$setting[8]->value?></span> <!--<span class="text-muted">Mon-Fri | 8.30am-6:30pm</span>--></a>
                                </li>
                                <li>
                                    <a href="mailto:support@ei.ru"><span class="icon-font text-primary inline-block-middle mr-5 font16"><i class="fa fa-envelope"></i></span> <span class="font700 text-black">support@ei.ru</span></a>
                                </li>
                            </ul>
                        </div>
                        
                    </div>
                    
                </div>
                
                <hr class="opacity-7" />
                
            </div>

        </div>
        
        <div class="main-footer">
        
            <div class="container">
            
                <div class="row gap-50">
                
                    <div class="col-12 col-lg-5">
                    
                        <div class="footer-logo">
                            <svg xmlns="http://www.w3.org/2000/svg" class="logo logo-footer" width="168" height="150" viewBox="0 0 1680 1500"
                                shape-rendering="geometricPrecision">
                                <path class="logo-icon"
                                d="M753.7 88.8c350.6 0 637.5 272.7 660.2 617.6h-132.7c-22.4-271.7-250-485.3-527.5-485.3-292.4 0-529.4 237-529.4 529.4 0 291 234.8 527.1 525.3 529.3 1.3.1 2.7.2 4.1.2 24.4 0 44.1-19.8 44.1-44.2 0-24.3-19.7-44.1-44.1-44.1v-.1c-243.6 0-441.1-197.5-441.1-441.1 0-243.7 197.5-441.2 441.1-441.2 228.8 0 416.8 174.2 438.9 397.1h-133.2C1038 556.8 909.3 441.7 753.7 441.7c-170.5 0-308.8 138.2-308.8 308.8 0 169.2 136.1 306.6 304.8 308.7 2.6.2 5.1.2 7.6 0 14.3-.1 28.5-1.3 42.6-3.4 23.4-1.1 42-20.4 42-44.1 0-24.3-19.7-44.1-44.1-44.1-1.9 0-3.9.1-5.8.4-12.6 2.2-25.5 3.2-38.3 3.2-121.8 0-220.6-98.8-220.6-220.6S631.9 530 753.7 530c106.7 0 195.7 75.8 216.1 176.4H753.7c-24.3 0-44.1 19.9-44.1 44.1 0 24.3 19.8 44.2 44.1 44.2h705.8c24.3 0 44.1-19.9 44.1-44.2C1503.6 336.3 1167.9.6 753.7.6S3.8 336.3 3.8 750.5c0 414.1 335.7 749.9 749.9 749.9 30.2 0 60.4-1.8 90.4-5.5 23.3-1.1 41.9-20.4 41.9-44 0-24.4-19.7-44.1-44.1-44.1-2.6 0-5.1.2-7.5.6-26.8 3.3-53.7 4.8-80.7 4.8-365.4 0-661.7-296.3-661.7-661.7C92 385 388.3 88.8 753.7 88.8z" />
                                <path class="logo-icon"
                                d="M753.7 88.8c350.6 0 637.5 272.7 660.2 617.6h-132.7c-22.4-271.7-250-485.3-527.5-485.3-292.4 0-529.4 237-529.4 529.4 0 291 234.8 527.1 525.3 529.3 1.3.1 2.7.2 4.1.2 24.4 0 44.1-19.8 44.1-44.2 0-24.3-19.7-44.1-44.1-44.1v-.1c-243.6 0-441.1-197.5-441.1-441.1 0-243.7 197.5-441.2 441.1-441.2 228.8 0 416.8 174.2 438.9 397.1h-133.2C1038 556.8 909.3 441.7 753.7 441.7c-170.5 0-308.8 138.2-308.8 308.8 0 169.2 136.1 306.6 304.8 308.7 2.6.2 5.1.2 7.6 0 14.3-.1 28.5-1.3 42.6-3.4 23.4-1.1 42-20.4 42-44.1 0-24.3-19.7-44.1-44.1-44.1-1.9 0-3.9.1-5.8.4-12.6 2.2-25.5 3.2-38.3 3.2-121.8 0-220.6-98.8-220.6-220.6S631.9 530 753.7 530c106.7 0 195.7 75.8 216.1 176.4H753.7c-24.3 0-44.1 19.9-44.1 44.1 0 24.3 19.8 44.2 44.1 44.2h705.8c24.3 0 44.1-19.9 44.1-44.2C1503.6 336.3 1167.9.6 753.7.6S3.8 336.3 3.8 750.5c0 414.1 335.7 749.9 749.9 749.9 30.2 0 60.4-1.8 90.4-5.5 23.3-1.1 41.9-20.4 41.9-44 0-24.4-19.7-44.1-44.1-44.1-2.6 0-5.1.2-7.5.6-26.8 3.3-53.7 4.8-80.7 4.8-365.4 0-661.7-296.3-661.7-661.7C92 385 388.3 88.8 753.7 88.8z" />
                                <path class="logo-acronym"
                                d="M1636 883c24.2 0 44.1 19.8 44.1 44.1v529.3c0 24.3-19.9 44.1-44.1 44.1-24.3 0-44.2-19.8-44.2-44.1V927.1c0-24.3 19.9-44.1 44.2-44.1zm-215.5 542c10.3-8 17-20.6 17-34.7 0-24.4-19.8-44.2-44.2-44.2-11.4 0-21.9 4.4-29.8 11.6l-.1-.1c-49 35.6-108 54.7-168.6 54.7v88.3c81.1 0 160-26.3 224.9-75l.8-.6zm83.1-233.2c0-170.6-138.2-308.8-308.8-308.8-170.5 0-308.8 138.2-308.8 308.8 0 170.5 138.3 308.8 308.8 308.8v-88.3c-106.7 0-195.7-75.7-216.1-176.4h480.8c24.3 0 44.1-19.9 44.1-44.1zm-524.9-44.1c20.4-100.7 109.4-176.5 216.1-176.5 106.7 0 195.7 75.8 216.2 176.5H978.7zM1636 707.1c24.3 0 44.1 19.7 44.1 44.1 0 24.3-19.8 44.1-44.1 44.1-24.4 0-44.2-19.8-44.2-44.1 0-24.4 19.8-44.1 44.2-44.1z" />
                                <path class="logo-acronym"
                                d="M1636 883c24.2 0 44.1 19.8 44.1 44.1v529.3c0 24.3-19.9 44.1-44.1 44.1-24.3 0-44.2-19.8-44.2-44.1V927.1c0-24.3 19.9-44.1 44.2-44.1zm-215.5 542c10.3-8 17-20.6 17-34.7 0-24.4-19.8-44.2-44.2-44.2-11.4 0-21.9 4.4-29.8 11.6l-.1-.1c-49 35.6-108 54.7-168.6 54.7v88.3c81.1 0 160-26.3 224.9-75l.8-.6zm83.1-233.2c0-170.6-138.2-308.8-308.8-308.8-170.5 0-308.8 138.2-308.8 308.8 0 170.5 138.3 308.8 308.8 308.8v-88.3c-106.7 0-195.7-75.7-216.1-176.4h480.8c24.3 0 44.1-19.9 44.1-44.1zm-524.9-44.1c20.4-100.7 109.4-176.5 216.1-176.5 106.7 0 195.7 75.8 216.2 176.5H978.7zM1636 707.1c24.3 0 44.1 19.7 44.1 44.1 0 24.3-19.8 44.1-44.1 44.1-24.4 0-44.2-19.8-44.2-44.1 0-24.4 19.8-44.1 44.2-44.1z" />
                            </svg>
                        </div>
                        
                        <p class="mt-20">Цель нашего проекта - помогать людям находить самое разнообразное имущество по самым низким ценам. Наш проект собирает, обрабатывает, дополняет, анализирует информацию со всех торговых площадок и аукционов России.</p>
                        
                        <a href="<?=Url::to(['pages/about'])?>" class="text-capitalize font14 h6 line-1 mb-0 font500 mt-30">Узнать больше <i class="elegent-icon-arrow_right font18 inline-block-middle"></i></a>
                        
                    </div>
                    
                    <div class="col-12 col-lg-7">
                    
                        <div class="col-inner">
                        
                            <div class="row shrink-auto-sm gap-30">
                    
                                <div class="col-6 col-shrink">
                                    
                                    <div class="col-inner">
                                        <h5 class="footer-title">About company</h5>
                                        <ul class="footer-menu-list set-width">
                                            <li><a href="#">Who we are</a></li>
                                            <li><a href="#">Careers</a></li>
                                            <li><a href="#">Company history</a></li>
                                            <li><a href="#">Legal</a></li>
                                            <li><a href="#">Partners</a></li>
                                            <li><a href="#">Privacy notice</a></li>
                                        </ul>
                                    </div>
                                    
                                </div>
                                
                                <div class="col-6 col-shrink">
                                    
                                    <div class="col-inner">
                                        <h5 class="footer-title">Customer Service</h5>
                                        <ul class="footer-menu-list set-width">
                                            <li><a href="#">Payment</a></li>
                                            <li><a href="#">Feedback</a></li>
                                            <li><a href="#">Contact us</a></li>
                                            <li><a href="#">Our Service</a></li>
                                            <li><a href="#">FAQ</a></li>
                                            <li><a href="#">Site map</a></li>
                                        </ul>
                                    </div>
                                    
                                </div>
                                
                                <div class="col-12 col-auto">
                                    
                                    <div class="col-inner">
                                        <h5 class="footer-title">Newsletter &amp; Social</h5>
                                        <p class="font12">Savings her pleased are several started females met. Short her not among being any.</p>
                                        <form class="footer-newsletter mt-20">
                                            <div class="input-group">
                                                <input type="email" class="form-control" placeholder="Email address">
                                                <div class="input-group-append">
                                                    <button class="btn btn-primary" type="button"><i class="far fa-envelope"></i></button>
                                                </div>
                                            </div>
                                        </form>
                                        <div class="footer-socials mt-20">
                                            <a href="#"><i class="fab fa-facebook-square"></i></a>
                                            <a href="#"><i class="fab fa-twitter-square"></i></a>
                                            <a href="#"><i class="fab fa-google-plus-square"></i></a>
                                            <a href="#"><i class="fab fa-pinterest-square"></i></a>
                                            <a href="#"><i class="fab fa-flickr"></i></a>
                                        </div>
                                    </div>
                                    
                                </div>
                                
                            </div>
                        
                        </div>
                    
                    </div>
                
                </div>
                
            </div>
        
        </div>
        
        <div class="bottom-footer">
                 
            <div class="container">
            
                <div class="row shrink-auto-md gap-10 gap-40-lg">
                
                    <div class="col-auto">
                        <div class="col-inner">
                            <ul class="footer-menu-list-02">
                                <li><a href="#">Cookies</a></li>
                                <li><a href="#">Policies</a></li>
                                <li><a href="#">Terms</a></li>
                                <li><a href="#">Blogs</a></li>
                            </ul>
                        </div>
                    </div>
                
                    <div class="col-shrink">
                        <div class="col-inner">
                            <p class="footer-copy-right">© 2019 "ei.ru" Обращаем ваше внимание на то, что данный Интернет-сайт носит исключительно информационный характер и ни при каких условиях не является публичной офертой, определяемой положениями Статьи 437 Гражданского кодекса Российской Федерации.</p>
                        </div>
                    </div>
                    
                </div>
            
            </div>

        </div>
        
    </footer>
    <!-- start Footer Wrapper -->
    
    
    
</div>
<!-- end Body Inner -->



<!-- start Login modal -->
<div class="modal fade modal-with-tabs form-login-modal" id="loginFormTabInModal" aria-labelledby="modalWIthTabsLabel" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content shadow-lg">
        
            <nav class="d-none">
                <ul class="nav external-link-navs clearfix">
                    <li><a class="active" data-toggle="tab" href="#loginFormTabInModal-login">Sign-in</a></li>
                    <li><a data-toggle="tab" href="#loginFormTabInModal-register">Register </a></li>
                    <li><a data-toggle="tab" href="#loginFormTabInModal-forgot-pass">Forgot Password </a></li>
                </ul>
            </nav>
            
            <div class="tab-content">

                <div role="tabpanel" class="tab-pane active" id="loginFormTabInModal-login">
                
                    <div class="form-login">

                        <div class="form-header">
                            <h4>Добро пожаловать на Ei.ru</h4>
                            <p>Авторизуйтесь на сайте, что бы дальше пользоваться им.</p>
                        </div>
                        
                        <div class="form-body">
                        <!-- ['site/login'] -->
                            <?= Html::beginForm('', 'POST', ['class' => 'form-ajax-js']); ?>

                                <div class="d-flex flex-column flex-lg-row align-items-stretch">
                                    <div class="flex-md-grow-1 bg-primary-light">
                                        <div class="form-inner">

                                            <div class="form-group">
                                                <label>E-mail адрес</label>
                                                <?=Html::input('text', 'username', '', ['autofocus' => true, 'class' => 'form-control'])?>
                                            </div>
                                            <div class="form-group">
                                                <label>Пароль</label>
                                                <?=Html::input('password', 'password', '', ['class' => 'form-control'])?>
                                            </div>
                                            

                                            <div class="d-flex flex-column flex-md-row mt-25">
                                                <div class="flex-shrink-0">
                                                    <?=Html::submitButton('Войти', ['class' => 'btn btn-primary btn-wide'])?>
                                                </div>
                                                <div class="ml-0 ml-md-15 mt-15 mt-md-0">
                                                    <div class="custom-control custom-checkbox">
                                                        <?=Html::input('checkbox', 'rememberMe', '', ['class' => 'custom-control-input', 'id' => 'loginFormTabInModal-rememberMe', 'autofocus' => true])?>
                                                        <label class="custom-control-label" for="loginFormTabInModal-rememberMe">Запомнить меня</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <a href="#loginFormTabInModal-forgot-pass" class="tab-external-link block mt-25 font600">Забыл пароль?</a>

                                        </div>
                                    </div>
                                </div>

                            <?= Html::endForm(); ?>

                            <?php
                                $js = <<<JS
                                    $('form').on('beforeSubmit', function(){
                                        alert('Работает!');
                                    return false;
                                });
JS;

                                $this->registerJs($js);
                            ?>

                            <!-- <form method="post" action="#">
                            
                                <div class="d-flex flex-column flex-lg-row align-items-stretch">
                                
                                    <div class="flex-md-grow-1 bg-primary-light">

                                        <div class="form-inner">
                                            <div class="form-group">
                                                <label>Email adress/username</label>
                                                <input type="text" class="form-control" />
                                            </div>
                                            <div class="form-group">
                                                <label>Password</label>
                                                <input type="password" class="form-control" />
                                            </div>
                                            <div class="d-flex flex-column flex-md-row mt-25">
                                                <div class="flex-shrink-0">
                                                    <a href="#" class="btn btn-primary btn-wide">Sign-in</a>
                                                </div>
                                                <div class="ml-0 ml-md-15 mt-15 mt-md-0">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input" id="loginFormTabInModal-rememberMe">
                                                        <label class="custom-control-label" for="loginFormTabInModal-rememberMe">Remember me</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <a href="#loginFormTabInModal-forgot-pass" class="tab-external-link block mt-25 font600">Forgot password?</a>
                                        </div>

                                    </div>
                                    
                                    <div class="form-login-socials">
                                        <div class="login-socials-inner">
                                            <h5 class="mb-20">Or sign-up with your socials</h5>
                                            <button class="btn btn-login-with btn-facebook btn-block"><i class="fab fa-facebook"></i> facebook</button>
                                            <button class="btn btn-login-with btn-google btn-block"><i class="fab fa-google"></i> google</button>
                                            <button class="btn btn-login-with btn-twitter btn-block"><i class="fab fa-twitter"></i> google</button>
                                        </div>
                                    </div>
                                
                                </div>
                        
                            </form> -->
                            
                        </div>
                        
                        <div class="form-footer">
                            <p>Not a member yet? <a href="#loginFormTabInModal-register" class="tab-external-link font600">Sign up</a> for free</p>
                        </div>
                        
                    </div>
                
                </div>
                
                <div role="tabpanel" class="tab-pane fade in" id="loginFormTabInModal-register">
                
                    <div class="form-login">

                        <div class="form-header">
                            <h4>Join SiteName for Free</h4>
                            <p>Access thousands of online classes in design, business, and more!</p>
                        </div>
                        
                        <div class="form-body">
                        
                            <form method="post" action="#">
                            
                                <div class="d-flex flex-column flex-lg-row align-items-stretch">
                                
                                    <div class="flex-grow-1 bg-primary-light">

                                        <div class="form-inner">
                                            <div class="form-group">
                                                <label>Full name</label>
                                                <input type="text" class="form-control" />
                                            </div>
                                            <div class="form-group">
                                                <label>Email adress</label>
                                                <input type="text" class="form-control" />
                                            </div>
                                            <div class="row cols-2 gap-10">
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label>Password</label>
                                                        <input type="password" class="form-control" />
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="form-group">
                                                        <label>Confirm password</label>
                                                        <input type="password" class="form-control" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    
                                    <div class="form-login-socials">
                                        <div class="login-socials-inner">
                                            <h5 class="mb-20">Or sign-in with your socials</h5>
                                            <button class="btn btn-login-with btn-facebook btn-block"><i class="fab fa-facebook"></i> facebook</button>
                                            <button class="btn btn-login-with btn-google btn-block"><i class="fab fa-google"></i> google</button>
                                            <button class="btn btn-login-with btn-twitter btn-block"><i class="fab fa-twitter"></i> google</button>
                                        </div>
                                    </div>
                                
                                </div>
                            
                                <div class="d-flex flex-column flex-md-row mt-30 mt-lg-10">
                                    <div class="flex-shrink-0">
                                        <a href="#" class="btn btn-primary btn-wide mt-5">Sign-up</a>
                                    </div>
                                    <div class="pt-1 ml-0 ml-md-15 mt-15 mt-md-0">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="loginFormTabInModal-acceptTerm">
                                            <label class="custom-control-label line-145" for="loginFormTabInModal-acceptTerm">By clicking this, you are agree to to our <a href="#">terms of use</a> and <a href="#">privacy policy</a> including the use of cookies</label>
                                        </div>
                                    </div>
                                </div>
                            
                            </form>
                            
                        </div>
                        
                        <div class="form-footer">
                            <p>Already a member? <a href="#loginFormTabInModal-login" class="tab-external-link font600">Sign in</a></p>
                        </div>
                        
                    </div>
                    
                </div>
                
                <div role="tabpanel" class="tab-pane fade in" id="loginFormTabInModal-forgot-pass">
                    
                    <div class="form-login">
                    
                        <div class="form-header">
                            <h4>Lost your password?</h4>
                            <p>Please provide your detail.</p>
                        </div>
                        
                        <div class="form-body">
                            <form method="post" action="#">
                                <p class="line-145">We'll send password reset instructions to the email address associated with your account.</p>
                                <div class="row">
                                    <div class="col-12 col-md-10 col-lg-8">
                                        <div class="form-group">
                                            <input type="password" class="form-control" placeholder="password" />
                                        </div>
                                    </div>
                                </div>
                                <button class="btn btn-primary mt-5">Retreive password</button>
                            </form>
                        </div>
                        
                        <div class="form-footer">
                            <p>Back to <a href="#loginFormTabInModal-login" class="tab-external-link font600">Sign in</a> or <a href="#loginFormTabInModal-register" class="tab-external-link font600">Sign up</a></p>
                        </div>
                        
                    </div>
                    
                </div>
                
            </div>
            
            <div class="text-center pb-20">
                <button type="button" class="close" data-dismiss="modal" aria-labelledby="Close">
                    <span aria-hidden="true"><i class="far fa-times-circle"></i></span>
                </button>
            </div>
        </div>
    </div>
</div>
<!-- end Login modal -->



<!-- start Back To Top -->
<a id="back-to-top" href="#" class="back-to-top" role="button" title="Click to return to top" data-toggle="tooltip" data-placement="left"><i class="elegent-icon-arrow_carrot-up"></i></a>
<!-- end Back To Top -->



<!-- 
<div class="wrap">
    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
    </div>
</div> -->

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
