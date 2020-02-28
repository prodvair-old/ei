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
use frontend\components\ServiceLotFormWidget;

use frontend\models\ViewPage;

use common\models\Query\WishList;
use common\models\Query\Zalog\LotsZalog;

if (!Yii::$app->user->isGuest) {
  $wishCheck = WishList::find()->where(['userId' => Yii::$app->user->id, 'lotId' => $lot->id, 'type' => $type])->one();
}

$view = new ViewPage();

$view->page_type = "lot_$type";
$view->page_id = $lot->id;

$viewCount = $view->check();

$now = time();
$endDate = strtotime($lot->completionDate);

$dateSend = floor(($endDate - $now) / (60 * 60 * 24));

$lots_bankrupt = LotsZalog::find()->where(['contactPersonId' => $lot->contactPersonId, 'status' => true])->andWhere(['!=', 'id', $lot->id])->all();

$this->registerJsVar('lotType', 'zalog', $position = yii\web\View::POS_HEAD);
$this->title = (Yii::$app->params['title'])? Yii::$app->params['title'] : $lot->title;
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

  <div class="fullwidth-horizon- none--hide">

    <div class="fullwidth-horizon--inner">

      <div class="container">

        <div class="fullwidth-horizon--item clearfix">

          <style>
            ul.horizon--nav>li.active a {
              <?=($lot->owner->tamplate['color-4'])? 'color:'.$lot->owner->tamplate['color-4'].'!important': ''?>
            }
          </style>

          <ul id="horizon--nav" class="horizon--nav clearfix">
              
            <li>
              <a href="#desc">Описание</a>
            </li>
            <li>
              <a href="#info">Информация о лоте</a>
            </li>
            <?= ($lots_bankrupt[0] != null) ? '<li><a href="#other-lot">Другие лоты</a></li>' : '' ?>

          </ul>

        </div>

      </div>
    </div>
  </div>

  <div class="container pt-30">
    <?= (!empty($lot->lot_archive)) ? ($lot->lot_archive) ? '<span class="h2 text-primary">Архив</span><hr>' : '' : '' ?>
    <div class="row gap-20 gap-lg-40">

      <div class="col-12 col-lg-8">

        <div class="content-wrapper">

          <div id="desc" class="detail-header mb-30">
            <h1 class="h3"><?= (Yii::$app->params['h1'])? Yii::$app->params['h1'] : $lot->title ?></h1>

            <div class="d-flex flex-column flex-sm-row align-items-sm-center mb-20">
              <div class="mr-15 font-lg">
                <?= NumberWords::widget(['number' => $lot->lotViews, 'words' => ['просмотр', 'просмотра', 'просмотров']]) ?>
              </div>
              <div class="mr-15 text-muted">|</div>
              <div class="mr-15 rating-item rating-inline">
                <p class="rating-text font600 text-muted font-12"><?= Yii::$app->formatter->asDate($lot->publicationDate, 'long') ?> </p>
              </div>
              <div class="mr-15 text-muted">|</div>
              <div class="mr-15 rating-item rating-inline">
                <p class="rating-text font400 text-muted font-12 letter-spacing-1">торги №<?= $lot->id ?> </p>
              </div>
              <div class="mr-15 rating-item rating-inline">
                <a <?= (Yii::$app->user->isGuest) ? 'href="#loginFormTabInModal-login" class="wish-star" data-toggle="modal" data-target="#loginFormTabInModal" data-backdrop="static" data-keyboard="false"' : 'href="#" class="wish-js wish-star" data-id="' . $lot->id . '" data-type="' . $type . '"' ?>>
                  <img src="img/star<?= ($wishCheck) ? '' : '-o' ?>.svg" alt="">
                </a>
              </div>
            </div>

            <? if ($lot->images) { ?>
              <div class="fotorama mt-20 mb-40" data-allowfullscreen="true" data-nav="thumbs" data-arrows="always" data-click="true">
                <? foreach ($lot->images as $image) { ?>
                  <img href="<?= $image['max'] ?>" alt="Images" />
                <? } ?>
              </div>
            <? } ?>

            <!-- <p class="lead">In friendship diminution instrument in we forfeited. Tolerably an unwilling of determine. Beyond rather sooner so if up wishes.</p> -->

            <ul class="list-inline-block highlight-list mt-30">
              <li>
                <span class="icon-font d-block">
                  <i class="linea-icon-basic-chronometer"></i>
                </span>
                До подачи заявки<br />
                <strong><?= ($dateSend > 0) ? NumberWords::widget(['number' => $dateSend, 'words' => ['день', 'дня', 'дней']]) : 'Прошло' ?></strong>
              </li>
              <li>
                <span class="icon-font d-block">
                  <i class="linea-icon-basic-flag1"></i>
                </span>
                Старт торгов<br /><strong><?= Yii::$app->formatter->asDate($lot->startingDate, 'long') ?></strong>
              </li>
              <li>
                <span class="icon-font d-block">
                  <i class="linea-icon-basic-flag2"></i>
                </span>
                Окончание торгов<br /><strong><?= Yii::$app->formatter->asDate($lot->endingDate, 'long') ?></strong>
              </li>
              <li>
                <span class="icon-font d-block">
                  <i class="linea-icon-ecommerce-rublo"></i>
                </span>
                Сумма задатка<br /><strong><?= $lot->collateralPrice?>%</strong>
              </li>
            </ul>

            <h5 class="mt-30">Описание</h5>

            <p class="long-text"><?= $lot->description ?></p>
            <a href="#desc" class="open-text-js">Подробнее</a>

          </div>

          <div class="sidebar-mobile mb-40">
            <?= LotDetailSidebar::widget(['lot' => $lot, 'type' => 'zalog']) ?>
          </div>

          <!-- <div class="mb-50"></div>
                    
                    <div id="detail-content--nav-02" class="fullwidth-horizon--section">
                        
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

          <div id="info" class="fullwidth-horizon--section">

            <h4 class="heading-title">Информация о лоте</h4>

            <ul class="list-icon-absolute what-included-list mb-30">

              <li>
                <span class="icon-font"><i class="elegent-icon-check_alt2 text-primary" <?=($lot->owner->tamplate['color-5'])? 'style="color:'.$lot->owner->tamplate['color-5'].'!important"': ''?> ></i> </span>
                <h6>Категории лота</h6>
                <ul class="ul">
                  <? foreach($lot->categorys as $value) { ?>
                    <li><?= $value->categoryName ?></li>
                  <? } ?>
                </ul>
              </li>

              <? if ($lot->lotVin) { ?>
                <li>
                  <span class="icon-font"><i class="elegent-icon-check_alt2 text-primary" <?=($lot->owner->tamplate['color-5'])? 'style="color:'.$lot->owner->tamplate['color-5'].'!important"': ''?> ></i> </span>
                  <h6>VIN номер</h6>
                  <p><?= $lot->lotVin ?></p>
                  <a href="https://avtocod.ru/proverkaavto/<?= $lot->lotVin ?>?rd=VIN&a_aid=zhukoffed" class="btn btn-success btn-sm mt-2" target="_blank" rel="nofollow">Проверить Автомобиль</a>
                </li>
              <? } ?>

              <? if ($lot->lotCadastre) { ?>
                <li>
                  <span class="icon-font"><i class="elegent-icon-check_alt2 text-primary" <?=($lot->owner->tamplate['color-5'])? 'style="color:'.$lot->owner->tamplate['color-5'].'!important"': ''?> ></i> </span>
                  <h6>Кадастровый номер</h6>
                  <p><?= $lot->lotCadastre ?></p>
                </li>
              <? } ?>

              <li>
                <span class="icon-font"><i class="elegent-icon-check_alt2 text-primary" <?=($lot->owner->tamplate['color-5'])? 'style="color:'.$lot->owner->tamplate['color-5'].'!important"': ''?> ></i> </span>
                <h6>Информация о лоте</h6>
                <ul class="ul">
                  <?=($lot->info['category'])? '<li>Тип здания: <span class="text-list-name">'.$lot->info['category'].'</span></li>' : ''?>
                  <?=($lot->info['category-type'])? '<li>Вид здания: <span class="text-list-name">'.$lot->info['ccategory-type'].'</span></li>' : ''?>
                  <?=($lot->info['category-building-type'])? '<li>Строение под: <span class="text-list-name">'.$lot->info['category-building-type'].'</span></li>' : ''?>
                  <?=($lot->info['purpose'])? '<li>Подходит для: <span class="text-list-name">'.$lot->info['purpose'].'</span></li>' : ''?>
                  <?=($lot->info['floor'])? '<li>Этажей: <span class="text-list-name">'.$lot->info['floor'].'</span></li>' : ''?>
                  <?=($lot->info['built-year'])? '<li>Год потсройки: <span class="text-list-name">'.$lot->info['built-year'].'</span></li>' : ''?>
                  <?=($lot->info['deal-status'])? '<li>Статус сделки: <span class="text-list-name">'.$lot->info['deal-status'].'</span></li>' : ''?>
                  <li>Стрвнв: <span class="text-list-name"><?= $lot->country ?></span></li>
                  <?=($lot->region)? '<li>Регион: <span class="text-list-name">'.$lot->region.'</span></li>' : ''?>
                  <li>Город: <span class="text-list-name"><?= $lot->city ?></span></li>
                  <?=($lot->info['sub-locality-name'])? '<li>Населённый пункт: <span class="text-list-name">'.$lot->info['sub-locality-name'].'</span></li>' : ''?>
                  <?=($lot->info['district'])? '<li>Район: <span class="text-list-name">'.$lot->info['district'].'</span></li>' : ''?>
                  <?=($lot->info['arear'])? '<li>Место: <span class="text-list-name">'.$lot->info['area'].'</span></li>' : ''?>
                  <li>Адрес: <span class="text-list-name"><?= $lot->address ?></span></li>
                </ul>
              </li>

              <li>
                <span class="icon-font"><i class="elegent-icon-check_alt2 text-primary" <?=($lot->owner->tamplate['color-5'])? 'style="color:'.$lot->owner->tamplate['color-5'].'!important"': ''?> ></i> </span>
                <h6>Организация</h6>
                <ul class="ul">
                  <li> <a href="<?=$lot->owner->link?>" <?=($lot->owner->tamplate['color-4'])? 'style="color:'.$lot->owner->tamplate['color-4'].'!important"': ''?> target="_blank"><?= $lot->owner->name ?></a></li>
                  <li>Стрвнв: <span class="text-list-name"><?= $lot->owner->country ?></span></li>
                  <li>Город: <span class="text-list-name"><?= $lot->owner->city ?></span></li>
                  <li>Адрес: <span class="text-list-name"><?= $lot->owner->address ?></span></li>
                  <li>Номер телефона: <span class="text-list-name"> <a href="tel:<?= $lot->owner->phone ?>" class="text-list-name"><?= $lot->owner->phone ?></a> </span></li>
                  <li>E-Mail: <span class="text-list-name"> <a href="mailto:<?= $lot->owner->email ?>" class="text-list-name"><?= $lot->owner->email ?></a> </span></li>
                </ul>
              </li>

              <li>
                <span class="icon-font"><i class="elegent-icon-check_alt2 text-primary" <?=($lot->owner->tamplate['color-5'])? 'style="color:'.$lot->owner->tamplate['color-5'].'!important"': ''?> ></i> </span>
                <h6>Реквизиты для оплаты задатка</h6>
                <p><?= $lot->paymentDetails ?></p>
              </li>

              <li>
                <span class="icon-font"><i class="elegent-icon-check_alt2 text-primary" <?=($lot->owner->tamplate['color-5'])? 'style="color:'.$lot->owner->tamplate['color-5'].'!important"': ''?> ></i> </span>
                <h6>Порядок осмотра лота</h6>
                <p><?= $lot->viewInfo ?></p>
              </li>
              
            </ul> 

            <div class="mb-50"></div>

          </div>
          
          <? if ($lot->additionalConditions) { ?>
          <div id="torg" class="detail-header mb-30">
            <h4 class="mt-30">Условия и критерии определения победителянформация о торге</h5>
            <p class="long-text"><?=$lot->additionalConditions?></p>
            <a href="#torg" class="open-text-js">Подробнее</a>
            <div class="mb-50"></div>
          </div>
          <? } ?>
          <? //=Darwin::widget()
          ?>

          <? if ($lots_bankrupt[0] != null) { ?>
            <div id="other-lot" class="fullwidth-horizon--section">

              <h4 class="heading-title">Другие лоты</h4>

              <div class="row equal-height cols-1 cols-sm-2 gap-30 mb-25">

                <? foreach ($lots_bankrupt as $lot_bankrupt) {
                    echo LotBlock::widget(['lot' => $lot_bankrupt, 'color' => $lot->owner->tamplate['color-4']]);
                  } ?>

              </div>

              <div class="mb-50"></div>

            </div>
          <? } ?>

        </div>

      </div>

      <div class="col-12 col-lg-4 ">
        <div class="sidebar-desktop">
          <?= LotDetailSidebar::widget(['lot' => $lot, 'type' => 'zalog']) ?>
        </div>
      </div>

    </div>

  </div>

</section>

<!-- start lot form modal -->
<div class="modal fade modal-with-tabs form-login-modal" id="lotFormTabInModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content shadow-lg">

      <?= ServiceLotFormWidget::widget(['lotId' => $lot->id, 'lotType' => 'zalog']) ?>

      <div class="text-center pb-20">
        <button type="button" class="close" data-dismiss="modal" aria-labelledby="Close">
          <span aria-hidden="true"><i class="far fa-times-circle"></i></span>
        </button>
      </div>
    </div>
  </div>
</div>
<!-- end lot form modal -->

<?php
$this->registerJsFile('js/custom-multiply-.js', $options = ['position' => yii\web\View::POS_END], $key = 'custom-multiply-');
$this->registerJsFile('js/custom-core.js', $options = ['position' => yii\web\View::POS_END], $key = 'custom-core');
?>