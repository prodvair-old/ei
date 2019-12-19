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
use common\models\Query\Zalog\OwnerProperty;

$this->title = Yii::$app->params['title'];
$this->params['breadcrumbs'] = Yii::$app->params['breadcrumbs'];

$lotsSubcategory[0] = 'Все подкатегории';
$lotsCategory[0] = ['id' => 0, 'name' => 'Все категории'];
$subcategoryCheck = true;

$regionList[0] = 'Все регионы';
$regions = Regions::find()->orderBy('id ASC')->all();
foreach ($regions as $region) {
  $regionList[$region->id] = $region->name;
}

$traderList = [];

if ($type == 'bankrupt') {
    $traderLabel = 'Торговые площадки';
    $traderPlaceholder = 'Все торговые площадки';
    $traderList = ArrayHelper::map(TradePlace::find()->orderBy('tradename ASC')->all(), 'idtradeplace', 'tradename');
} else if ($type == 'zalog') {
    $traderLabel = 'Организации';
    $traderPlaceholder = 'Все организации';
    $traderList = ArrayHelper::map(OwnerProperty::find()->orderBy('name ASC')->all(), 'id', 'name');
}

$lotsCategory = LotsCategory::find()->where(['or', ['not', [$type.'_categorys' => null]], ['translit_name' => 'lot-list']])->orderBy('id ASC')->all();

