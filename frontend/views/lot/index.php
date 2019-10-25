<?php

/* @var $this yii\web\View */

use yii\helpers\Url;
use frontend\components\LotBlock;
use common\models\Query\Settings;
use yii\widgets\Breadcrumbs;

$this->title = Yii::$app->params['title'];
$this->params['breadcrumbs'][] = [
    'label' => ' Имущество должников',
    'template' => '<li class="breadcrumb-item active" aria-current="page">{link}</li>',
    'url' => ["/$type"]
];
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

                <aside class="sidebar-wrapper pv">
                
                    <div class="secondary-search-box mb-30">
                    
                        <h4 class="">Поиск</h4>
                        
                        <form>
                        
                            <div class="row">
                            
                                <div class="col-12">
                                    <div class="col-inner">
                                        <div class="form-group">
                                            <label>Тип лота</label>
                                            <select class="chosen-the-basic form-control form-control-sm" placeholder="Выберите тип лота" tabindex="2">
                                                <option selected>Банкротное иммущество</option>
                                                <option>Аррестованное иммущество</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-12">
                                    <div class="col-inner">
                                        <div class="form-group">
                                            <label>Категория</label>
                                            <select class="chosen-the-basic form-control form-control-sm" placeholder="Все категории" tabindex="2">
                                                <option></option>
                                                <option>All</option>
                                                <option>Asia</option>
                                                <option>Europe</option>
                                                <option>Africa</option>
                                                <option>America</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-12">
                                    <div class="col-inner">
                                        <div class="form-group">
                                            <label>Регион</label>
                                            <select class="chosen-the-basic form-control form-control-sm" placeholder="Все Регионы" tabindex="2">
                                                <option></option>
                                                <option>All</option>
                                                <option>Asia</option>
                                                <option>Europe</option>
                                                <option>Africa</option>
                                                <option>America</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-12">
                                    <div class="col-inner ph-20 pv-15">
                                        <a href="#" class="btn btn-primary btn-block"><i class="ion-android-search"></i> search</a>
                                    </div>
                                </div>
                            
                            </div>
                        
                        </form>
                    
                    </div>
                    
                    <div class="sidebar-box">
                    
                        <div class="box-title"><h5>Price Range</h5></div>
                        
                        <div class="box-content">
                            <input id="price_range" />
                        </div>
                        
                    </div>
                    
                    <div class="sidebar-box">
                    
                        <div class="box-title"><h5>Star Slider</h5></div>
                        
                        <div class="box-content">
                            <input id="star_range" />
                        </div>
                        
                    </div>
                    
                    <div class="sidebar-box">
                    
                        <div class="box-title"><h5>Starting Point</h5></div>
                        
                        <div class="box-content">
                        
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="filerStartPoint-01" name="filerStartPoint" checked>
                                <label class="custom-control-label" for="filerStartPoint-01">Berlin <span class="text-muted font-sm">(854)</span></label>
                            </div>
                            
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="filerStartPoint-02" name="filerStartPoint" >
                                <label class="custom-control-label" for="filerStartPoint-02">Paris <span class="checkbox-count">(25)</span></label>
                            </div>
                            
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="filerStartPoint-03" name="filerStartPoint" >
                                <label class="custom-control-label" for="filerStartPoint-03">Munich <span class="checkbox-count">(254)</span></label>
                            </div>
                            
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="filerStartPoint-04" name="filerStartPoint" >
                                <label class="custom-control-label" for="filerStartPoint-04">Lyon<span class="checkbox-count">(22)</span></label>
                            </div>
                            
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="filerStartPoint-05" name="filerStartPoint" >
                                <label class="custom-control-label" for="filerStartPoint-05">Vienna  <span class="checkbox-count">(9)</span></label>
                            </div>
                            
                            <div id="filerStartPointShowHide" class="collapse"> 
                            
                                <div class="collapse-inner">

                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="filerStartPoint-06" name="filerStartPoint" >
                                        <label class="custom-control-label" for="filerStartPoint-06">Toulouse <span class="checkbox-count">(3)</span></label>
                                    </div>
                                    
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="filerStartPoint-06" name="filerStartPoint" />
                                        <label class="custom-control-label" for="filerStartPoint-06">Graz <span class="checkbox-count">(25)</span></label>
                                    </div>
                                    
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="filerStartPoint-07" name="filerStartPoint" />
                                        <label class="custom-control-label" for="filerStartPoint-07">Linz  <span class="checkbox-count">(2)</span></label>
                                    </div>

                                </div>
                            
                            </div>
                            
                            <div class="clear mb-10"></div>
                            
                            <button class="btn btn-toggle btn-text-inherit text-primary text-uppercase font10 letter-spacing-2 font600 collapsed collapsed-on padding-0" type="buttom" data-toggle="collapse" data-target="#filerStartPointShowHide">Show more (+)</button>
                            <button class="btn btn-toggle btn-text-inherit text-uppercase font10 letter-spacing-2 font600 collapsed collapsed-off padding-0" type="buttom" data-toggle="collapse" data-target="#filerStartPointShowHide">Show less (-)</button>
                            
                        </div>
                        
                    </div>
                    
                    <div class="sidebar-box">
                    
                        <div class="box-title"><h5>Endong Point</h5></div>
                        
                        <div class="box-content">
                        
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="filerEndPoint-01" name="filerEndPoint" checked>
                                <label class="custom-control-label" for="filerEndPoint-01">Berlin <span class="text-muted font-sm">(854)</span></label>
                            </div>
                            
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="filerEndPoint-02" name="filerEndPoint" >
                                <label class="custom-control-label" for="filerEndPoint-02">Paris <span class="checkbox-count">(25)</span></label>
                            </div>
                            
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="filerEndPoint-03" name="filerEndPoint" >
                                <label class="custom-control-label" for="filerEndPoint-03">Munich <span class="checkbox-count">(254)</span></label>
                            </div>
                            
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="filerEndPoint-04" name="filerEndPoint" >
                                <label class="custom-control-label" for="filerEndPoint-04">Lyon<span class="checkbox-count">(22)</span></label>
                            </div>
                            
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="filerEndPoint-05" name="filerEndPoint" >
                                <label class="custom-control-label" for="filerEndPoint-05">Vienna  <span class="checkbox-count">(9)</span></label>
                            </div>

                            <div id="filerStartPointShowHide" class="collapse"> 
                            
                                <div class="collapse-inner">

                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="filerEndPoint-06" name="filerEndPoint" />
                                        <label class="custom-control-label" for="filerEndPoint-06">Toulouse <span class="checkbox-count">(3)</span></label>
                                    </div>
                                    
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="filerEndPoint-07" name="filerEndPoint" />
                                        <label class="custom-control-label" for="filerEndPoint-07">Graz <span class="checkbox-count">(25)</span></label>
                                    </div>
                                    
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="filerEndPoint-08" name="filerEndPoint" />
                                        <label class="custom-control-label" for="filerEndPoint-08">Linz <span class="checkbox-count">(2)</span></label>
                                    </div>
                                    
                                </div>
                            
                            </div>
                            
                            <div class="clear mb-10"></div>
                            
                            <button class="btn btn-toggle btn-text-inherit text-primary text-uppercase font10 letter-spacing-2 font600 collapsed collapsed-on padding-0" type="buttom" data-toggle="collapse" data-target="#filerStartPointShowHide">Show more (+)</button>
                            <button class="btn btn-toggle btn-text-inherit text-uppercase font10 letter-spacing-2 font600 collapsed collapsed-off padding-0" type="buttom" data-toggle="collapse" data-target="#filerStartPointShowHide">Show less (-)</button>
                            
                        </div>
                        
                    </div>
                    
                    <div class="sidebar-box">
                    
                        <div class="box-title"><h5>Filter Select</h5></div>
                        
                        <div class="box-content">
                            <div class="form-group">
                                <select data-placeholder="Filter Select"  class="chosen-the-basic form-control" tabindex="2">
                                    <option value=""></option>
                                    <option value="filter-select-0">Filter Select One</option>
                                    <option value="filter-select-1">Filter Select Two</option>
                                    <option value="filter-select-2">Filter Select Three</option>
                                    <option value="filter-select-3">Filter Select Four</option>
                                    <option value="filter-select-4">Filter Select Five</option>
                                </select>
                            </div>
                        </div>
                        
                    </div>
                    
                    <div class="sidebar-box">
                    
                        <div class="box-title"><h5>Filter Text</h5></div>
                        
                        <div class="box-content">
                            <p>Park fat she nor does play deal our. Procured sex material his offering humanity laughing moderate can. Unreserved had she nay dissimilar admiration interested.</p>
                        </div>
                        
                    </div>

                </aside>

            </div>
            
            <div class="col-12 col-lg-8">
                
                <div class="content-wrapper pv">
                
                    <div class="d-flex justify-content-between flex-row align-items-center sort-group page-result-01">
                        <div class="sort-box">
                            <div class="d-flex align-items-center sort-item">
                                <label class="sort-label d-none d-sm-flex">Sort by:</label>
                                <div class="sort-form">
                                    <select class="chosen-the-basic form-control" tabindex="2">
                                        <option value="sort-by-1">Name: A to Z</option>
                                        <option value="sort-by-2">Name: Z to A</option>
                                        <option value="sort-by-3">Price: Hight to Low</option>
                                        <option value="sort-by-4">Price: Low to High</option>
                                    </select>
                                </div>
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
                                    Showing reslut 1 to 15 from 248 
                                </div>
                                
                                <div class="col-12 col-lg-7">
                                    <nav class="float-lg-right mt-10 mt-lg-0">
                                        <ul class="pagination justify-content-center justify-content-lg-left">
                                            <li>
                                                <a href="#" aria-label="Previous">
                                                    <span aria-hidden="true">&laquo;</span>
                                                </a>
                                            </li>
                                            <li class="active"><a href="#">1</a></li>
                                            <li><a href="#">2</a></li>
                                            <li><a href="#">3</a></li>
                                            <li><span>...</span></li>
                                            <li><a href="#">11</a></li>
                                            <li><a href="#">12</a></li>
                                            <li><a href="#">13</a></li>
                                            <li>
                                                <a href="#" aria-label="Next">
                                                    <span aria-hidden="true">&raquo;</span>
                                                </a>
                                            </li>
                                        </ul>
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