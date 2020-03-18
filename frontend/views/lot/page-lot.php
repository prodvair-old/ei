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
use frontend\components\Darwin; 
use frontend\components\ServiceLotFormWidget;

use frontend\models\UserAccess;
use frontend\models\ViewPage;

use common\models\Query\WishList;
use common\models\Query\Lot\Lots;

$start = microtime(true);

$view = new ViewPage();

$view->page_type = "lot_".$lot->torg->type;
$view->page_id = $lot->id;

$view->check();

$now = time();
$endDate = strtotime($lot->torg->endDate);

$dateSend = floor(($endDate - $now) / (60 * 60 * 24));

// switch ($lot->torg->type) {
//     case 'bankrupt':
//         $otherJoin = ['torg.bankrupt'];
//         $otherWhere = ['bankrupt.id'=>$lot->torg->bankrupt->id];
//         break;
//     case 'arrest':
//         $otherJoin = ['torg'];
//         $otherWhere = ['torg.id'=>$lot->torg->id];
//         break;
//     case 'zalog':
//         $otherJoin = ['torg.owner'];
//         $otherWhere = ['owner.id'=>$lot->torg->owner->id];
//         break;
// }

// $otherLots = Lots::find()->joinWith($otherJoin)->alias('lot')->where($otherWhere)->andWhere(['!=', 'lot.id', $lot->id])->all();
$otherLots = null;

echo '<center><span style="font-size:12px;">', date('s', microtime(true) - $start), ' сек.</span></center>';
die();

$this->registerJsVar( 'lotType', $lot->torg->type, $position = yii\web\View::POS_HEAD );
$this->title = $lot->title;
$this->params['breadcrumbs'] = Yii::$app->params['breadcrumbs'];

$isCategory = 
    $lot->category->categoryId == '1061' ||
    $lot->category->categoryId == '1063' ||
    $lot->category->categoryId == '1064' ||
    $lot->category->categoryId == '1068' ||
    $lot->category->categoryId == '1083' ||
    $lot->category->categoryId == '1102' ||
    $lot->category->categoryId == '1102';

foreach ($lot->info as $key => $value) { 
    if (
            $value != null && 
            $key != 'address' && 
            $key != 'vin' && 
            $key != 'cadastreNumber' && 
            $key != 'priceReduction' && 
            $key != 'isBurdened' && 
            $key != 'sellType' &&
            $key != 'sellTypeId' &&
            $key != 'minPrice' &&
            // $key != 'torgReason' &&
            // $key != 'stepCount' &&
            // $key != 'dateAuction' &&
            // $key != 'procedureDate' &&
            // $key != 'conclusionDate' &&
            $key != 'currency'
        ) {
        $otherInfo[$key] = $value;
    } 
}
?>



