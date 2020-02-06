<?
use yii\widgets\Breadcrumbs;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;

use common\models\Query\Lot\Lots;

use frontend\components\LotBlock;
use frontend\components\ProfileMenu;

foreach ($wishArrestList as $wishArrest) {
    $lotArrestIds[] = Lots::findOne($wishArrest->lotId);
}

foreach ($wishBankruptList as $wishBankrupt) {
    $lotBankruptIds[] = Lots::findOne($wishBankrupt->lotId);
}

foreach ($wishZalogList as $wishZalog) {
    $lotZalogIds[] = Lots::findOne($wishZalog->lotId)->one();
}

$name = (\Yii::$app->user->identity->info['firstname'] || \Yii::$app->user->identity->info['lastname'])? \Yii::$app->user->identity->info['firstname'].' '.\Yii::$app->user->identity->info['lastname'] : \Yii::$app->user->identity->info['contacts']['emails'][0];

$this->title = "Избранные – $name";
$this->params['breadcrumbs'][] = [
    'label' => 'Профиль',
    'template' => '<li class="breadcrumb-item active" aria-current="page">{link}</li>',
    'url' => ['user/index']
];
$this->params['breadcrumbs'][] = [
    'label' => 'Избранные',
    'template' => '<li class="breadcrumb-item active" aria-current="page">{link}</li>',
    'url' => ['user/wish_list']
];
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
                            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
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
                                    <p class="mb-15"><?=(\Yii::$app->user->identity->info['firstname'] || \Yii::$app->user->identity->info['lastname'])? \Yii::$app->user->identity->info['contacts']['emails'][0]: ''?></p>
                                </div>
                                
                            </div>
                            
                            <?=ProfileMenu::widget(['page'=>'wishlist'])?>
                            
                            <!-- <p class="font-sm mt-20">Your last logged-in: <span class="text-primary font700">4 hours ago</span></p> -->

                        </div>
                        
                    </div>
                
                </aside>
                
            </div>
            
            <div class="col-12 col-lg-9">
                
                <div class="content-wrapper">
                    
                    <div class="form-draft-payment">
                    
                        <h3 class="heading-title"><span>Мои <span class="font200"> Избранные</span></span></h3>
                        
                        <div class="clear"></div>

                        <div class="wish__nav">
                            <ul class="row">
                                <!-- <? if ($lotArrestIds) { echo '<li class="col-md-6 col-12"><a id="arrest-wish-btn" href="#arrest-wish" class="wish-tabs active">Аррестованное имущество</a></li>'; } ?>
                                <? if ($lotBankruptIds) { echo '<li class="col-md-6 col-12"><a id="bankrupt-wish-btn"href="#bankrupt-wish" class="wish-tabs active">Банкротное имущество</a></li>'; } ?> -->
                                <li class="col-md-4 col-12"><a id="bankrupt-wish-btn"href="#bankrupt-wish" class="wish-tabs">Банкротное имущество</a></li>
                                <li class="col-md-4 col-12"><a id="arrest-wish-btn" href="#arrest-wish" class="wish-tabs">Арестованное имущество</a></li>
                                <li class="col-md-4 col-12"><a id="zalog-wish-btn" href="#zalog-wish" class="wish-tabs">Имущество организаций</a></li>
                            </ul>
                            <hr class="mt-10">
                        </div>

                        <div class="mb-50"></div>




                        <div data-count="<?= ($lotBankruptIds)? count($lotBankruptIds) : 0?>" class="row equal-height cols-1 cols-sm-2 cols-lg-3 gap-20 mb-30 wish-lot-list" id="bankrupt-wish">
                            <? if ($lotBankruptIds) {
                                foreach ($lotBankruptIds as $lot) { echo LotBlock::widget(['lot' => $lot]); }
                            } else {
                                echo "<div class='p-15 font-bold'>Пока нет избранных лотов по банкротным торгам</div>";
                            } ?>
                            <div class="pager-innner">
                        
                                <div class="row align-items-center text-center text-lg-left">
                                
                                    <div class="col-12 col-lg-5">
                                    </div>
                                    
                                    <div class="col-12 col-lg-7">
                                        
                                        <nav class="float-lg-right mt-10 mt-lg-0">
                                            <?= LinkPager::widget([
                                                'pagination' => $pagesBankrupt,
                                                'nextPageLabel' => "<span aria-hidden=\"true\">&raquo;</span></i>",
                                                'prevPageLabel' => "<span aria-hidden=\"true\">&laquo;</span>",
                                                'maxButtonCount' => 6,
                                                'options' => ['class' => 'pagination justify-content-center justify-content-lg-left'],
                                                'disabledPageCssClass' => false
                                            ]); ?>
                                        </nav>
                                    </div>
                                    
                                </div>
                            
                            </div>
                        </div>

                        <div data-count="<?= ($lotArrestIds)? count($lotArrestIds) : 0?>" class="row equal-height cols-1 cols-sm-2 cols-lg-3 gap-20 mb-30 wish-lot-list" id="arrest-wish">
                            <? if ($lotArrestIds) {
                                foreach ($lotArrestIds as $lot) { echo LotBlock::widget(['lot' => $lot]); } 
                            } else {
                                echo "<div class='p-15 font-bold'>Пока нет избранных лотов по арестованному имуществу</div>";
                            } ?>

                            <div class="pager-innner">
                        
                                <div class="row align-items-center text-center text-lg-left">
                                
                                    <div class="col-12 col-lg-5">
                                    </div>
                                    
                                    <div class="col-12 col-lg-7">
                                        
                                        <nav class="float-lg-right mt-10 mt-lg-0">
                                            <?= LinkPager::widget([
                                                'pagination' => $pagesArrest,
                                                'nextPageLabel' => "<span aria-hidden=\"true\">&raquo;</span></i>",
                                                'prevPageLabel' => "<span aria-hidden=\"true\">&laquo;</span>",
                                                'maxButtonCount' => 6,
                                                'options' => ['class' => 'pagination justify-content-center justify-content-lg-left'],
                                                'disabledPageCssClass' => false
                                            ]); ?>
                                        </nav>
                                    </div>
                                    
                                </div>
                            
                            </div>
                        </div>

                        <div data-count="<?= ($lotZalogIds)? count($lotZalogIds) : 0?>" class="row equal-height cols-1 cols-sm-2 cols-lg-3 gap-20 mb-30 wish-lot-list" id="zalog-wish">
                            <? if ($lotZalogIds) {
                                foreach ($lotZalogIds as $lot) { echo LotBlock::widget(['lot' => $lot]); }
                            } else {
                                echo "<div class='p-15 font-bold'>Пока нет избранных лотов по имуществу организаций</div>";
                            } ?>
                            <div class="pager-innner">
                        
                                <div class="row align-items-center text-center text-lg-left">
                                
                                    <div class="col-12 col-lg-5">
                                    </div>
                                    
                                    <div class="col-12 col-lg-7">
                                        
                                        <nav class="float-lg-right mt-10 mt-lg-0">
                                            <?= LinkPager::widget([
                                                'pagination' => $pagesZalog,
                                                'nextPageLabel' => "<span aria-hidden=\"true\">&raquo;</span></i>",
                                                'prevPageLabel' => "<span aria-hidden=\"true\">&laquo;</span>",
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