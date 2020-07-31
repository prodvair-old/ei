<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;
use yii\widgets\Menu;

use common\models\Query\Settings;
use common\models\Query\LotsCategory;
use common\models\db\Owner;
use common\models\db\Category;
// use frontend\modules\models\Category;

use frontend\components\LoginWidget;
use frontend\components\SignupWidget;
use frontend\components\ResetPasswordWidget;


$setting = Settings::find()->orderBy('id ASC')->all();
$lotsCategory = Category::find()->where(['depth' => 1])->orderBy('id ASC')->all();
$owners = Owner::find()->orderBy('id ASC')->all();

$bankruptLotsCategoryMenu = $arrestLotsCategoryMenu = null;

foreach ($owners as $owner) {
  $ownersMenu[] = ['label' => $owner->organization->title, 'url' => ['/'. $owner->slug]];
}

foreach ($lotsCategory as $value) {
    $bankruptLotsCategoryMenu[] = ['label' => $value->name, 'url' => ['/bankrupt/' . $value->slug]];
    $arrestLotsCategoryMenu[] = ['label' => $value->name, 'url' => ['/arrest/' . $value->slug]];
    $municipaltLotsCategoryMenu[] = ['label' => $value->name, 'url' => ['/municipal/' . $value->slug]];
}

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
  <meta charset="<?= Yii::$app->charset ?>">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- <base href="http://c844edeee179.ngrok.io"> -->
  <base href="<?=Yii::$app->request->hostInfo?>">
  <?php $this->registerCsrfMetaTags() ?>

  <!-- Title Of Site -->
  <title><?= Html::encode($this->title) ?></title>

  <meta name="description"
    content="<?= isset(Yii::$app->params['description']) ? Yii::$app->params['description'] : '' ?>" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">


  <!-- Fav and Touch Icons -->
  <link rel="apple-touch-icon" sizes="180x180" href="<?= Url::to('/img/favicon/logo_ei(green).png', true)?>">
  <link rel="icon" type="image/png" sizes="32x32" href="">
  <link rel="icon" type="image/png" sizes="16x16" href="<?= Url::to('/img/favicon/favicon2.ico', true)?>">
  <link rel="manifest" href="<?= Url::to('/img/favicon/site.webmanifest', true)?>">
  <link rel="mask-icon" href="<?= Url::to('/img/favicon/safari-pinned-tab.svg', true)?>" color="#5bbad5">
  <meta name="msapplication-TileColor" content="#ffffff">
  <meta name="theme-color" content="#ffffff">

  <!-- Global site tag (gtag.js) - Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=UA-141584755-1"></script>
  <script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
      dataLayer.push(arguments);
    }
    gtag('js', new Date());
    gtag('config', 'UA-141584755-1');
  </script>

  <!-- Yandex.Metrika counter -->
  <script type="text/javascript">
    (function (m, e, t, r, i, k, a) {
      m[i] = m[i] || function () {
        (m[i].a = m[i].a || []).push(arguments)
      };
      m[i].l = 1 * new Date();
      k = e.createElement(t), a = e.getElementsByTagName(t)[0], k.async = 1, k.src = r, a.parentNode.insertBefore(k,
        a)
    })
    (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

    ym(53770261, "init", {
      clickmap: true,
      trackLinks: true,
      accurateTrackBounce: true,
      webvisor: true
    });
  </script>
  <noscript>
    <div><img src="https://mc.yandex.ru/watch/53770261" style="position:absolute; left:-9999px;" alt="" /></div>
  </noscript>
  <!-- /Yandex.Metrika counter -->

  <?php $this->head() ?>

  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>

<body class="with-waypoint-">
  <?php $this->beginBody() ?>

  <!-- start Body Inner -->
  <div class="body-inner">

    <!-- start Header -->
    <header id="header-waypoint-" class="header-main header-mobile-menu with-absolute-navbar">

      <div class="header-outer clearfix">

        <div class="header-inner">

          <div class="row shrink-auto-lg gap-0 align-items-center">

            <div class="col-5 col-shrink">
              <div class="col-inner">
                <div class="main-logo">
                  <a href="/" class="main-logo__link">
                    <img src="<?= Url::base() ?>/img/logo-ei(green).svg" alt="" class="logo">
                    <!-- <svg xmlns="http://www.w3.org/2000/svg" class="logo" width="168" height="150" viewBox="0 0 1680 1500" shape-rendering="geometricPrecision">
                      <path class="logo-icon" d="M753.7 88.8c350.6 0 637.5 272.7 660.2 617.6h-132.7c-22.4-271.7-250-485.3-527.5-485.3-292.4 0-529.4 237-529.4 529.4 0 291 234.8 527.1 525.3 529.3 1.3.1 2.7.2 4.1.2 24.4 0 44.1-19.8 44.1-44.2 0-24.3-19.7-44.1-44.1-44.1v-.1c-243.6 0-441.1-197.5-441.1-441.1 0-243.7 197.5-441.2 441.1-441.2 228.8 0 416.8 174.2 438.9 397.1h-133.2C1038 556.8 909.3 441.7 753.7 441.7c-170.5 0-308.8 138.2-308.8 308.8 0 169.2 136.1 306.6 304.8 308.7 2.6.2 5.1.2 7.6 0 14.3-.1 28.5-1.3 42.6-3.4 23.4-1.1 42-20.4 42-44.1 0-24.3-19.7-44.1-44.1-44.1-1.9 0-3.9.1-5.8.4-12.6 2.2-25.5 3.2-38.3 3.2-121.8 0-220.6-98.8-220.6-220.6S631.9 530 753.7 530c106.7 0 195.7 75.8 216.1 176.4H753.7c-24.3 0-44.1 19.9-44.1 44.1 0 24.3 19.8 44.2 44.1 44.2h705.8c24.3 0 44.1-19.9 44.1-44.2C1503.6 336.3 1167.9.6 753.7.6S3.8 336.3 3.8 750.5c0 414.1 335.7 749.9 749.9 749.9 30.2 0 60.4-1.8 90.4-5.5 23.3-1.1 41.9-20.4 41.9-44 0-24.4-19.7-44.1-44.1-44.1-2.6 0-5.1.2-7.5.6-26.8 3.3-53.7 4.8-80.7 4.8-365.4 0-661.7-296.3-661.7-661.7C92 385 388.3 88.8 753.7 88.8z" />
                      <path class="logo-icon" d="M753.7 88.8c350.6 0 637.5 272.7 660.2 617.6h-132.7c-22.4-271.7-250-485.3-527.5-485.3-292.4 0-529.4 237-529.4 529.4 0 291 234.8 527.1 525.3 529.3 1.3.1 2.7.2 4.1.2 24.4 0 44.1-19.8 44.1-44.2 0-24.3-19.7-44.1-44.1-44.1v-.1c-243.6 0-441.1-197.5-441.1-441.1 0-243.7 197.5-441.2 441.1-441.2 228.8 0 416.8 174.2 438.9 397.1h-133.2C1038 556.8 909.3 441.7 753.7 441.7c-170.5 0-308.8 138.2-308.8 308.8 0 169.2 136.1 306.6 304.8 308.7 2.6.2 5.1.2 7.6 0 14.3-.1 28.5-1.3 42.6-3.4 23.4-1.1 42-20.4 42-44.1 0-24.3-19.7-44.1-44.1-44.1-1.9 0-3.9.1-5.8.4-12.6 2.2-25.5 3.2-38.3 3.2-121.8 0-220.6-98.8-220.6-220.6S631.9 530 753.7 530c106.7 0 195.7 75.8 216.1 176.4H753.7c-24.3 0-44.1 19.9-44.1 44.1 0 24.3 19.8 44.2 44.1 44.2h705.8c24.3 0 44.1-19.9 44.1-44.2C1503.6 336.3 1167.9.6 753.7.6S3.8 336.3 3.8 750.5c0 414.1 335.7 749.9 749.9 749.9 30.2 0 60.4-1.8 90.4-5.5 23.3-1.1 41.9-20.4 41.9-44 0-24.4-19.7-44.1-44.1-44.1-2.6 0-5.1.2-7.5.6-26.8 3.3-53.7 4.8-80.7 4.8-365.4 0-661.7-296.3-661.7-661.7C92 385 388.3 88.8 753.7 88.8z" />
                      <path class="logo-acronym" d="M1636 883c24.2 0 44.1 19.8 44.1 44.1v529.3c0 24.3-19.9 44.1-44.1 44.1-24.3 0-44.2-19.8-44.2-44.1V927.1c0-24.3 19.9-44.1 44.2-44.1zm-215.5 542c10.3-8 17-20.6 17-34.7 0-24.4-19.8-44.2-44.2-44.2-11.4 0-21.9 4.4-29.8 11.6l-.1-.1c-49 35.6-108 54.7-168.6 54.7v88.3c81.1 0 160-26.3 224.9-75l.8-.6zm83.1-233.2c0-170.6-138.2-308.8-308.8-308.8-170.5 0-308.8 138.2-308.8 308.8 0 170.5 138.3 308.8 308.8 308.8v-88.3c-106.7 0-195.7-75.7-216.1-176.4h480.8c24.3 0 44.1-19.9 44.1-44.1zm-524.9-44.1c20.4-100.7 109.4-176.5 216.1-176.5 106.7 0 195.7 75.8 216.2 176.5H978.7zM1636 707.1c24.3 0 44.1 19.7 44.1 44.1 0 24.3-19.8 44.1-44.1 44.1-24.4 0-44.2-19.8-44.2-44.1 0-24.4 19.8-44.1 44.2-44.1z" />
                      <path class="logo-acronym" d="M1636 883c24.2 0 44.1 19.8 44.1 44.1v529.3c0 24.3-19.9 44.1-44.1 44.1-24.3 0-44.2-19.8-44.2-44.1V927.1c0-24.3 19.9-44.1 44.2-44.1zm-215.5 542c10.3-8 17-20.6 17-34.7 0-24.4-19.8-44.2-44.2-44.2-11.4 0-21.9 4.4-29.8 11.6l-.1-.1c-49 35.6-108 54.7-168.6 54.7v88.3c81.1 0 160-26.3 224.9-75l.8-.6zm83.1-233.2c0-170.6-138.2-308.8-308.8-308.8-170.5 0-308.8 138.2-308.8 308.8 0 170.5 138.3 308.8 308.8 308.8v-88.3c-106.7 0-195.7-75.7-216.1-176.4h480.8c24.3 0 44.1-19.9 44.1-44.1zm-524.9-44.1c20.4-100.7 109.4-176.5 216.1-176.5 106.7 0 195.7 75.8 216.2 176.5H978.7zM1636 707.1c24.3 0 44.1 19.7 44.1 44.1 0 24.3-19.8 44.1-44.1 44.1-24.4 0-44.2-19.8-44.2-44.1 0-24.4 19.8-44.1 44.2-44.1z" />
                    </svg> -->
                    <span class="d-none d-sm-inline text-logo">Единый <br> информатор</span>
                  </a>
                </div>
              </div>
            </div>

            <div class="col-7 col-shrink order-last-lg">
              <div class="col-inner">
                <ul class="nav-mini-right vertival-center">
                  <?php if (Yii::$app->user->isGuest) { ?>
                  <li class="d-none d-sm-block">
                    <a href="#loginFormTabInModal-register" data-toggle="modal" data-target="#loginFormTabInModal"
                      data-backdrop="static" data-keyboard="false" class="borr-5 main-page__link">
                      <span class="icon-font"><i class="icon-user-follow"></i></span> Зарегистрироваться
                    </a>
                  </li>
                  <li class="d-none d-sm-block">
                    <a href="#loginFormTabInModal-login" data-toggle="modal" data-target="#loginFormTabInModal"
                      data-backdrop="static" data-keyboard="false">
                      <span class="icon-font"><i class="icon-login"></i></span> Войти
                    </a>
                  </li>
                  <li class="d-block d-sm-none">
                    <a href="#loginFormTabInModal-login" data-toggle="modal" data-target="#loginFormTabInModal"
                      data-backdrop="static" data-keyboard="false" style="white-space: pre;">
                      <span class="icon-font"><i class="icon-user-follow"></i></span> Войти
                    </a>
                  </li>
                  <?php } else { ?>
                  <li class="d-block d-sm-block">
                    <a href="<?= Url::to(['/profile/favorite']) ?>" class="vertival-center">
                      <div class="image header-avatar">
                        <?= Yii::$app->user->identity->avatar ?>
                      </div>
                      <div class="ml-15">
                        <?= Yii::$app->user->isGuest ? '' : Yii::$app->user->identity->getFullName() ?>
                        <?php if (!Yii::$app->user->isGuest): ?>
                        <p class="head-email lowercase">
                          <?= Yii::$app->user->identity->email ?>
                        </p>
                        <?php endif; ?>
                      </div>
                    </a>
                  </li>
                  <!-- <li>
                      <a href="<?= Url::to(['site/logout']) ?>">
                        <span class="icon-font ml-15"><i class="icon-logout"></i></span>
                      </a>
                    </!-->
                  <?php } ?>
                  <li>
                    <button class="btn btn-toggle collapsed" data-toggle="collapse" data-target="#mobileMenu"></button>
                  </li>
                </ul>
              </div>
            </div>

            <div class="col-12 col-auto ml-auto">

              <div class="navbar-wrapper">

                <div class="navbar navbar-expand-lg">

                  <div id="mobileMenu" class="collapse navbar-collapse">

                    <nav class="main-nav-menu main-menu-nav navbar-arrow">

                      <?= Menu::widget([
                        'items' => [
                          ['label' => 'Торги', 'url' => ['/bankrupt'], 'items' => [
                            ['label' => 'Банкротное имущество', 'url' => ['/bankrupt'], 'items' => $bankruptLotsCategoryMenu],
                            ['label' => 'Арестованное имущество', 'url' => ['/arrest'], 'items' => $arrestLotsCategoryMenu],
                            ['label' => 'Имущество организаций', 'url' => ['/zalog'], 'items' => $ownersMenu],
                            ['label' => 'Муниципальное имущество', 'url' => ['/municipal'], 'items' => $municipaltLotsCategoryMenu],
                          ]],
                          ['label' => 'О компании', 'url' => ['pages/about'], 'items' => [
                            ['label' => 'О нас', 'url' => ['pages/about']],
                            ['label' => 'Контакты', 'url' => ['pages/contact']],
                          ]],
                          ['label' => 'Реестры', 'url' => ['/lot/arbitr/index'], 'items' => [
                            ['label' => 'Арбитражные управляющие', 'url' => ['/lot/arbitr/index']],
                            ['label' => 'Должники', 'url' => ['/lot/bankrupt/index']],
                            ['label' => 'СРО', 'url' => ['/lot/sro/index']]
                          ]],
                          ['label' => 'Услуги', 'url' => ['services/index'], 'items' => [
                            // ['label' => 'Консультация специалиста', 'url' => ['service/specialist']],
                            ['label' => 'Услуги агента', 'url' => ['service/agent']],
                            ['label' => 'Оценка лота', 'url' => ['service/lot']],
                            ['label' => 'Как приобрести лот?', 'url' => ['service/ecp']]
                          ]],
                          // ['label' => 'Войти', 'url' => ['services/index'],
                          // 'options'=>['data-toggle'=>'modal','data-target'=>'#loginFormTabInModal', 'data-target'=>'#loginFormTabInModal']
                        
                          // ],
                          // 'options'=>['data-toggle'=>'modal', 'data-target'=>'#loginFormTabInModal', 'data-backdrop'=>'static', 'data-keyboard'='false'

                          
                        ],
                        'options' => [
                          'class' => 'main-nav',
                        ],
                        'activeCssClass' => 'active',
                      ]); ?>

                    </nav>
                    <!--/.nav-collapse -->

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

    <? if (!Yii::$app->params['isOffFooter']) {?>
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
                    <a href="tel:<?= $setting[8]->value ?>"><span
                        class="icon-font text-primary inline-block-middle mr-5 font16"><i
                          class="fa fa-phone"></i></span> <span class="font700 text-black">Техническая поддержка
                        пользователей: <?= $setting[8]->value ?></span>
                      <!--<span class="text-muted">Mon-Fri | 8.30am-6:30pm</span>--></a>
                  </li>
                  <li>
                    <a href="mailto:help@ei.ru"><span class="icon-font text-primary inline-block-middle mr-5 font16"><i
                          class="fa fa-envelope"></i></span> <span class="font700 text-black">help@ei.ru</span></a>
                  </li>
                </ul>
              </div>

            </div>

          </div>

          <hr class="opacity-7" />

        </div>

        <div class="main-footer">

          <div class="container">

            <div class="row gap-50">

              <a href="tel:88006003305" class="link-mobile">
                <span>
                  8 (800) 600-33-05
                </span>
                <img src="<?= Url::base() ?>/img/telephone.svg" alt="icon_phone" class="link-mobile__icon">
              </a>

              <div class="col-lg-4">

                <div class="footer-logo">
                  <svg xmlns="http://www.w3.org/2000/svg" class="logo logo-footer" width="168" height="150"
                    viewBox="0 0 1680 1500" shape-rendering="geometricPrecision">
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

                <p class="mt-20">Единый информатор собирает, обрабатывает, дополняет информацию с торговых площадок
                  России.</p>

                <!-- <a href="<?=Url::to(['pages/about'])?>" class="text-capitalize font14 h6 line-1 mb-0 font500 mt-30">Узнать больше <i class="elegent-icon-arrow_right font18 inline-block-middle"></i></a> -->

              </div>

              <div class="col-lg-8">

                <div class="col-inner">

                  <div class="row shrink-auto-sm gap-30">

                    <div class="col-lg-4 col-shrink">

                      <div class="col-inner">
                        <h5 class="footer-title">Меню</h5>
                        <ul class="footer-menu-list set-width">
                          <li><a href="/arbitrazhnye-upravlyayushchie">Арбитражные управляющие</a></li>
                          <li><a href="/sro">СРО</a></li>
                          <li><a href="/dolzhniki">Должники</a></li>
                          <li><a href="/service">Услуги</a></li>
                          <li><a href="/contact">Контакты</a></li>
                          <li><a href="/sitemap">Карта сайта</a></li>

                          <!-- <li><a href="/">Торги</a></li>
                                            <li><a href="/about">О компании</a></li>
                                            <li><a href="/arbitrazhnye-upravlyayushchie">Реестры</a></li>
                                            <li><a href="/about">О нас</a></li> -->
                        </ul>
                      </div>

                    </div>

                    <div class="col-lg-4 col-shrink">

                      <div class="col-inner">
                        <h5 class="footer-title">Купить имущество</h5>
                        <ul class="footer-menu-list set-width">
                          <li><a href="/bankrupt">Банкротное имущество</a></li>
                          <li><a href="/arrest">Арестованное имущество</a></li>
                          <!-- <li><a href="#buyLotModal" class="font-sm" data-toggle="modal" data-target="#buyLotModal" data-backdrop="static" data-keyboard="false">buyLotModal</a></li> -->


                          <!-- <li><a href="/business">Залоговое имущество</a></li> -->

                        </ul>
                      </div>

                    </div>

                    <div class="col-lg-4 col-shrink">

                      <div class="col-inner">
                        <h5 class="footer-title">Социальные сети</h5>

                        <div class="footer-socials mt-20">
                          <!-- <a href="#"><i class="fab fa-telegram" target="_blank"></i></a> -->
                          <!-- <a href="https://vk.com/eiru_torgi"><i class="fab fa-vk" target="_blank"></i></a> -->
                          <a href="https://www.instagram.com/ei.ru_torgi" target="_blank"><i
                              class="fab fa-instagram"></i></a>
                          <a href="https://www.facebook.com/ei.ru.life" target="_blank"><i class="fab fa-facebook"
                              target="_blank"></i></a>
                        </div>
                      </div>

                    </div>

                  </div>

                </div>
                </form>
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
          <!-- <div class="col-inner">
                            <ul class="footer-menu-list-02">
                                <li><a href="#">Cookies</a></li>
                                <li><a href="#">Policies</a></li>
                                <li><a href="#">Terms</a></li>
                                <li><a href="#">Blogs</a></li>
                            </ul>
                        </div> -->
          <!-- </div>
                    
                </div> -->
          <div class="row shrink-auto-md gap-10 gap-40-lg pb-4">

            <div class="col-auto">
              <div class="col-inner">
                <hr>
                <p class="footer-copy-center">Обращаем ваше внимание на то, что данный Интернет-сайт носит исключительно
                  информационный характер и ни при каких условиях не является публичной офертой, определяемой
                  положениями Статьи 437 Гражданского кодекса Российской Федерации.</p>
                <p class="footer-copy-center">
                  <a href="/policy">Политика конфиденциальности</a> и <a href="/license"> Лицензионное соглашение</a> ©
                  2019 ei.ru

                </p>
              </div>
            </div>

          </div>

        </div>

      </div>

    </div>

  </div>

  </footer>
  <!-- start Footer Wrapper -->
  <? } ?>


  </div>
  <!-- end Body Inner -->



  <!-- start Login modal -->
  <div class="modal fade modal-with-tabs form-login-modal" id="loginFormTabInModal" aria-labelledby="modalWIthTabsLabel"
    tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content shadow-lg">
        <div class="pt-4 pr-4 ml-auto">
          <button type="button" class="close" data-dismiss="modal" aria-labelledby="Close">
            <span aria-hidden="true"><i class="far fa-times-circle"></i></span>
          </button>
        </div>

        <nav class="d-none">
          <ul class="nav external-link-navs clearfix">
            <li><a class="active" data-toggle="tab" href="#loginFormTabInModal-login">Sign-in</a></li>
            <li><a data-toggle="tab" href="#loginFormTabInModal-register">Register </a></li>
            <li><a data-toggle="tab" href="#loginFormTabInModal-forgot-pass">Forgot Password </a></li>
            <li><a data-toggle="tab" href="#loginFormTabInModal-thank">Thank</a></li>
          </ul>
        </nav>

        <div class="tab-content">

          <?= LoginWidget::widget() ?>

          <?= SignupWidget::widget() ?>

          <?= ResetPasswordWidget::widget() ?>

        </div>

      </div>
    </div>
  </div>
  <!-- end Login modal -->


  <!-- start Login modal -->
  <div class="modal fade modal-with-tabs form-login-modal" id="buyLotModal" aria-labelledby="modalWIthTabsLabel"
    tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 500px">
      <div id="help-steps" class="modal-content shadow-lg help-steps p-3">

        <div class="pt-4 pr-4 ml-auto">
          <button type="button" class="close" data-dismiss="modal" aria-labelledby="Close">
            <span aria-hidden="true"><i class="far fa-times-circle"></i></span>
          </button>
        </div>
        <div class="help-step" v-for="(item, index) in steps" v-if="index === step - 1">

          <img v-if="(steps.length > step)" :src="'/help/' + (index + 1) + '.svg'" alt="w-100">
          <div class="help-step__text py-4"><strong v-html="item.text"></strong></div>
        </div>

        <div class="d-flex ">
          <button v-if="step != 1" class="btn btn-outline-primary w-25" @click="prevStep"><i
              class="fas fa-chevron-left"></i></button>
          <button v-if="steps.length > step" class="btn btn-outline-primary ml-auto w-25" @click="nextStep"><i
              class="fas fa-chevron-right"></i></button>
        </div>

      </div>
    </div>
  </div>
  <!-- end Login modal -->






  <!-- start Back To Top -->
  <a id="back-to-top" href="#" class="back-to-top" role="button" title="Click to return to top" data-toggle="tooltip"
    data-placement="left"><i class="elegent-icon-arrow_carrot-up"></i></a>
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