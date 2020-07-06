<?php
use yii\widgets\Breadcrumbs;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;

use common\models\Query\Lot\Lots;

use frontend\components\LotBlock;
use frontend\components\ProfileMenu;

$name = Yii::$app->user->identity->getFullName();

$this->title = "Сохранённые поиски – $name";
$this->params['breadcrumbs'][] = [
    'label' => 'Профиль',
    'template' => '<li class="breadcrumb-item active" aria-current="page">{link}</li>',
    'url' => ['user/index']
];
$this->params['breadcrumbs'][] = [
    'label' => 'Сохранённые поиски',
    'template' => '<li class="breadcrumb-item active" aria-current="page">{link}</li>',
    'url' => ['user/wish_list']
];
$this->registerJsVar( 'lotType', '', $position = yii\web\View::POS_HEAD );
?>

<section class="page-wrapper page-detail">
			
    <div class="page-title border-bottom pt-25 mb-0 border-bottom-0">
    
        <div class="container">
        
            <div class="row gap-15 align-items-center">
            
                <div class="col-12 col-md-7">
                    
                    <nav aria-label="breadcrumb">
                        <?= Breadcrumbs::widget([
                            'itemTemplate' => '<li class="breadcrumb-item">{link}</li>',
                            'encodeLabels' => false,
                            'tag' => 'ol',
                            'activeItemTemplate' => '<li class="breadcrumb-item active" aria-current="page">{link}</li>',
                            'homeLink' => ['label' => '<i class="fas fa-home"></i>', 'url' => '/'],
                            'links' => $this->params['breadcrumbs'],
                        ]) ?>
                    </nav>
                    
                </div>
                
            </div>
    
        </div>
        
    </div>
    
    <div class="container pt-30">

        <div class="row gap-20 gap-lg-40">
            
            
            <div class="col-12 col-lg-3">
                
                <aside class="-kit sidebar-wrapper profile-sidebar">

                    <div class="bashboard-nav-box">
                    
                        <div class="box-heading"><h3 class="h6 text-white text-uppercase">Профиль:</h3></div>
                        <div class="box-content">
                        
                            <div class="dashboard-avatar mb-10">
                        
                                <div class="image">
                                <img class="setting-image-tag" src="<?=(Yii::$app->user->identity->avatar)? Yii::$app->user->identity->avatar: 'img/image-man/01.jpg'?>" alt="Image" />
                                </div>
                                
                                <div class="content">
                                    <h6><?=$name?></h6>
                                    <p class="mb-15"><?= Yii::$app->user->identity->getFullName() ?></p>
                                </div>
                                
                            </div>
                            
                            <?=ProfileMenu::widget(['page'=>'search-preset'])?>
                            
                            <!-- <p class="font-sm mt-20">Your last logged-in: <span class="text-primary font700">4 hours ago</span></p> -->

                        </div>
                        
                    </div>
                
                </aside>
                
            </div>
            
            <div class="col-12 col-lg-9">
                
                <div class="content-wrapper">
                    
                    <div class="form-draft-payment">
                    
                        <h3 class="heading-title"><span>Мои <span class="font200"> поисковые отслеживания</span></span></h3>
                        
                        <div class="clear"></div>

                        <div class="mb-30"></div>
                        <div class="row search-preset-list" id="">
                            <?php if ($searchQueries[0]) { ?>
                                <div class="offset-md-8 col-md-4 d-none d-md-block search-preset-sender">
                                    Получать письма
                                    <div class="mb-15"></div>
                                </div>
                                <? foreach ($searchQueries as $searchQuerу) { ?>
                                    <div class="search-preset-box search-preset-box-<?=$searchQuerу->id?> col-12">
                                    <?=$searchQuerу->last_count ? '<span class="search-preset-box__count">'.$searchQuerу->last_count.'</span>' : ''?>
                                    <div class="search-preset-box__left">
                                    <a href="<?=$searchQuerу->url?>" class="search-preset-box__title">
                                        <h5><?=$searchQuerу->defs?></h5>
                                    </a>
                                    <p class="search-preset-box__desc text-muted"><?=$searchQuerу->descripton?></p>
                                    <span class="text-muted">Последний поиск: <?=Yii::$app->formatter->asDate($searchQuerу->seached_at, 'dd.MM.yyyy')?></span>
                                    </div>
                                    <div class="search-preset-box__right">
                                        <div class="search-preset-box__check">
                                        <span class="d-md-none">Получать письма</span>
                                        <div class="toggle normal">
                                            
                                            <input id="normal-<?=$searchQuerу->id?>" class="normal-input" data-id="<?=$searchQuerу->id?>" type="checkbox" <?=$searchQuerу->send_email ? 'checked' : ''?>/>
                                            <label class="toggle-item" for="normal-<?=$searchQuerу->id?>"></label>
                                        </div>
                                        </div>
                                        <a href="#" class="search-preset-box__del search-preset-box__del-js" data-id="<?=$searchQuerу->id?>"><i class="far fa-trash-alt"></i></a>
                                    </div>
                                    </div>
                                <? } 
                            } ?>
                            <div class="p-15 search-preset__info <?=$searchQueries[0] ? '' : 'active'?>">
                                <h3>Как отслеживать поиск?</h3>
                                <p>На странице <a href="<?=Url::to(['all/lot-list'])?>" class="text-dark"><u>поисковой выдачи</u></a> после фильтрации нажмите <span class="text-primary">"Отслеживать поиск"</span>.</p>
                            </div>

                            <div class="pager-innner">
                        
                                <div class="row align-items-center text-center text-lg-left">
                                
                                    <div class="col-12 col-lg-5">
                                    </div>
                                    
                                    <div class="col-12 col-lg-7">
                                        
                                        <nav class="float-lg-right mt-10 mt-lg-0">
                                            <?= LinkPager::widget([
                                                'pagination' => $pages,
                                                'nextPageLabel' => "<span aria-hidden=\"true\">Далее</span></i>",
                                                'prevPageLabel' => "<span aria-hidden=\"true\">Назад</span>",
                                                'maxButtonCount' => 6,
                                                'options' => ['class' => 'pagination justify-content-center justify-content-lg-left'],
                                                'disabledPageCssClass' => false
                                            ]); ?>
                                        </nav>
                                    </div>
                                    
                                </div>
                            
                            </div>
                        </div>

                    </div>
                    
                </div>
                
            </div>
            
        </div>
        
    </div>

</section>
