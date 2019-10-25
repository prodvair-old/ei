<?php

/* @var $this yii\web\View */

use yii\widgets\Breadcrumbs;
use yii\widgets\LinkPager;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

use frontend\components\LotBlock;

use common\models\Query\Settings;
use common\models\Query\LotsCategory;
use common\models\Query\Regions;

use common\models\Query\Bankrupt\TradePlace;

$this->title = Yii::$app->params['title'];
$this->params['breadcrumbs'][] = [
    'label' => ' Имущество должников',
    'template' => '<li class="breadcrumb-item active" aria-current="page">{link}</li>',
    'url' => ["/$type"]
];

$lotsCategory = LotsCategory::find()->orderBy('id ASC')->all();
// switch ($type) {
//     case 'bankrupt':
//             foreach ($lotsCategory->bankrupt_categorys as $key => $value) {
//                 $lotsSubcategory[] = [$key => $value['name']];
//             }
//         break;
//     case 'bankrupt':
//             foreach ($lotsCategory->arrest_categorys as $key => $value) {
//                 $lotsSubcategory[] = [$key => $value['name']];
//             }
//         break;
//     default:
//         # code...
//         break;
// }
?>

<section class="page-wrapper page-result pb-0">
			
    <div class="page-title bg-light mb-0">
    
        <div class="container">
        
            <div class="row gap-15 align-items-center">
            
                <div class="col-12 col-md-7">
                    
                    <nav aria-label="breadcrumb">
                        <!-- <ol class="breadcrumb"> -->
                            <?= Breadcrumbs::widget([
                                'itemTemplate' => '<li class="breadcrumb-item">{link}</li>',
                                'encodeLabels' => false,
                                'tag' => 'ol',
                                'activeItemTemplate' => '<li class="breadcrumb-item active" aria-current="page">{link}</li>',
                                'homeLink' => ['label' => '<i class="fas fa-home"></i>', 'url' => '/'],
                                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                            ]) ?>
                        <!-- </ol> -->
                    </nav>
                    
                    <h4 class="mt-0 line-125"><?=Yii::$app->params['h1']?></h4>
                    
                </div>
                
            </div>
    
        </div>
        
    </div>
    
    <div class="container">
        <div class="row equal-height gap-30 gap-lg-40">
            
            <div class="col-12 col-lg-4">

                <?php $form = ActiveForm::begin(['id' => 'search-lot-form']); ?>

                <aside class="sidebar-wrapper pv">
                
                    <div class="secondary-search-box mb-30">
                    
                        <h4 class="">Поиск</h4>
                        
                        <div class="row">
                        
                            <div class="col-12">
                                <div class="col-inner">
                                    <?=$form->field($model, 'type')->dropDownList([
                                            'bankrupt' => 'Банкротное имущество',
                                            'arrest' => 'Арестованное имущество',
                                        ], [
                                            'class'=>'chosen-the-basic form-control form-control-sm', 
                                            'data-placeholder'=>'Выберите тип лота', 
                                            'tabindex'=>'2',
                                            'options' => [
                                                $type => ['Selected' => true]
                                            ]])
                                        ->label('Тип лота');?>
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <div class="col-inner">
                                    <?=$form->field($model, 'category')->dropDownList(
                                            ArrayHelper::map($lotsCategory, 'id', 'name'),
                                        [
                                            'class'=>'chosen-the-basic form-control form-control-sm', 
                                            'data-placeholder'=>'Все категории', 
                                            'tabindex'=>'2',
                                            'options' => [
                                                $category => ['Selected' => true]
                                            ]])
                                        ->label('Категория');?>
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <div class="col-inner">
                                    <?=$form->field($model, 'region')->dropDownList(
                                            ArrayHelper::map(Regions::find()->orderBy('id ASC')->all(), 'id', 'name'),
                                        [
                                            'class'=>'chosen-the-basic form-control form-control-sm', 
                                            'data-placeholder'=>'Все регионы', 
                                            'tabindex'=>'2',
                                            'multiple' => true
                                        ])
                                        ->label('Регион');?>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="col-inner">
                                    <?=$form->field($model, 'search')->textInput([
                                            'class'=>'form-control form-control-sm', 
                                            'placeholder'=>'Например: Машина, Квартира...',
                                            'tabindex'=>'2',
                                        ])
                                        ->label('Найти');?>
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <div class="col-inner ph-20 pv-15">
                                    <?= Html::submitButton('<i class="ion-android-search"></i> Поиск', ['class' => 'btn btn-primary btn-block', 'name' => 'login-button']) ?>
                                </div>
                            </div>
                        
                        </div>
                        
                    </div>
                    
                    <div class="sidebar-box">
                    
                        <div class="box-title"><h5>Цена</h5></div>
                        
                        <div class="box-content">
                            <?=$form->field($model, 'minPrice')->hiddenInput(['class'=>'lot__price-min'])->label(false);?>
                            <?=$form->field($model, 'maxPrice')->hiddenInput(['class'=>'lot__price-max'])->label(false);?>
                            <input id="price_range" data-min="<?=$price['min']?>"  data-max="<?=$price['max']?>"/>
                        </div>
                        
                    </div>
                    
                    <div class="sidebar-box">
                    
                        <div class="box-title"><h5>Тип торгов</h5></div>
                        
                        <div class="box-content">
                        
                            <?=$form->field($model, 'tradeType')
                                ->checkboxList([
                                    'OpenedAuction' => 'Открытый аукцион',
                                    'PublicOffer' => 'Публичное предложение',
                                ],[
                                    'item' =>
                                    function ($index, $label, $name, $checked, $value) {
                                        $html = Html::checkbox($name, $checked, [
                                            'value' => $value,
                                            'class'=>'custom-control-input',
                                            'id' => $value,
                                        ],false);
                                        return '<div class="custom-control custom-checkbox">'.$html.'<label for="'.$value.'" class="custom-control-label">'.$label.'</label></div>';
                                    },
                                ])->label(false);?>
                            
                        </div>
                        
                    </div>

                    <div class="sidebar-box">
                    
                        <div class="box-title"><h5>Торговые площадки</h5></div>
                        
                        <div class="box-content">
                            <?=$form->field($model, 'etp')->dropDownList(
                                    ArrayHelper::map(TradePlace::find()->orderBy('tradename ASC')->all(), 'idtradeplace', 'tradename'), 
                                [
                                    'class'=>'chosen-the-basic form-control', 
                                    'data-placeholder'=>'Все торговые площадки', 
                                    'multiple' => true
                                ])
                                ->label(false);?>
                        </div>
                        
                    </div>
                    
                    <div class="sidebar-box">

                        <div class="box-title"><h5>Другое</h5></div>
                    
                        <div class="box-content">
                        
                            <div class="custom-control custom-checkbox">
                                <?= $form->field($model, 'imageCheck')->checkbox([
                                        'class'=>'custom-control-input',
                                        'value' => '1',
                                        'id' => 'imageCheck',
                                        'template' => '{input}<label class="custom-control-label" for="imageCheck">Только с фото</label>']) ?>
                            </div>
                            
                        </div>
                        
                    </div>
                    
                    <div class="sidebar-box">
                    
                    <?= Html::submitButton('фильтровать', ['class' => 'btn btn-primary btn-block', 'name' => 'login-button']) ?>
                        
                    </div>

                </aside>

                <?php ActiveForm::end(); ?>

            </div>
            
            <div class="col-12 col-lg-8">
                
                <div class="content-wrapper pv">
                
                    <div class="d-flex justify-content-between flex-row align-items-center sort-group page-result-01">
                        <div class="sort-box">
                            <div class="d-flex align-items-center sort-item">
                                
                                <label class="sort-label d-none d-sm-flex">Сортировка по:</label>
                                <?php $form = ActiveForm::begin(['id' => 'sort-lot-form']); ?>
                                <div class="sort-form">
                                    <?=$form->field($modelSort, 'sortBy')->dropDownList([
                                            'nameASC'   =>'Название от А до Я',
                                            'nameDESC'  =>'Название от Я до А',
                                            'dateDESC'  =>'Сначала новые',
                                            'dateASC'   =>'Сначала старые',
                                            'priceDESC' =>'Цена по убыванию',
                                            'priceASC'  =>'Цена по возрастанию'
                                        ],[
                                            'class'=>'chosen-the-basic form-control', 
                                            'data-placeholder'=>'Сортировка по', 
                                            'tabindex'=>'2',
                                            'options' => [
                                                'dateDESC' => ['Selected' => true]
                                            ]])
                                        ->label(false);?>
                                </div>
                                <?php ActiveForm::end(); ?>
                                
                            </div>
                        </div>
                        <div class="sort-box">
                            <div class="d-flex align-items-center sort-item">
                                <label class="sort-label d-none d-sm-flex">View as:</label>
                                <ul class="sort-nav">
                                    <li><a href="#"><i class="fas fa-th"></i></a></li>
                                    <li><a href="#" class="active"><i class="fas fa-th-list"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div class="tour-long-item-wrapper-01">
                        <?foreach ($lots as $lot) { echo LotBlock::widget(['lot' => $lot, 'type' => 'long']); }?>
                    </div>
                    
                    <div class="pager-wrappper mt-40">

                        <div class="pager-innner">
                        
                            <div class="row align-items-center text-center text-lg-left">
                            
                                <div class="col-12 col-lg-5">
                                    Выведено от <?=$offset+1?> до <?=$limit?> лотов. Всего <?=$count?>.
                                </div>
                                
                                <div class="col-12 col-lg-7">
                                    
                                    <nav class="float-lg-right mt-10 mt-lg-0">
                                        <?= LinkPager::widget([
                                            'pagination' => $pages,
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

</section>8