<section class="page-wrapper page-detail">
        
    <div class="page-title bg-light d-none d-sm-block">
    
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
    <!-- <div class="fullwidth-horizon- none--hide">
    
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
                        <?=($lot->priceHistorys == 1)? '<li><a href="#price-history">Этапы снижения цены</a></li>': ''?>
                        <li>
                            <a href="#docs">Документы</a>
                        </li>
                        <?=($otherLots[0] != null)? '<li><a href="#other-lot">Другие лоты</a></li>': ''?>
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
    </div> -->
    
    <div class="container pt-30">
       
        <?=(!empty($lot->archive))? ($lot->archive)? '<span class="h3 text-primary">Архив</span><hr>' : '' : ''?>                 

        <div class="row gap-20 gap-lg-40" itemscope itemtype="http://schema.org/Product">
            
            <div class="col-12 col-lg-8">
                
                <div class="content-wrapper">
                     
                    <div id="desc" class="detail-header mb-30">
                      <h1 class="h4" itemprop="name"><?=Yii::$app->params['h1']?></h1>
              
                        <div class="d-flex flex-row align-items-sm-center mb-20">
                            <div class="mr-10 font-lg text-muted">
                              <?=$lot->viewsCount?> <i class="far fa-eye fa-sm"></i>
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
                                <a <?=(Yii::$app->user->isGuest)? 'href="#loginFormTabInModal-login" class="wish-star" data-toggle="modal" data-target="#loginFormTabInModal" data-backdrop="static" data-keyboard="false"' : 'href="#" class="wish-js wish-star" data-id="'.$lot->id.'" data-type="'.$lot->torg->type.'"'?>>
                                    <img src="img/star<?=($lot->getWishId(Yii::$app->user->id))? '' : '-o' ?>.svg" alt="">
                                </a>
                            </div>
                            <? 
                                if (!Yii::$app->user->isGuest) {
                                    if (Yii::$app->user->identity->role !== 'user' && UserAccess::forManager('lots', 'edit')) {
                            ?>
                                <div class="mr-10 text-muted">|</div>
                                <div class="mr-10 rating-item rating-inline">
                                    <a href="<?= Yii::$app->params['backLink'].'/login?token='.Yii::$app->user->identity->auth_key.'&link[to]=lots&link[page]=update&link[id]='.$lot->id ?>" target="_blank">
                                        Редактировать
                                    </a>
                                </div>
                            <? } }?>
                            
                        </div>
                        
                        <?if ($lot->images[0]) { ?>
                            <div class="fotorama mt-20 mb-40" data-allowfullscreen="true" data-nav="thumbs" data-arrows="always" data-click="true">
                                <? foreach ($lot->images as $image) { ?>
                                    <img href="<?=$image['max']?>" alt="Images" />
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
                        <!-- <pre>
                            <? // print_r($lot[info][address][region])?>,
                            <? // print_r($lot[info][address][city])?>, 
                            <? // print_r($lot[info][address][district])?>, 
                            <? // print_r($lot[info][address][street])?>,
                            <? // print_r($lot[info])?>
                        </pre> -->
                        

                        <p class="long-text" itemprop="description"><?=$lot->description?></p>
                        <a href="#desc" class="open-text-js">Подробнее</a>
                        
                    </div>

                    <? if($lot[info][address][geo_lat] && $isCategory): ?>
                        <div 
                            id="map-lot" 
                            data-lat="<?=$lot[info][address][geo_lat];?>"
                            data-lng="<?=$lot[info][address][geo_lon];?>">
                        </div>
                    <? endif; ?>


                    <div class="sidebar-mobile mb-40">
                        <?= LotDetailSidebar::widget(['lot' => $lot, 'type' => $type]) ?>
                      </div>
                    
                    <div id="info" class="fullwidth-horizon--section">
                    
                        <h4 class="heading-title">Информация о лоте</h4>
                        
                        <ul class="list-icon-absolute what-included-list mb-30">

                            <li>
                                <span class="icon-font"><i class="elegent-icon-check_alt2 text-primary"></i> </span> 
                                <h6>Категории лота</h6>
                                <ul class="ul">
                                    <?foreach ($lot->categorys as $category) { ?>
                                        <li itemprop="category"><?=$category->name?></li>
                                    <? }?>
                                </ul>
                            </li>
                            
                            <? if ($lot->info['vin']) { ?>
                                <li>
                                    <span class="icon-font"><i class="elegent-icon-check_alt2 text-primary"></i> </span> 
                                    <h6>VIN номер</h6>
                                    <p itemprop="mpn"><?= $lot->info['vin'] ?></p>
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

                            <? if ($lot->torg->bankrupt !== null) { ?>
                            <li>
                                <span class="icon-font"><i class="elegent-icon-check_alt2 text-primary"></i> </span> 
                                <h6>Должник</h6>
                                <ul class="ul">
                                    <li><a href="<?=Url::to(['doljnik/list'])?>/<?=$lot->torg->bankrupt->oldId?>" target="_blank" itemprop="brand"><?=$lot->torg->bankrupt->name?></a></li>
                                    <li>ИНН: <span class="text-list-name"><?= $lot->torg->bankrupt->inn?></span></li>
                                    <li>Адрес: <span class="text-list-name"><?= $lot->torg->bankrupt->address;?></span></li>
                                </ul>
                            </li>
                            <? } ?>
                            
                            <? if ($lot->torg->case !== null) { ?>
                            <li>
                                <span class="icon-font"><i class="elegent-icon-check_alt2 text-primary"></i> </span> 
                                <h6>Сведения о деле</h6>
                                <ul class="ul">
                                    <li>Номер дела: <span class="text-list-name"><?= $lot->torg->case->number ?></span></li>
                                    <li>Арбитражный суд: <span class="text-list-name"><a href="<?=Url::to(['sro/list'])?>/<?=$lot->torg->publisher->sro->id?>" target="_blank"><?= $lot->torg->publisher->sro->title?></a></span></li>
                                    <li>Адрес суда: <span class="text-list-name"><?= $lot->torg->publisher->sro->address?></span></li>
                                </ul>
                            </li>
                            <? } ?>
                            
                            <? if ($lot->torg->publisher !== null && $lot->torg->typeId == 1) { ?>
                            <li>
                                <span class="icon-font"><i class="elegent-icon-check_alt2 text-primary"></i> </span> 
                                <h6>Арбитражный управляющий</h6>
                                <ul class="ul">
                                    <li><a href="<?=Url::to(['arbitr/list'])?>/<?=$lot->torg->publisher->oldId?>" target="_blank"><?=$lot->torg->publisher->fullName?></a></li>
                                    <li>Рег. номер: <span class="text-list-name"><?= $lot->torg->publisher->regnum?></span></li>
                                    <li>ИНН: <span class="text-list-name"><?= $lot->torg->publisher->inn?></span></li>
                                    <!-- <li>ОГРН: <span class="text-list-name"><?= $lot->torg->publisher->info['ogrn']?></span></li> -->
                                </ul>
                            </li>
                            <? } ?>

                            <? if ($lot->bank !== null) { ?>
                            <li>
                                <span class="icon-font"><i class="elegent-icon-check_alt2 text-primary"></i> </span>
                                <h6>Банк</h6>
                                <ul class="ul">
                                <li><?= $lot->bank->name ?></li>
                                <li>БИК: <span class="text-list-name"><?= $lot->bank->bik ?></span></li>
                                <!-- <li>Кор. счет: <span class="text-list-name"><? $lot ?></span></li> -->
                                <li>Расчетный счет: <span class="text-list-name"><?= $lot->bank->payment ?></span></li>
                                <li>Лицевой счет: <span class="text-list-name"><?= $lot->bank->personal ?></span></li>
                                </ul>
                            </li>
                            <? } ?>
                            <? if ($lot->torg->owner !== null) { ?>
                            <li>
                                <span class="icon-font"><i class="elegent-icon-check_alt2 text-primary"></i> </span>
                                <h6>Банк</h6>
                                <ul class="ul">
                                <li><a href="/<?=$lot->torg->owner->linkEi?>"><?= $lot->torg->owner->title ?></a></li>
                                <li>E-mail: <span class="text-list-name"><?= $lot->torg->owner->email ?></span></li>
                                <!-- <li>Кор. счет: <span class="text-list-name"><? $lot ?></span></li> -->
                                <li>ИНН: <span class="text-list-name"><?= $lot->torg->owner->inn ?></span></li>
                                <li>Адрес: <span class="text-list-name"><?= $lot->torg->owner->city.', '.$lot->torg->owner->address ?></span></li>
                                </ul>
                            </li>
                            <? } ?>

                            <? if ($lot->torg->publisher !== null && $lot->torg->typeId == 2) { ?>
                            <li>
                                <span class="icon-font"><i class="elegent-icon-check_alt2 text-primary"></i> </span>
                                <h6>Организатора торгов</h6>
                                <ul class="ul">
                                <li><?= $lot->torg->publisher->fullName ?></li>
                                <li>Вышестоящая организация: <span class="text-list-name"><?= $lot->torg->publisher->info['headOrganization'] ?></span></li>
                                <li>Личный номер: <span class="text-list-name"><?= $lot->torg->publisher->arbId ?></span></li>
                                <li>Порог крупной сделки: <span class="text-list-name"><?= $lot->torg->publisher->info['limitBidDeal'] ?></span></li>
                                <li>ИНН: <span class="text-list-name"><?= $lot->torg->publisher->inn ?></span></li>
                                <li>КПП: <span class="text-list-name"><?= $lot->torg->publisher->info['kpp'] ?></span></li>
                                <li>ОКАТО: <span class="text-list-name"><?= $lot->torg->publisher->info['okato'] ?></span></li>
                                <li>ОКПО: <span class="text-list-name"><?= $lot->torg->publisher->info['okpo'] ?></span></li>
                                <li>ОКВЕД: <span class="text-list-name"><?= $lot->torg->publisher->info['okved'] ?></span></li>
                                <li>ОГРН: <span class="text-list-name"><?= $lot->torg->publisher->info['ogrn'] ?></span></li>
                                <li>Факс: <span class="text-list-name"><?= $lot->torg->publisher->info['contacts']['fax'] ?></span></li>
                                <li>E-mail: <span class="text-list-name"><?= $lot->torg->publisher->info['contacts']['email'] ?></span></li>
                                <li>Номер телефона: <span class="text-list-name"><?= $lot->torg->publisher->info['contacts']['phone'] ?></span></li>
                                <li>Почтовый адрес: <span class="text-list-name"><?= $lot->torg->publisher->info['contacts']['address'] ?></span></li>
                                <li>Фактический адрес: <span class="text-list-name"><?= $lot->torg->publisher->info['contacts']['location'] ?></span></li>
                                </ul>
                            </li>
                            <? } ?>

                            <? if ($otherInfo) { ?>
                            <li>
                            <span class="icon-font"><i class="elegent-icon-check_alt2 text-primary"></i> </span> 
                                <h6>Дополнительные данные</h6>
                                <ul class="ul">
                                    <? foreach ($otherInfo as $key => $value) { ?>
                                        <?
                                            switch ($key) {
                                                case 'viewInfo':
                                                    $title = 'Дата, время и порядок осмотра лота';
                                                    break;
                                                case 'basisBidding':
                                                    $title = 'Тип сделки';
                                                    break;
                                                case 'paymentDetails':
                                                    $title = 'Описание лота';
                                                    break;
                                                case 'additionalConditions':
                                                    $title = 'Дополнительные условия и критерии определения победителя';
                                                    break;
                                                case 'flatName':
                                                    $title = 'Квартира';
                                                    break;
                                                case 'flatFloor':
                                                    $title = 'Этаж';
                                                    break;
                                                case 'depositDesc':
                                                    $title = 'Описание депозита';
                                                    break;
                                                case 'burdenDesc':
                                                    $title = 'Классификация';
                                                    break;
                                                case 'contractDesc':
                                                    $title = 'Контракт';
                                                    break;
                                                case 'contractTerm':
                                                    $title = 'Срок действия контракта';
                                                    break;
                                                default:
                                                    $title = $key;
                                                    break;
                                            }
                                        ?>
                                        <?if (strlen($value) < 100) {?>
                                            <li><?=$title?>: <span class="text-list-name"><?= $value ?></span></li>
                                        <? } else { ?>
                                            <li>
                                                <h6><?=$title?></h6>
                                                <p><?=$value?></p>
                                            </li>
                                        <? } ?>
                                    <? } ?>
                                </ul>
                            </li>
                            <? } ?>
                            
                        </ul>
                        
                        <div class="mb-50"></div>
                        
                    </div>
                    
                    
                    <?php if($lot->torg->tradeTypeId == 1) { ?>

                        <div id="price-history" class="fullwidth-horizon-sticky-section">

                            <h5 class="heading-title">Этапы снижения цены</h5>
                            
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
                                    if ($lot->priceHistorys != null) { 
                                        foreach ($lot->getPriceHistorys()->orderBy('price DESC')->all() as $key => $value) { 
                                            $date = Yii::$app->formatter->asDatetime(new \DateTime(), "php:Y-m-d H:i:s");
                                ?>
                                    <div class="item-text-long <?=(($value->intervalBegin <= $date) && $value->intervalEnd >= $date)? '' : 'sold-out' ?>">
                                    
                                        <div class="row align-items-center">
                                        
                                            <div class="col-12 col-sm-6">
                                            
                                                <div class="col-inner mb-10 mb-sm-0">
                                                
                                                    <div class="row gap-10 align-items-center">
                                                    
                                                        <div class="col-6">
                                                            <span class="font-sm">Начало</span>
                                                            <strong class="d-block"><?=Yii::$app->formatter->asDate($value->intervalBegin, 'long')?></strong>
                                                        </div>
                                                        
                                                        <!-- <div class="col-2">
                                                            <span class="day-count mt-3">3<br/>days</span>
                                                        </div> -->
                                                        
                                                        <div class="col-6 text-right text-sm-left">
                                                            <span class="font-sm">Конец</span>
                                                            <strong class="d-block"><?=Yii::$app->formatter->asDate($value->intervalEnd, 'long')?></strong>
                                                        </div>
                                                        
                                                    </div>
                                                
                                                </div>
                                            
                                            </div>
                                            
                                            <div class="col-12 col-sm-6">
                                            
                                                <div class="col-inner">
                                                
                                                    <div class="row gap-10 align-items-center">
                                                    
                                                        <div class="col-6 text-left text-sm-center">
                                                            <span class="font-sm">Цена </span>
                                                            <strong class="d-block"><?=Yii::$app->formatter->asCurrency($value->price)?></strong>
                                                        </div>
                                                        
                                                        <div class="col-6 text-left  text-sm-right">
                                                            <span class="font-sm">Задаток</span>
                                                            <strong class="d-block"><?=($lot->depositTypeId == 1)? Yii::$app->formatter->asCurrency((($value->price / 100) * $lot->deposit)) : Yii::$app->formatter->asCurrency($lot->deposit)?></strong>
                                                        </div>
                                                        
                                                    </div>
                                                    
                                                </div>
                                                
                                            </div>
                                            

                                            
                                            <!-- <div class="col-4 col-sm-2">
                                                <a href="#" class="btn btn-primary btn-block btn-sm mt-3">Купить</a>
                                            </div> -->
                                            
                                        </div>
                                    
                                    </div>
                                <? } }  else { ?>
                                    <div class="item-text-long <?=(($key == 0 || $value->intervalBegin <= $date) && $value->intervalEnd >= $date)? '' : 'sold-out' ?>">
                                    
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

                    <?= Darwin::widget()?>

                    <? if ($lot->torg->case) {?>
                    <? if ($lot->torg->case->documents) { ?>

                    <div id="docs" class="fullwidth-horizon--section">
                        <h5 class="heading-title">Документы по делу должника</h5>
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

                    <? } ?>
                    <? } ?>

                    <? if ($lot->torg->documents) {?>
                    <div id="docs" class="fullwidth-horizon--section">
                        <h5 class="heading-title">Документы по торгу</h5>
                        
                        <ul class="list-icon-absolute what-included-list mb-30 long-text">
                        <? foreach ($lot->torg->documents as $document) { ?>

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
                    <? } ?>

                    <? if ($lot->documents) {?>
                    <div id="docs" class="fullwidth-horizon--section">
                        <h5 class="heading-title">Документы по лоту</h5>
                        
                        <ul class="list-icon-absolute what-included-list mb-30 long-text">
                        <? foreach ($lot->documents as $document) { ?>

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
                    <? } ?>

                    <? if ($lots_bankrupt[0] != null) { ?>
                    <div id="other-lot" class="fullwidth-horizon--section">

                        <h5 class="heading-title">Другие лоты должника</h5>
                        
                        <div class="row equal-height cols-1 cols-sm-2 gap-30 mb-25">
            
                            <?foreach ($otherLots as $otherLot) { echo LotBlock::widget(['lot' => $otherLot]); }?>
                        
                        </div>
                        
                        <div class="mb-50"></div>
                        
                    </div>
                    <? } ?>
                    

                    <div id="torg" class="detail-header mb-30">
                        <h5 class="mt-30">Информация о торге</h5>
                        <p class="long-text"><?=$lot->torg->description?></p>
                        <a href="#torg" class="open-text-js">Подробнее</a>
                        <div class="mb-50"></div>
                    </div>

                    <div id="roles" class="detail-header mb-30">
                        <h5 class="mt-30">Правила подачи заявок</h5>
                        <p class="long-text"><?=($lot->torg->typeId == 1)? $lot->torg->info['rules'] : $lot->info['torgReason'] ?></p>
                        <a href="#roles" class="open-text-js">Подробнее</a>
                    </div>

                </div>
                
            </div>
            
            <div class="col-12 col-lg-4">

                <div class="sidebar-desktop">
                    <?= \ymaker\social\share\widgets\SocialShare::widget([
                        'configurator'  => 'socialShare',
                        'url'           => ('https://ei.ru' . Yii::$app->request->url),
                        'title'         => 'Посмотри лот на ei.ru: ' .Yii::$app->params['h1'],
                        // 'description'   => $lot->description,
                        'imageUrl'      => Url::to($lot->images[0]['max'], true),
                    ]); ?>    
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
$this->registerJsFile( 'js/custom-multiply-.js', $options = ['position' => yii\web\View::POS_END], $key = 'custom-multiply-' );
$this->registerJsFile( 'js/custom-core.js', $options = ['position' => yii\web\View::POS_END], $key = 'custom-core' );?>