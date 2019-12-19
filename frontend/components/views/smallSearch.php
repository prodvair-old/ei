<?php

use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

use common\models\Query\LotsCategory;
use common\models\Query\Regions;


$lotsCategory = LotsCategory::find()->where(['or', ['not', [$type.'_categorys' => null]], ['translit_name' => 'lot-list']])->orderBy('id ASC')->all();

$regionList[0] = 'Все регионы';
$regions = Regions::find()->orderBy('id ASC')->all();
foreach ($regions as $region) {
    $regionList[$region->id] = $region->name;
}
$this->registerJsVar('lotType', $type, $position = yii\web\View::POS_HEAD);
$this->registerJsVar('categorySelected', 0, $position = yii\web\View::POS_HEAD);

$btnStyle = ($btnColor)? "background: $btnColor; border-color: $btnColor;" : '' ;
?>
<? if ($color) { ?>
<style>
    .hero-banner-01 .search-form-main .form-group label {
        color: <?=$color?>;
    }
    .hero-banner-01 .search-form-main .form-group .form-control::-webkit-input-placeholder {
        color: <?=$color?>;
    }
    .hero-banner-01 .search-form-main .form-group .form-control::-moz-placeholder {
        color: <?=$color?>;
    }
    .hero-banner-01 .search-form-main .form-group .form-control:-moz-placeholder {
        color: <?=$color?>;
    }
    .hero-banner-01 .search-form-main .form-group .form-control:-ms-input-placeholder {
        color: <?=$color?>;
    }
    .chosen-container-single a:not([href]):not([tabindex]).chosen-single:not(.chosen-default) {
        color: <?=$color?>!important;
    }
    .chosen-container-single a:not([href]):not([tabindex]) {
        color: <?=$color?>!important;
    }
</style>
<? } ?>
<div class="search-form-main">
    <?php $form = ActiveForm::begin(['method' => 'get','action' => '/'.(($typeZalog)? $typeZalog : $type).'/lot-list','options' => ['enctype' => 'multipart/form-data']]) ?>
        <div class="from-inner">
            
            <div class="row shrink-auto-sm gap-1">
            
                <div class="col-12 col-auto">
                    <div class="col-inner">
                        <div class="row cols-1 cols-sm-4 gap-1">
            
                            <div class="col">
                                <div class="col-inner height-100">
                                    <?=$form->field($model, 'type')->dropDownList([
                                            'bankrupt' => 'Банкротное имущество',
                                            'arrest' => 'Арестованное имущество',
                                            'zalog' => 'Залоговое имущество',
                                        ], [
                                            'class'=>'chosen-type-select form-control form-control-sm', 
                                            'data-placeholder'=>'Выберите тип лота', 
                                            'tabindex'=>'2',
                                            'options' => [
                                                $type => ['Selected' => true]
                                            ]])
                                        ->label('Тип лота');?>
                                </div>
                            </div>

                            <div class="col">
                                <div class="col-inner height-100">
                                    <?=$form->field($model, 'category')->dropDownList(
                                            ArrayHelper::map($lotsCategory, 'id', 'name'),
                                        [
                                            'class'=>'chosen-category-select form-control form-control-sm', 
                                            'data-placeholder'=>'Все категории', 
                                            'tabindex'=>'2'
                                        ])
                                        ->label('Категория');?>
                                </div>
                            </div>

                            <div class="col">
                                <div class="col-inner height-100">
                                    <?=$form->field($model, 'search')->textInput([
                                            'class'=>'form-control form-control-sm', 
                                            'placeholder'=>'Например: Машина, Квартира...',
                                            'tabindex'=>'2',
                                        ])
                                        ->label('Поиск');?>
                                </div>
                            </div>

                            <div class="col">
                                <div class="col-inner">
                                    <?=$form->field($model, 'region')->dropDownList(
                                            $regionList,
                                        [
                                            'class'=>'chosen-the-basic form-control form-control-sm', 
                                            'data-placeholder'=>'Все регионы', 
                                            'tabindex'=>'2',
                                            'multiple' => false
                                        ])
                                        ->label('Регион');?>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-12 col-shrink">
                    <div class="col-inner">
                        <?= Html::submitButton('<i class="ion-android-search"></i>', ['class' => 'btn btn-primary btn-block', 'style' => $btnStyle, 'name' => 'login-button']) ?>
                    </div>
                </div>
                
            </div>
        </div>

        

        <?php ActiveForm::end() ?>
    </div>
</div>