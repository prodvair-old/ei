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
use common\models\Query\Lot\Lots;

if (!Yii::$app->user->isGuest) {
    $wishCheck = WishList::find()->where(['userId' => Yii::$app->user->id, 'lotId' => $lot->id, 'type' => $type])->one();
}

$view = new ViewPage();

$view->page_type = "lot_".$lot->torg->type;
$view->page_id = $lot->id;

$viewCount = $view->check();

$now = time();
$endDate = strtotime($lot->torg->endDate);

$dateSend = floor(($endDate - $now) / (60 * 60 * 24));

$lots_bankrupt = Lots::find()->joinWith(['torg.bankrupt'])->alias('lot')->where(['bankrupt.id'=>$lot->torg->bankrupt->id])->andWhere(['!=', 'lot.id', $lot->id])->all();

$this->registerJsVar( 'lotType', 'bankrupt', $position = yii\web\View::POS_HEAD );
$this->title = $lot->title;
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
    
    <div class="fullwidth-horizon-sticky none-sticky-hide">
    
        <div class="fullwidth-horizon-sticky-inner">
        
            <div class="container">
           
                
                <div class="fullwidth-horizon-sticky-item clearfix">
                        
                    <ul id="horizon-sticky-nav" class="horizon-sticky-nav clearfix">
                        <li>
                            <a href="#desc">Описание</a>
                        </li>
                        <li>
                            <a href="#info">Информация о лоте</a>
                        </li>
                        <?=($lot->torg->publisherId == 1)? '<li><a href="#price-history">Этапы снижения цены</a></li>': ''?>
                        <li>
                            <a href="#docs">Документы</a>
                        </li>
                        <?=($lots_bankrupt[0] != null)? '<li><a href="#other-lot">Другие лоты</a></li>': ''?>
                        <li>
                            <a href="#torg">Информация о торге</a>
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
       
        <?=(!empty($lot->archive))? ($lot->archive)? '<span class="h3 text-primary">Архив</span><hr>' : '' : ''?>                 

        <div class="row gap-20 gap-lg-40">
            
            <div class="col-12 col-lg-8">
                
                <div class="content-wrapper">
                     
                    <div id="desc" class="detail-header mb-30">
                      <h1 class="h3"><?=Yii::$app->params['h1']?></h1>
              
                        <div class="d-flex flex-row align-items-sm-center mb-20">
                            <div class="mr-10 font-lg text-muted">
                              <?=$lot->viewsCount?> <i class="far fa-eye fa-sm"></i>
                                <!-- <?=NumberWords::widget(['number' => $lot->viewsCount, 'words' => ['просмотр', 'просмотра', 'просмотров']]) ?> -->
                            </div>
                            <div class="mr-10 text-muted">|</div>
                            <div class="mr-10 rating-item rating-inline">
                                <p class="rating-text font600 text-muted font-12"><?= Yii::$app->formatter->asDate($lot->torg->publishedDate, 'long')?> </p>
                            </div>
                            <div class="mr-10 text-muted">|</div>
                            <div class="mr-10 rating-item rating-inline">
                                <p class="rating-text font400 text-muted font-12 letter-spacing-1">торги №<?=$lot->id?> </p>
                            </div>
                            <div class="mr-10 text-muted">|</div>
                            <div class="mr-10 rating-item rating-inline">
                                <a <?=(Yii::$app->user->isGuest)? 'href="#loginFormTabInModal-login" class="wish-star" data-toggle="modal" data-target="#loginFormTabInModal" data-backdrop="static" data-keyboard="false"' : 'href="#" class="wish-js wish-star" data-id="'.$lot->id.'" data-type="'.$type.'"'?>>
                                    <img src="img/star<?=($wishCheck)? '' : '-o' ?>.svg" alt="">
                                </a>
                            </div>
                        </div>
                        
                        <?if ($lot->images) { ?>
                            <div class="fotorama mt-20 mb-40" data-allowfullscreen="true" data-nav="thumbs" data-arrows="always" data-click="true">
                                <? foreach ($lot->images['max'] as $image) { ?>
                                    <img href="<?=$image?>" alt="Images" />
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
                                <strong><?=($dateSend > 0)? NumberWords::widget(['number' => $dateSend, 'words' => ['день', 'дня', 'дней']]) : 'Прошло'?></strong>
                            </li>
                            <li>
                                <span class="icon-font d-block">
                                    <i class="linea-icon-basic-flag1"></i>
                                </span>
                                Старт торгов<br /><strong><?= Yii::$app->formatter->asDate($lot->torg->startDate, 'long')?></strong>
                            </li>
                            <li>
                                <span class="icon-font d-block">
                                    <i class="linea-icon-basic-flag2"></i>
                                </span>
                                Окончание торгов<br /><strong><?= Yii::$app->formatter->asDate($lot->torg->endDate, 'long') ?></strong>
                            </li>
                            <li>
                                <span class="icon-font d-block">
                                    <i class="linea-icon-ecommerce-rublo"></i>
                                </span>
                                Сумма задатка<br /><strong><?=($lot->depositTypeId == 1)? Yii::$app->formatter->asCurrency((($lot->price / 100) * $lot->deposit)) : Yii::$app->formatter->asCurrency($lot->deposit)?></strong>
                            </li>
                        </ul>
                        
                        <h5 class="mt-30">Описание</h5>

                        <p class="long-text"><?=$lot->description?></p>
                        <a href="#desc" class="open-text-js">Подробнее</a>
                        
                    </div>

                    <div class="sidebar-mobile mb-40">
                        <?= LotDetailSidebar::widget(['lot' => $lot, 'type' => $type]) ?>
                      </div>
                    
                    <!-- <div class="mb-50"></div>
                    
                    <div id="detail-content-sticky-nav-02" class="fullwidth-horizon-sticky-section">
                        
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
                    
                    <div id="info" class="fullwidth-horizon-sticky-section">
                    
                        <h4 class="heading-title">Информация о лоте</h4>
                        
                        <ul class="list-icon-absolute what-included-list mb-30">

                            <li>
                                <span class="icon-font"><i class="elegent-icon-check_alt2 text-primary"></i> </span> 
                                <h6>Категории лота</h6>
                                <ul class="ul">
                                    <?foreach ($lot->categorys as $category) { ?>
                                        <li><?=$category->name?></li>
                                    <? }?>
                                </ul>
                            </li>
                            
                            <? if ($lot->info['vin']) { ?>
                                <li>
                                    <span class="icon-font"><i class="elegent-icon-check_alt2 text-primary"></i> </span> 
                                    <h6>VIN номер</h6>
                                    <p><?= $lot->info['vin'] ?></p>
                                    <a href="https://avtocod.ru/proverkaavto/<?=$lot->info['vin']?>?rd=VIN&a_aid=zhukoffed"
                                        class="btn btn-success btn-sm mt-2" target="_blank" rel="nofollow">Проверить Автомобиль</a>
                                </li>    
                            <? } ?>
                            
                            <? if ($lot->info['cadastreNumber']) { ?>
                                <li>
                                    <span class="icon-font"><i class="elegent-icon-check_alt2 text-primary"></i> </span> 
                                    <h6>Кадастровый номер</h6>
                                    <p><?= $lot->info['cadastreNumber'] ?></p>
                                </li>    
                            <? } ?>

                            <li>
                                <span class="icon-font"><i class="elegent-icon-check_alt2 text-primary"></i> </span> 
                                <h6>Должник</h6>
                                <ul class="ul">
                                    <li><a href="<?=Url::to(['doljnik/list'])?>/<?=$lot->torg->bankrupt->oldId?>" target="_blank"><?=$lot->torg->bankrupt->name?></a></li>
                                    <li>ИНН: <span class="text-list-name"><?= $lot->torg->bankrupt->inn?></span></li>
                                    <li>Адрес: <span class="text-list-name"><?=  $lot->torg->bankrupt->address?></span></li>
                                </ul>
                            </li>
                            
                            <li>
                                <span class="icon-font"><i class="elegent-icon-check_alt2 text-primary"></i> </span> 
                                <h6>Сведения о деле</h6>
                                <ul class="ul">
                                    <li>Номер дела: <span class="text-list-name"><?= $lot->torg->case->number ?></span></li>
                                    <li>Арбитражный суд: <span class="text-list-name"><a href="<?=Url::to(['sro/list'])?>/<?=$lot->torg->publisher->sro->oldId?>" target="_blank"><?= $lot->torg->publisher->sro->title?></a></span></li>
                                    <li>Адрес суда: <span class="text-list-name"><?= $lot->torg->publisher->sro->address?></span></li>
                                </ul>
                            </li>
                            
                            <li>
                                <span class="icon-font"><i class="elegent-icon-check_alt2 text-primary"></i> </span> 
                                <h6>Арбитражный управляющий</h6>
                                <ul class="ul">
                                    <li><a href="<?=Url::to(['arbitr/list'])?>/<?=$lot->torg->publisher->oldId?>" target="_blank"><?=$lot->torg->publisher->fullName?></a></li>
                                    <li>Рег. номер: <span class="text-list-name"><?= $lot->torg->publisher->regnum?></span></li>
                                    <li>ИНН: <span class="text-list-name"><?= $lot->torg->publisher->inn?></span></li>
                                    <li>ОГРН: <span class="text-list-name"><?= $lot->torg->publisher->info['ogrn']?></span></li>
                                </ul>
                            </li>
                            
                        </ul>
                        
                        <div class="mb-50"></div>
                        
                    </div>
                    
                    <?php if($lot->torg->tradeType == 'PublicOffer') { ?>

                        <div id="price-history" class="fullwidth-horizon-sticky-section">

                            <h4 class="heading-title">Этапы снижения цены</h4>
                            
                            <div class="mb-20"></div>
                            
                            <div class="item-text-long-wrapper">
                            
                                <div class="item-heading text-muted">
                                
                                    <div class="row d-none d-sm-flex">
                                    
                                        <div class="col-12 col-sm-6">
                                        
                                            <div class="col-inner">
                                            
                                                <div class="row gap-10">
                                                
                                                    <div class="col-5">
                                                        Начало периода
                                                    </div>
                                                    
                                                    <div class="col-2">
                                                    
                                                    </div>
                                                    
                                                    <div class="col-5">
                                                        Окончание периода
                                                    </div>
                                                    
                                                </div>
                                            
                                            </div>
                                        
                                        </div>
                                        
                                        <div class="col-12 col-sm-6">
                                        
                                            <div class="col-inner">
                                            
                                                <div class="row gap-10">
                                                
                                                    <div class="col-6 text-center">
                                                        Текущая цена, руб.
                                                    </div>
                                                    
                                                    <div class="col-6 text-right">
                                                        Размер задатка, руб.
                                                    </div>
                                                    
                                                </div>
                                                
                                            </div>
                                            
                                        </div>
                                        
                                        
                                    </div>
                                
                                </div>
                                
                                <?php 
                                    if ($lot->offer != null) { 
                                        foreach ($lot->offer as $key => $value) { 
                                        if ($value->ofrRdnLotNumber == $lot->lotid) {
                                            $date = Yii::$app->formatter->asDatetime(new \DateTime(), "php:Y-m-d H:i:s");
                                ?>
                                    <div class="item-text-long <?=(($key == 0 || $value->ofrRdnDateTimeBeginInterval <= $date) && $value->ofrRdnDateTimeEndInterval >= $date)? '' : 'sold-out' ?>">
                                    
                                        <div class="row align-items-center">
                                        
                                            <div class="col-12 col-sm-6">
                                            
                                                <div class="col-inner mb-10 mb-sm-0">
                                                
                                                    <div class="row gap-10 align-items-center">
                                                    
                                                        <div class="col-6">
                                                            <span class="font-sm">Начало</span>
                                                            <strong class="d-block"><?=Yii::$app->formatter->asDate($value->ofrRdnDateTimeBeginInterval, 'long')?></strong>
                                                        </div>
                                                        
                                                        <!-- <div class="col-2">
                                                            <span class="day-count mt-3">3<br/>days</span>
                                                        </div> -->
                                                        
                                                        <div class="col-6 text-right text-sm-left">
                                                            <span class="font-sm">Конец</span>
                                                            <strong class="d-block"><?=Yii::$app->formatter->asDate($value->ofrRdnDateTimeEndInterval, 'long')?></strong>
                                                        </div>
                                                        
                                                    </div>
                                                
                                                </div>
                                            
                                            </div>
                                            
                                            <div class="col-12 col-sm-6">
                                            
                                                <div class="col-inner">
                                                
                                                    <div class="row gap-10 align-items-center">
                                                    
                                                        <div class="col-6 text-left text-sm-center">
                                                            <span class="font-sm">Цена </span>
                                                            <strong class="d-block"><?=Yii::$app->formatter->asCurrency($value->ofrRdnPriceInInterval)?></strong>
                                                        </div>
                                                        
                                                        <div class="col-6 text-left  text-sm-right">
                                                            <span class="font-sm">Задаток</span>
                                                            <strong class="d-block"><?=($lot->advancestepunit == 'Percent')? Yii::$app->formatter->asCurrency((($value->ofrRdnPriceInInterval / 100) * $lot->advance)) : Yii::$app->formatter->asCurrency($lot->advance)?></strong>
                                                        </div>
                                                        
                                                    </div>
                                                    
                                                </div>
                                                
                                            </div>
                                            

                                            
                                            <!-- <div class="col-4 col-sm-2">
                                                <a href="#" class="btn btn-primary btn-block btn-sm mt-3">Купить</a>
                                            </div> -->
                                            
                                        </div>
                                    
                                    </div>
                                <? } } }  else { ?>
                                    <div class="item-text-long <?=(($key == 0 || $value->ofrRdnDateTimeBeginInterval <= $date) && $value->ofrRdnDateTimeEndInterval >= $date)? '' : 'sold-out' ?>">
                                    
                                        <div class="row align-items-center">
                                        
                                            <div class="col-7 col-sm-9">
                                                <strong class="text-primary">Этапы снижения цены в данной момент находятся в обработке</strong>
                                            </div>

                                            <div class="col-5 col-sm-3">
                                                <a href="#" class="btn btn-primary btn-block btn-sm mt-3">Задать вопрос</a>
                                            </div>
                                        </div>
                                    
                                    </div>
                                <? } ?>
                            </div>

                            <div class="mb-50"></div>
                            
                        </div>

                    <? } ?>

                    <?//=Darwin::widget()?>
                    <div id="docs" class="fullwidth-horizon-sticky-section">
                        <h4 class="heading-title">Документы</h4>
                        <ul class="list-icon-absolute what-included-list mb-30 long-text">

                            <? foreach ($lot->torg->case->documents as $document) { ?>

                                <?
                                    switch ($document->format) {
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
                                    <span class="icon-font"><?=$icon?></span> 
                                    <a href="<?=$document->url?>" target="_blank"><?=$document->name?></a>
                                </li>
                            
                            <? } ?>
                            
                        </ul>
                        <a href="#docs" class="open-text-js">Все документы</a>
                        <div class="mb-50"></div>
                    </div>

                    <? if ($lots_bankrupt[0] != null) { ?>
                    <div id="other-lot" class="fullwidth-horizon-sticky-section">

                        <h4 class="heading-title">Другие лоты должника</h4>
                        
                        <div class="row equal-height cols-1 cols-sm-2 gap-30 mb-25">
            
                            <?foreach ($lots_bankrupt as $lot_bankrupt) { echo LotBlock::widget(['lot' => $lot_bankrupt]); }?>
                        
                        </div>
                        
                        <div class="mb-50"></div>
                        
                    </div>
                    <? } ?>
                    

                    <div id="torg" class="detail-header mb-30">
                        <h4 class="mt-30">Информация о торге</h5>
                        <p class="long-text"><?=$lot->torg->description?></p>
                        <a href="#torg" class="open-text-js">Подробнее</a>
                        <div class="mb-50"></div>
                    </div>

                    <div id="roles" class="detail-header mb-30">
                        <h4 class="mt-30">Правила подачи заявок</h5>
                        <p class="long-text"><?=$lot->torg->info['rules']?></p>
                        <a href="#roles" class="open-text-js">Подробнее</a>
                    </div>

                    <!-- <div id="faq" class="fullwidth-horizon-sticky-section">
                    
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
            
            <div class="col-12 col-lg-4">

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
            
            <?=ServiceLotFormWidget::widget(['lotId' => $lot->id, 'lotType' => $type])?>
            
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
$this->registerJsFile( 'js/custom-multiply-sticky.js', $options = ['position' => yii\web\View::POS_END], $key = 'custom-multiply-sticky' );
$this->registerJsFile( 'js/custom-core.js', $options = ['position' => yii\web\View::POS_END], $key = 'custom-core' );
?>