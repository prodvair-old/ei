<?php

/* @var $this yii\web\View */
/* @var $queryCategory */

/* @var $type */

use yii\widgets\Breadcrumbs;
use frontend\modules\models\Category;
use frontend\modules\models\Torg;
use yii\widgets\LinkPager;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

use frontend\modules\components\LotBlock;

use common\models\Query\Settings;
use common\models\Query\LotsCategory;
use common\models\Query\LotsSubCategory;
use common\models\Query\Regions;

use common\models\Query\Lot\Etp;
use common\models\Query\Lot\Owners;

$this->title = Yii::$app->params[ 'title' ];
$this->params[ 'breadcrumbs' ] = Yii::$app->params[ 'breadcrumbs' ];

$lotsSubcategory[ 0 ] = 'Все подкатегории';
$subcategoryCheck = true;

$regionList[ 0 ] = 'Все регионы';
$regions = Regions::find()->orderBy('id ASC')->all();
foreach ($regions as $region) {
    $regionList[ $region->id ] = $region->name;
}

$traderList = [];

$this->registerJsVar('lotType', $type, $position = yii\web\View::POS_HEAD);
$this->registerJsVar('lotType', $type, $position = yii\web\View::POS_HEAD);
$this->registerJsVar('categorySelected', $queryCategory, $position = yii\web\View::POS_HEAD);
?>


