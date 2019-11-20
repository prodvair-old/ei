<?php

/* @var $this yii\web\View */

use yii\widgets\Breadcrumbs;
use yii\widgets\LinkPager;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

use common\models\Query\LotsCategory;
use common\models\Query\Regions;

use frontend\components\sro\sroBlock;

$this->title = Yii::$app->params['title'];
$this->params['breadcrumbs'] = Yii::$app->params['breadcrumbs'];
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
    
    
    <div class="container">
    <h1 class="h3 mt-40 line-125 "><?=Yii::$app->params['h1']?></h1>
    <hr>

        <div class="row equal-height gap-30 gap-lg-40">
            
            <div class="col-12 col-lg-4">

                <?php $form = ActiveForm::begin(['id' => 'search-lot-form', 'action'=>$url, 'method' => 'GET']); ?>

                <aside class="sidebar-wrapper pv">
                
                    <div class="secondary-search-box mb-30">
                    
                        <h4 class="">Поиск</h4>
                        
                        <div class="row">
                        
                            <div class="col-12">
                                <div class="col-inner">
                                    <?=$form->field($model, 'search')->textInput([
                                            'class'=>'form-control form-control-sm', 
                                            'placeholder'=>'Например: Союз АУ "Возрождение"',
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
                        <p><?=Yii::$app->params['text']?></p>
                    </div>

                </aside>

                <?php ActiveForm::end(); ?>

            </div>
            
            <div class="col-12 col-lg-8">
                
                <div class="content-wrapper pv">
                
                    <div class="d-flex justify-content-between flex-row align-items-center sort-group page-result-01">
                        <div class="sort-box">
                            
                        </div>
                        <div class="sort-box">
                            <div class="d-flex align-items-center sort-item">
                                <label class="sort-label d-none d-sm-flex">Найдено СРО: <?=$count?></label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row equal-height cols-1 cols-sm-2 gap-20 mb-25 load-list">
                        <?foreach ($sros as $sro) { echo sroBlock::widget(['sro' => $sro]); }?>
                    </div>
                    
                    <div class="pager-wrappper mt-40">

                        <div class="pager-innner">
                        
                            <div class="row align-items-center text-center text-lg-left">
                            
                                <div class="col-12 col-lg-5">
                                    <? if (count($sros) > 0) {?>
                                        Выведено от <?= $offset+1 ?> до <?= $offset + count($sros)?> СРО. Всего <?=$count?>.
                                    <? } else { ?>
                                        СРО не найдено
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

</section>