if ($queryCategory != '0') {
    switch ($type) {
        case 'bankrupt':
                $lotsCategory = LotsCategory::find()->where(['not', ['bankrupt_categorys' => null]])->orderBy('id ASC')->all();
                $category = LotsCategory::findOne($queryCategory);
                if ($category->bankrupt_categorys != null) {
                    $subcategoryCheck = false;
                    foreach ($category->bankrupt_categorys as $key => $value) {
                        $lotsSubcategory[$key] = $value['name'];
                    }
                }
            break;
        case 'arrest':
                $lotsCategory = LotsCategory::find()->where(['not', ['arrest_categorys' => null]])->orderBy('id ASC')->all();
                $category = LotsCategory::findOne($queryCategory);
                if ($category->arrest_categorys != null) {
                    $subcategoryCheck = false;
                    foreach ($category->arrest_categorys as $key => $value) {
                        $lotsSubcategory[$key] = $value['name'];
                    }
                }
            break;
        case 'zalog':
                $lotsCategory = LotsCategory::find()->where(['not', ['zalog_categorys' => null]])->orderBy('id ASC')->all();
                $category = LotsCategory::findOne($queryCategory);
                if ($category->zalog_categorys != null) {
                    $subcategoryCheck = false;
                    foreach ($category->zalog_categorys as $key => $value) {
                        $lotsSubcategory[$key] = $value['name'];
                    }
                }
            break;
    }
}
$this->registerJsVar('lotType', $type, $position = yii\web\View::POS_HEAD);
$this->registerJsVar('categorySelected', $queryCategory, $position = yii\web\View::POS_HEAD);
?>


    <section class="page-wrapper page-result pb-0">

      <div class="page-title bg-light mb-0">

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

      <aside class="container">

        <style>
          .search-box {
            -webkit-box-shadow: none;
            box-shadow: none;
            border-radius: 3px;
            border: 1px solid #d6dade;
            padding: 15px;
          }

          .search-form-control {

            border: 2px solid #F04E23;

          }

          .search-box .control-label {
            display: block;
            margin-bottom: .25rem;
            line-height: 1;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
          }
        </style>

        <h1 class="h3 mt-40 line-125 "><?= Yii::$app->params['h1'] ?></h1>
        <hr>

        <div class="row equal-height gap-30 gap-lg-40">
            
            <aside class="col-12 col-lg-4">

                <?php $form = ActiveForm::begin(['id' => 'search-lot-form', 'action'=>$url, 'method' => 'GET']); ?>

                <aside class="sidebar-wrapper pv">
                
                  <div class="secondary-search-box mb-30">
                  
                      <h4 class="">Поиск</h4>
                      
                      <div class="row">
                      
                          <div class="col-12">
                              <div class="col-inner">
                                  <?=$form->field($model, 'type')->dropDownList([
                                          'bankrupt' => 'Банкротное имущество',
                                          'arrest' => 'Арестованное имущество',
                                          'zalog' => 'Залоговое имущество',
                                      ], [
                                          'class'=>'chosen-type-select form-control form-control-sm', 
                                          'data-placeholder'=>'Выберите тип лота', 
                                          'tabindex'=>'2',
                                          'options' => [
                                              // 'zalog' => ['disabled' => true, 'title'=>'Скоро'],
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
                                          'class'=>'chosen-category-select form-control form-control-sm', 
                                          'data-placeholder'=>'Все категории', 
                                          'tabindex'=>'2'
                                      ])
                                      ->label('Категория');?>
                              </div>
                          </div>

                          <div class="col-12">
                              <div class="col-inner">
                                  <?=$form->field($model, 'subCategory')->dropDownList(
                                          $lotsSubcategory,
                                      [
                                          'class'=>'chosen-the-basic subcategory-load form-control form-control-sm', 
                                          'data-placeholder'=>'Все подкатегории', 
                                          'disabled' => $subcategoryCheck,
                                          'multiple' => true,
                                          'tabindex'=>'2'
                                      ])
                                      ->label('Подкатегория');?>
                              </div>
                          </div>
                          
                          <div class="col-12">
                              <div class="col-inner">
                                  <?=$form->field($model, 'region')->dropDownList(
                                          $regionList,
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
                                  <?= Html::submitButton('<i class="ion-android-search"></i> Поиск', ['class' => 'btn btn-primary btn-block load-list-click', 'name' => 'login-button']) ?>
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

                  <div class="sidebar-box bankrupt-type">
                  
                      <div class="box-title"><h5><?=$traderLabel?></h5></div>
                      
                      <div class="box-content">
                          <?=$form->field($model, 'etp')->dropDownList(
                                  $traderList, 
                              [
                                  'class'=>'chosen-the-basic form-control',
                                  'prompt' => $traderPlaceholder,
                                  'data-placeholder'=>$traderPlaceholder, 
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
                          <div class="custom-control custom-checkbox">
                              <?= $form->field($model, 'archivCheck')->checkbox([
                                      'class'=>'custom-control-input',
                                      'value' => '1',
                                      'id' => 'archivCheck',
                                      'template' => '{input}<label class="custom-control-label" for="archivCheck">Лоты с архива</label>']) ?>
                          </div>
                          
                      </div>
                      
                  </div>

                  <div class="sidebar-box">
                    <?= Html::submitButton('<i class="ion-android-search"></i> Поиск', ['class' => 'btn btn-primary btn-block load-list-click', 'name' => 'login-button']) ?>
                  </div>
                  <div class="sidebar-box">
                    <p></p>
                  </div>

                </aside>

                <?php ActiveForm::end(); ?>


            </aside>
            
            <div class="col-12 col-lg-8">
                
              <div class="content-wrapper pv">
              
                <div class="d-flex justify-content-between flex-row align-items-center sort-group page-result-01">
                    <div class="sort-box">
                        <div class="d-flex align-items-center sort-item">
                            
                            <label class="sort-label d-none d-sm-flex">Сортировка по:</label>
                            <?php $form = ActiveForm::begin(['id' => 'sort-lot-form', 'method' => 'GET']); ?>
                            <div class="sort-form">
                                <?=$form->field($modelSort, 'sortBy')->dropDownList([
                                        'nameASC'   =>'Название от А до Я',
                                        'nameDESC'  =>'Название от Я до А',
                                        'dateDESC'  =>'Сначала новые',
                                        'dateASC'   =>'Сначала старые',
                                        'priceDESC' =>'Цена по убыванию',
                                        'priceASC'  =>'Цена по возрастанию'
                                    ],[
                                        'class'=>'chosen-sort-select form-control sortSelect', 
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
                            <label class="sort-label d-none d-sm-flex">Найдено лотов: <?=$count?></label>
                        </div>
                    </div>
                </div>

                <div class="tour-long-item-wrapper-01 load-list">
                  <? foreach ($lots as $lot) {
                    echo LotBlock::widget(['lot' => $lot, 'type' => 'long']);
                  } ?>
                </div>

                <div class="pager-wrappper mt-40">

                  <div class="pager-innner">
                  
                    <div class="row align-items-center text-center text-lg-left">
                    
                      <div class="col-12 col-lg-5">
                        <? if (count($lots) > 0) {?>
                            Выведено от <?= $offset+1 ?> до <?= $offset + count($lots)?> лотов. Всего <?=$count?>.
                        <? } else { ?>
                            Лотов не найдено
                        <? } ?>
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

      </div>

    </section>