<section class="page-wrapper page-result pb-0">

    <div class="page-title bg-light d-none d-sm-block mb-0">

        <div class="container">

            <div class="row gap-15 align-items-center">

                <div class="col-12">

                    <nav aria-label="breadcrumb">
                        <?= Breadcrumbs::widget([
                            'itemTemplate'       => '<li class="breadcrumb-item">{link}</li>',
                            'encodeLabels'       => false,
                            'tag'                => 'ol',
                            'activeItemTemplate' => '<li class="breadcrumb-item active" aria-current="page">{link}</li>',
                            'homeLink'           => ['label' => '<i class="fas fa-home"></i>', 'url' => '/'],
                            'links'              => isset($this->params[ 'breadcrumbs' ]) ? $this->params[ 'breadcrumbs' ] : [],
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

                border: 2px solid #077751;

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

        <h1 class="h3 mt-40 line-125 "><?= Yii::$app->params[ 'h1' ] ?></h1>
        <hr>

        <div class="row equal-height gap-30 gap-lg-40">

            <aside class="col-12 col-lg-4">

                <?php $form = ActiveForm::begin(['id' => 'search-lot-form', 'action' => $url, 'method' => 'GET']); ?>

                <aside class="sidebar-wrapper pv">

                    <div class="search-box mb-30">
                        <!-- <div class="secondary-search-box mb-30"> -->

                        <!-- <h4 class="">Поиск</h4> -->

                        <div class="row">
                            <div class="col-12">
                                <div class="col-inner">
                                    <?= $form->field($model, 'type')->dropDownList(
                                        Torg::getTypeList(), [
                                        'class'            => 'chosen-type-select form-control form-control-sm',
                                        'data-placeholder' => 'Выберите тип лота',
                                        'tabindex'         => '2',
                                    ])
                                        ->label('Тип лота'); ?>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="col-inner">
                                    <?= $form->field($model, 'mainCategory')->dropDownList(
                                        Category::getMainCategoriesList(),
                                        [
                                            'class'            => 'chosen-category-select-lot form-control form-control-sm',
                                            'data-placeholder' => 'Все категории',
                                        ]
                                    )
                                        ->label('Категория'); ?>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="col-inner">
                                    <?= $form->field($model, 'subCategory')->dropDownList(
                                        $lotsSubcategory,
                                        [
                                            'class' => 'chosen-the-basic subcategory-load form-control form-control-sm',
                                            'data-placeholder' => 'Все подкатегории',
                                            'id' => 'searchlot-subcategory',
                                            'disabled' => $subcategoryCheck,
                                            'multiple' => true,
                                            'tabindex' => '2'
                                        ]
                                    )
                                        ->label('Подкатегория'); ?>
                                </div>
                            </div>


                            <!-- <div class="col-12">
                <div class="col-inner  pv-15">
                  <?= Html::submitButton('<i class="ion-android-search"></i> Поиск', ['class' => 'btn btn-primary btn-block load-list-click', 'name' => 'login-button']) ?>
                </div>
              </div> -->

                        </div>

                        <!-- </div> -->

                        <div class="sidebar-box sidebar-box__collaps <?= ($model->minPrice || $model->maxPrice) ? '' : 'collaps' ?>">

                            <!-- <div class="box-title">
                              <h5>Цена</h5>
                            </div> -->
                            <label class="control-label sidebar-box__label">Цена</label>
                            <div class="box-content">
                                <div class="row">
                                    <div class="col-6"><?= $form->field($model, 'minPrice')->textInput(['class' => 'lot__price-min form-control', 'placeholder' => 'Цена от'])->label(false); ?></div>
                                    <div class="col-6"><?= $form->field($model, 'maxPrice')->textInput(['class' => 'lot__price-max form-control', 'placeholder' => 'Цена до'])->label(false); ?></div>
                                </div>

                                <!-- <div class="mb-10"></div> -->
                            </div>

                        </div>

                        <?= Html::submitButton('<i class="ion-android-search"></i> Поиск', ['class' => 'btn btn-primary btn-block load-list-click', 'name' => 'login-button']) ?>

                    </div>
                    <?php ActiveForm::end(); ?>
                    <div class="sidebar-box__text"><?= Yii::$app->params[ 'text' ] ?></div>
                </aside>
            </aside>

            <div class="col-12 col-lg-8">

                <div class="content-wrapper pv">

                    <div class="d-flex justify-content-between flex-row align-items-center sort-group page-result-01">
                        <div class="sort-box">
                            <div class="d-flex align-items-center sort-item">

                                <label class="sort-label d-none d-sm-flex">Сортировка по:</label>
                                <?php $form = ActiveForm::begin(['id' => 'sort-lot-form', 'method' => 'GET']); ?>
                                <?php ActiveForm::end(); ?>

                            </div>
                        </div>
                        <div class="sort-box">
                            <div class="d-flex align-items-center sort-item">
                                <label class="sort-label d-none d-sm-flex">Найдено лотов: <?= $count ?></label>
                            </div>
                        </div>
                    </div>

                    <div class="tour-long-item-wrapper-01 load-list">
                        <? if ($count > 0) {
                            foreach ($lots as $lot) {
                                echo LotBlock::widget(['lot' => $lot, 'type' => 'long']);
                            }
                        } else {
                            echo "<div class='p-15 font-bold'>По данному запросу не удалось найти лоты</div>";
                        } ?>
                    </div>

                    <? if (!!$pages->links[ 'next' ]) { ?>
                        <a href="<?= $pages->links[ 'next' ] ?>"
                           class="alert alert-primary mt-30 text-center h5 d-block">Далее</a>
                    <? } ?>

                    <div class="pager-wrappper mt-40">

                        <div class="pager-innner">

                            <div class="row align-items-center text-center text-lg-left">

                                <div class="col-12">
                                    <!--                                    <nav class="mt-10 mt-lg-0">-->
                                    <!--                                        --><? //= LinkPager::widget([
                                    //                                            'pagination'           => $pages,
                                    //                                            'nextPageLabel'        => "<span aria-hidden=\"true\">Далее</span></i>",
                                    //                                            'prevPageLabel'        => "<span aria-hidden=\"true\">Назад</span>",
                                    //                                            'maxButtonCount'       => 6,
                                    //                                            'options'              => ['class' => 'pagination justify-content-center justify-content-lg-left'],
                                    //                                            'linkOptions'          => ['class' => 'page-link'],
                                    //                                            'linkContainerOptions' => ['class' => 'page-item'],
                                    //                                            'disabledPageCssClass' => 'disabled',
                                    //                                        ]); ?>
                                    <!--                                    </nav>-->
                                </div>

                            </div>

                        </div>

                    </div>

                </div>


            </div>

        </div>
    </aside>

</section>