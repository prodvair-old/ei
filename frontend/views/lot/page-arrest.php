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
use common\models\Query\Arrest\LotsArrest;

if (!Yii::$app->user->isGuest) {
  $wishCheck = WishList::find()->where(['userId' => Yii::$app->user->id, 'lotId' => $lot->lotId, 'type' => $type])->one();
}

$view = new ViewPage();

$view->page_type = "lot_$type";
$view->page_id = $lot->lotId;

$viewCount = $view->check();

$now = time();
$endDate = strtotime($lot->torgs->trgExpireDate);

$dateSend = floor(($endDate - $now) / (60 * 60 * 24));

$lots_bankrupt = LotsArrest::find()->where(['lotBidNumber' => $lot->lotBidNumber])->andWhere(['!=', 'lotId', $lot->lotId])->all();

$this->registerJsVar('lotType', 'bankrupt', $position = yii\web\View::POS_HEAD);
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

  <div class="fullwidth-horizon- none--hide">

    <div class="fullwidth-horizon--inner">

      <div class="container">

        <div class="fullwidth-horizon--item clearfix">

          <ul id="horizon--nav" class="horizon--nav clearfix">
            <li>
              <a href="#desc">Описание</a>
            </li>
            <li>
              <a href="#info">Информация о лоте</a>
            </li>
            <li>
              <a href="#docs">Документы</a>
            </li>
            <?= ($lots_bankrupt[0] != null) ? '<li><a href="#other-lot">Другие лоты</a></li>' : '' ?>
            <li>
              <a href="#torg">Информация о контракте</a>
            </li>
            <li>
              <a href="#roles">Правила подачи заявки</a>
            </li>

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
            <h1 class="h3"><?= Yii::$app->params['h1'] ?></h1>

            <div class="d-flex flex-column flex-sm-row align-items-sm-center mb-20">
              <div class="mr-15 font-lg">
                <?= NumberWords::widget(['number' => $lot->lotViews, 'words' => ['просмотр', 'просмотра', 'просмотров']]) ?>
              </div>
              <div class="mr-15 text-muted">|</div>
              <div class="mr-15 rating-item rating-inline">
                <p class="rating-text font600 text-muted font-12"><?= Yii::$app->formatter->asDate($lot->torgs->trgPublished, 'long') ?> </p>
              </div>
              <div class="mr-15 text-muted">|</div>
              <div class="mr-15 rating-item rating-inline">
                <p class="rating-text font400 text-muted font-12 letter-spacing-1">торги №<?= $lot->lotId ?> </p>
              </div>
              <div class="mr-15 rating-item rating-inline">
                <a <?= (Yii::$app->user->isGuest) ? 'href="#loginFormTabInModal-login" class="wish-star" data-toggle="modal" data-target="#loginFormTabInModal" data-backdrop="static" data-keyboard="false"' : 'href="#" class="wish-js wish-star" data-id="' . $lot->lotId . '" data-type="' . $type . '"' ?>>
                  <img src="img/star<?= ($wishCheck) ? '' : '-o' ?>.svg" alt="">
                </a>
              </div>
            </div>

            <? if ($lot->lotImage) { ?>
              <div class="fotorama mt-20 mb-40" data-allowfullscreen="true" data-nav="thumbs" data-arrows="always" data-click="true">
                <? foreach ($lot->lotImage as $image) { ?>
                  <img href="<?= $image ?>" alt="Images" />
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
                Старт торгов<br /><strong><?= Yii::$app->formatter->asDate($lot->torgs->trgStartDateRequest, 'long') ?></strong>
              </li>
              <li>
                <span class="icon-font d-block">
                  <i class="linea-icon-basic-flag2"></i>
                </span>
                Окончание торгов<br /><strong><?= Yii::$app->formatter->asDate($lot->torgs->trgExpireDate, 'long') ?></strong>
              </li>
              <li>
                <span class="icon-font d-block">
                  <i class="linea-icon-ecommerce-rublo"></i>
                </span>
                Сумма задатка<br /><strong><?= Yii::$app->formatter->asCurrency($lot->lotDepositSize) ?></strong>
              </li>
            </ul>

            <h5 class="mt-30">Описание</h5>

            <p class="long-text"><?= $lot->lotPropName ?></p>
            <a href="#desc" class="open-text-js">Подробнее</a>

          </div>

          <div class="sidebar-mobile mb-40">
            <?= LotDetailSidebar::widget(['lot' => $lot, 'type' => $type]) ?>
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
                <span class="icon-font"><i class="elegent-icon-check_alt2 text-primary"></i> </span>
                <h6>Категории лота</h6>
                <ul class="ul">
                  <li><?= $lot->lotPropertyTypeName ?></li>
                </ul>
              </li>

              <? if ($lot->lotVin) { ?>
                <li>
                  <span class="icon-font"><i class="elegent-icon-check_alt2 text-primary"></i> </span>
                  <h6>VIN номер</h6>
                  <p><?= $lot->lotVin ?></p>
                  <a href="https://avtocod.ru/proverkaavto/<?= $lot->lotVin ?>?rd=VIN&a_aid=zhukoffed" class="btn btn-success btn-sm mt-2" target="_blank" rel="nofollow">Проверить Автомобиль</a>
                </li>
              <? } ?>

              <? if ($lot->lotCadastre) { ?>
                <li>
                  <span class="icon-font"><i class="elegent-icon-check_alt2 text-primary"></i> </span>
                  <h6>Кадастровый номер</h6>
                  <p><?= $lot->lotCadastre ?></p>
                </li>
              <? } ?>

              <li>
                <span class="icon-font"><i class="elegent-icon-check_alt2 text-primary"></i> </span>
                <h6>Банк</h6>
                <ul class="ul">
                  <li><?= $lot->lotPaymentRequisitesBankName ?></li>
                  <li>БИК: <span class="text-list-name"><?= $lot->lotPaymentRequisitesBik ?></span></li>
                  <li>Кор. счет: <span class="text-list-name"><?= $lot->lotPaymentRequisitesKs ?></span></li>
                  <li>Расчетный счет: <span class="text-list-name"><?= $lot->lotPaymentRequisitesRs ?></span></li>
                  <li>Лицевой счет: <span class="text-list-name"><?= $lot->lotPaymentRequisitesPs ?></span></li>
                </ul>
              </li>

              <li>
                <span class="icon-font"><i class="elegent-icon-check_alt2 text-primary"></i> </span>
                <h6>Организатора торгов</h6>
                <ul class="ul">
                  <li><?= $lot->torgs->trgFullName ?></li>
                  <li>Вышестоящая организация: <span class="text-list-name"><?= $lot->torgs->trgHeadOrg ?></span></li>
                  <li>Личный номер: <span class="text-list-name"><?= $lot->torgs->trgOrganizationId ?></span></li>
                  <li>Порог крупной сделки: <span class="text-list-name"><?= $lot->torgs->trgLimitBidDeal ?></span></li>
                  <li>ИНН: <span class="text-list-name"><?= $lot->torgs->trgInn ?></span></li>
                  <li>КПП: <span class="text-list-name"><?= $lot->torgs->trgKpp ?></span></li>
                  <li>ОКАТО: <span class="text-list-name"><?= $lot->torgs->trgOkato ?></span></li>
                  <li>ОКПО: <span class="text-list-name"><?= $lot->torgs->trgOkpo ?></span></li>
                  <li>ОКВЕД: <span class="text-list-name"><?= $lot->torgs->trgOkved ?></span></li>
                  <li>ОГРН: <span class="text-list-name"><?= $lot->torgs->trgOgrn ?></span></li>
                  <li>Факс: <span class="text-list-name"><?= $lot->torgs->trgFax ?></span></li>
                  <li>E-mail: <span class="text-list-name"><?= $lot->torgs->trgEmail ?></span></li>
                  <li>Номер телефона: <span class="text-list-name"><?= $lot->torgs->trgPhone ?></span></li>
                  <li>Почтовый адрес: <span class="text-list-name"><?= $lot->torgs->trgAddress ?></span></li>
                  <li>Фактический адрес: <span class="text-list-name"><?= $lot->torgs->trgLocation ?></span></li>
                </ul>
              </li>

            </ul>

            <div class="mb-50"></div>

          </div>

          <? //=Darwin::widget()
          ?>

          <div id="docs" class="fullwidth-horizon--section">

            <h4 class="heading-title">Документы</h4>

            <ul class="list-icon-absolute what-included-list mb-30">
              <? foreach ($lot->lotDocuments as $doc) { ?>

                <?
                  $fileType = strtolower(preg_replace('/^.*\.(.*)$/s', '$1', $doc->ldocDescription));

                  switch ($fileType) {
                    case 'doc':
                      $icon = '<i class="far fa-file-word"></i>';
                      break;
                    case 'docs':
                      $icon = '<i class="far fa-file-word"></i>';
                      break;
                    case 'xls':
                      $icon = '<i class="far fa-file-excel"></i>';
                      break;
                    case 'xlsx':
                      $icon = '<i class="far fa-file-excel"></i>';
                      break;
                    case 'pdf':
                      $icon = '<i class="far fa-file-pdf"></i>';
                      break;
                    case 'zip':
                      $icon = '<i class="far fa-file-archive"></i>';
                      break;
                    default:
                      $icon = '<i class="far fa-file"></i>';
                      break;
                  }
                  ?>
                  <li>
                    <span class="icon-font"><?= $icon ?></span>
                    <a href="<?= $doc->ldocUrl ?>" target="_blank"><?= $doc->ldocDescription ?></a>
                  </li>

                <? } ?>
                <? foreach ($lot->documents as $doc) { ?>

                  <?
                    $fileType = strtolower(preg_replace('/^.*\.(.*)$/s', '$1', ($doc->tdocDescription) ? $doc->tdocDescription : $doc->tdocType));

                    switch ($fileType) {
                      case 'doc':
                        $icon = '<i class="far fa-file-word"></i>';
                        break;
                      case 'docs':
                        $icon = '<i class="far fa-file-word"></i>';
                        break;
                      case 'xls':
                        $icon = '<i class="far fa-file-excel"></i>';
                        break;
                      case 'xlsx':
                        $icon = '<i class="far fa-file-excel"></i>';
                        break;
                      case 'pdf':
                        $icon = '<i class="far fa-file-pdf"></i>';
                        break;
                      case 'zip':
                        $icon = '<i class="far fa-file-archive"></i>';
                        break;
                      default:
                        $icon = '<i class="far fa-file"></i>';
                        break;
                    }
                    ?>
                    <li>
                      <span class="icon-font"><?= $icon ?></span>
                      <a href="<?= $doc->tdocUrl ?>" target="_blank"><?= ($doc->tdocDescription) ? $doc->tdocDescription : $doc->tdocType ?></a>
                    </li>

                  <? } ?>
            </ul>

            <a href="#docs" class="open-text-js">Все документы</a>


            <div class="mb-50"></div>

          </div>

          <? if ($lots_bankrupt[0] != null) { ?>
            <div id="other-lot" class="fullwidth-horizon--section">

              <h4 class="heading-title">Другие лоты должника</h4>

              <div class="row equal-height cols-1 cols-sm-2 gap-30 mb-25">

                <? foreach ($lots_bankrupt as $lot_bankrupt) {
                    echo LotBlock::widget(['lot' => $lot_bankrupt]);
                  } ?>

              </div>

              <div class="mb-50"></div>

            </div>
          <? } ?>

          <div id="torg" class="detail-header mb-30">
            <h4 class="mt-30">Информация о контракте</h5>
              <p class="long-text"><?= $lot->lotContractDesc ?></p>
              <a href="#torg" class="open-text-js">Подробнее</a>
              <div class="mb-50"></div>
          </div>

          <div id="roles" class="detail-header mb-30">
            <h4 class="mt-30">Правила подачи заявок</h5>
              <p class="long-text"><?= $lot->lotDepositDesc ?></p>
              <a href="#roles" class="open-text-js">Подробнее</a>
          </div>

          <!-- <div id="faq" class="fullwidth-horizon--section">
                    
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
                        
                    </div> -->

        </div>

      </div>

      <div class="col-12 col-lg-4 ">
        <div class="sidebar-desktop">
          <?= LotDetailSidebar::widget(['lot' => $lot, 'type' => $type]) ?>
        </div>
      </div>

    </div>

  </div>

</section>

<!-- start lot form modal -->
<div class="modal fade modal-with-tabs form-login-modal" id="lotFormTabInModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content shadow-lg">

      <?= ServiceLotFormWidget::widget(['lotId' => $lot->lotId, 'lotType' => $type]) ?>

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