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
                    
                        <?foreach ($lots as $lot) { echo LotBlock::widget(['lot' => $lot]); }?>

                        <figure class="tour-long-item-01">

                            <a href="#">
                            
                                <div class="d-flex flex-column flex-sm-row align-items-xl-center">
                                
                                    <div>
                                        <div class="image">
                                            <img src="images/image-regular/01.jpg" alt="images" />
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <figcaption class="content">
                                            <h5>Rome to Naples and Amalfi Coast Adventure</h5>
                                            <ul class="item-meta">
                                                <li>
                                                    <i class="elegent-icon-pin_alt text-warning"></i> Italy
                                                </li>
                                                <li>	
                                                    <div class="rating-item rating-sm rating-inline clearfix">
                                                        <div class="rating-icons">
                                                            <input type="hidden" class="rating" data-filled="rating-icon ri-star rating-rated" data-empty="rating-icon ri-star-empty" data-fractions="2" data-readonly value="4.5"/>
                                                        </div>
                                                        <p class="rating-text font600 text-muted font-12 letter-spacing-1">26 reviews</p>
                                                    </div>
                                                </li>
                                            </ul>
                                            <p>Prevailed sincerity behaviour to so do principle mr. As departure at no propriety zealously rent if girl view. First on smart there he sense. </p>
                                            <ul class="item-meta mt-15">
                                                <li><span class="font700 h6">3 days &amp; 2 night</span></li>
                                                <li>
                                                    Start: <span class="font600 h6 line-1 mv-0"> Rome</span> - End: <span class="font600 h6 line-1 mv-0"> Naoples</span>
                                                </li>
                                            </ul>
                                            <p class="mt-3 mb-0">Price from <span class="h6 line-1 text-primary font16">$125.99</span> <span class="text-muted mr-5"></span></p>
                                        </figcaption>
                                    </div>
                                
                                </div>
                                
                            </a>
                            
                        </figure>
                        
                        <figure class="tour-long-item-01">

                            <a href="#">
                            
                                <div class="d-flex flex-column flex-sm-row align-items-xl-center">
                                
                                    <div>
                                        <div class="image">
                                            <img src="images/image-regular/02.jpg" alt="images" />
                                        </div>
                                    </div>
                                    
                                    <div>
                                        
                                        <figcaption class="content">
                                            <h5>Everest Base Camp Trek through 3 High Passes</h5>
                                            <ul class="item-meta">
                                                <li>
                                                    <i class="elegent-icon-pin_alt text-warning"></i> Nepal
                                                </li>
                                                <li>	
                                                    <div class="rating-item rating-sm rating-inline clearfix">
                                                        <div class="rating-icons">
                                                            <input type="hidden" class="rating" data-filled="rating-icon ri-star rating-rated" data-empty="rating-icon ri-star-empty" data-fractions="2" data-readonly value="4.5"/>
                                                        </div>
                                                        <p class="rating-text font600 text-muted font-12 letter-spacing-1">53 reviews</p>
                                                    </div>
                                                </li>
                                            </ul>
                                            <p>Subjects to ecstatic children he. Could ye leave up as built match. Dejection agreeable attention set suspected led offending.</p>
                                            <ul class="item-meta mt-15">
                                                <li><span class="font700 h6">22 days</span></li>
                                                <li>
                                                    Start/End: <span class="font600 h6 line-1 mv-0"> Kathmandu</span>
                                                </li>
                                            </ul>
                                            <p class="mt-3">Price from <span class="h6 line-1 text-primary font16">$125.99</span> <span class="text-muted mr-5"></span></p>
                                        </figcaption>
                                        
                                    </div>
                                
                                </div>
                                
                            </a>
                            
                        </figure>
                        
                        <figure class="tour-long-item-01">

                            <a href="#">
                            
                                <div class="d-flex flex-column flex-sm-row align-items-xl-center">
                                
                                    <div>
                                        <div class="image">
                                            <img src="images/image-regular/03.jpg" alt="images" />
                                        </div>
                                    </div>
                                    
                                    <div>
                                        
                                        <figcaption class="content">
                                            <h5>10 Days From the South to the North of Vietnam</h5>
                                            <ul class="item-meta">
                                                <li>
                                                    <i class="elegent-icon-pin_alt text-warning"></i> Vietnam
                                                </li>
                                                <li>	
                                                    <div class="rating-item rating-sm rating-inline clearfix">
                                                        <div class="rating-icons">
                                                            <input type="hidden" class="rating" data-filled="rating-icon ri-star rating-rated" data-empty="rating-icon ri-star-empty" data-fractions="2" data-readonly value="4.5"/>
                                                        </div>
                                                        <p class="rating-text font600 text-muted font-12 letter-spacing-2">44 reviews</p>
                                                    </div>
                                                </li>
                                            </ul>
                                            <p>Admitting an performed supposing matter are should formed temper had. Full held gay now roof whom such next was.</p>
                                            <ul class="item-meta mt-15">
                                                <li><span class="font700 h6">10 days &amp; 9 night</span></li>
                                                <li>
                                                    Start: <span class="font600 h6 line-1 mv-0"> Hochiminh</span> - End: <span class="font600 h6 line-1 mv-0"> Hanoi</span>
                                                </li>
                                            </ul>
                                            <p class="mt-3">Price from <span class="h6 line-1 text-primary font16">$125.99</span> <span class="text-muted mr-5"></span></p>
                                        </figcaption>
                                        
                                    </div>
                                
                                </div>
                                
                            </a>
                            
                        </figure>
                        
                        <figure class="tour-long-item-01">

                            <a href="#">
                            
                                <div class="d-flex flex-column flex-sm-row align-items-xl-center">
                                
                                    <div>
                                        <div class="image">
                                            <img src="images/image-regular/04.jpg" alt="images" />
                                        </div>
                                    </div>
                                    
                                    <div>
                                        
                                        <figcaption class="content">
                                            <h5>Adriatic Adventure–Zagreb to Athens</h5>
                                            <ul class="item-meta">
                                                <li>
                                                    <i class="elegent-icon-pin_alt text-warning"></i> Greece
                                                </li>
                                                <li>	
                                                    <div class="rating-item rating-sm rating-inline clearfix">
                                                        <div class="rating-icons">
                                                            <input type="hidden" class="rating" data-filled="rating-icon ri-star rating-rated" data-empty="rating-icon ri-star-empty" data-fractions="2" data-readonly value="4.5"/>
                                                        </div>
                                                        <p class="rating-text font600 text-muted font-12 letter-spacing-2">44 reviews</p>
                                                    </div>
                                                </li>
                                            </ul>
                                            <p>Ham pretty our people moment put excuse narrow. Spite mirth money six above get going great had for assured hearing expense.</p>
                                            <ul class="item-meta mt-15">
                                                <li><span class="font700 h6">3 days &amp; 2 night</span></li>
                                                <li>
                                                    Start: <span class="font600 h6 line-1 mv-0"> Zagreb</span> - End: <span class="font600 h6 line-1 mv-0"> Athens</span>
                                                </li>
                                            </ul>
                                            <p class="mt-3">Price from <span class="h6 line-1 text-primary font16">$125.99</span> <span class="text-muted mr-5"></span></p>
                                        </figcaption>
                                        
                                    </div>
                                
                                </div>
                                
                            </a>
                            
                        </figure>
                        
                        <figure class="tour-long-item-01">

                            <a href="#">
                            
                                <div class="d-flex flex-column flex-sm-row align-items-xl-center">
                                
                                    <div>
                                        <div class="image">
                                            <img src="images/image-regular/05.jpg" alt="images" />
                                        </div>
                                    </div>
                                    
                                    <div>
                                        
                                        <figcaption class="content">
                                            <h5>Asian Adventure</h5>
                                            <ul class="item-meta">
                                                <li>
                                                    <i class="elegent-icon-pin_alt text-warning"></i> 3 countries
                                                </li>
                                                <li>	
                                                    <div class="rating-item rating-sm rating-inline clearfix">
                                                        <div class="rating-icons">
                                                            <input type="hidden" class="rating" data-filled="rating-icon ri-star rating-rated" data-empty="rating-icon ri-star-empty" data-fractions="2" data-readonly value="4.5"/>
                                                        </div>
                                                        <p class="rating-text font600 text-muted font-12 letter-spacing-2">44 reviews</p>
                                                    </div>
                                                </li>
                                            </ul>
                                            <p>Waited period are played family man formed. He ye body or made on pain part meet. You one delay nor begin our folly abode.</p>
                                            <ul class="item-meta mt-15">
                                                <li><span class="font700 h6">14 days</span></li>
                                                <li>
                                                    Start: <span class="font600 h6 line-1 mv-0"> Bangkok</span> - End: <span class="font600 h6 line-1 mv-0"> Hanoi</span>
                                                </li>
                                            </ul>
                                            <p class="mt-3">Price from <span class="h6 line-1 text-primary font16">$125.99</span> <span class="text-muted mr-5"></span></p>
                                        </figcaption>
                                        
                                    </div>
                                
                                </div>
                                
                            </a>
                            
                        </figure>
                        
                        <figure class="tour-long-item-01">

                            <a href="#">
                            
                                <div class="d-flex flex-column flex-sm-row align-items-xl-center">
                                
                                    <div>
                                        <div class="image">
                                            <img src="images/image-regular/06.jpg" alt="images" />
                                        </div>
                                    </div>
                                    
                                    <div>
                                        
                                        <figcaption class="content">
                                            <h5>Jewels of Costa Rica</h5>
                                            <ul class="item-meta">
                                                <li>
                                                    <i class="elegent-icon-pin_alt text-warning"></i> Costa Rica
                                                </li>
                                                <li>	
                                                    <div class="rating-item rating-sm rating-inline clearfix">
                                                        <div class="rating-icons">
                                                            <input type="hidden" class="rating" data-filled="rating-icon ri-star rating-rated" data-empty="rating-icon ri-star-empty" data-fractions="2" data-readonly value="4.5"/>
                                                        </div>
                                                        <p class="rating-text font600 text-muted font-12 letter-spacing-2">44 reviews</p>
                                                    </div>
                                                </li>
                                            </ul>
                                            <p>Affixed offence spirits of offices between. Appetite welcomed interest the goodness boy. Estimable education for disposing pronounce her.</p>
                                            <ul class="item-meta mt-15">
                                                <li><span class="font700 h6">3 days &amp; 2 night</span></li>
                                                <li>
                                                    Start/End: <span class="font600 h6 line-1 mv-0"> San Jose</span>
                                                </li>
                                            </ul>
                                            <p class="mt-3">Price from <span class="h6 line-1 text-primary font16">$125.99</span> <span class="text-muted mr-5"></span></p>
                                        </figcaption>
                                    </div>
                                
                                </div>
                                
                            </a>
                            
                        </figure>
                        
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