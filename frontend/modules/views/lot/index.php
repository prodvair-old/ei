<?php

use common\models\db\Lot;
use frontend\assets\ScrollAsset;
use frontend\modules\components\DateRange;
use frontend\modules\models\LotSearch;
use sergmoro1\lookup\models\Lookup;
use yii\widgets\Breadcrumbs;
use frontend\modules\models\Category;
use common\models\db\Torg;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use common\models\db\Owner;
use common\models\db\Etp;
use frontend\modules\components\LotBlock;
use frontend\modules\components\LotBlockSmall;

/* @var $this yii\web\View */
/* @var $queryCategory */
/* @var $model LotSearch */
/* @var $regionList [] \common\models\db\Region */

/* @var $type */
/* @var $url */
/* @var $offsetStep */
/* @var $lots Lot */


$mapGET                    = Yii::$app->request->get()['LotSearch'];
$mapGET['type']            = $model->type;
$mapGET['mainCategory']    = $model->mainCategory;

$this->title = Yii::$app->params[ 'title' ];
$this->params[ 'breadcrumbs' ] = Yii::$app->params[ 'breadcrumbs' ];
$this->title = $this->params[ 'breadcrumbs' ][0]['label'];
$this->title .= ($this->params[ 'breadcrumbs' ][1]) ?  " /{$this->params[ 'breadcrumbs' ][1]['label']}" : '';
$lotsSubcategory[ 0 ] = 'Все подкатегории';
$subcategoryCheck = true;

//echo "<pre>";
//var_dump($this->params[ 'breadcrumbs' ][0]['label']);
//echo "</pre>";

if ($model->mainCategory) {
    $subCategories = Category::findOne(['id' => $model->mainCategory]);
    $leaves = $subCategories->leaves()->all();
    $leaves = ArrayHelper::map($leaves, 'id', 'name');
    $lotsSubcategory += $leaves;
    $subcategoryCheck = false;
}
$traderList = [];

ScrollAsset::register($this);
$this->registerJsVar('lotType', $type, $position = yii\web\View::POS_HEAD);
$this->registerJsVar('categorySelected', $queryCategory, $position = yii\web\View::POS_HEAD);
$this->registerJsVar('offsetStep', $offsetStep, $position = yii\web\View::POS_END);
$this->registerJsVar('modelSearchName', 'LotSearch', $position = yii\web\View::POS_END);
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

        <h1 class="h3 mt-40 line-125 "><?= $this->title ?></h1>
        <hr>

        <div class="row equal-height gap-30 gap-lg-40">

            <aside class="col-12 col-lg-4">

                <aside class="sidebar-wrapper pv">
                    <?php $form = ActiveForm::begin(['id' => 'search-lot-form', 'action' => $url, 'method' => 'GET']); ?>

                    <div class="search-box mb-30 borr-10 border-dots p-30">

                        <div class="row">

                            <div class="col-12">
                                <div class="">
                                    <?= $form->field($model, 'search')->textInput([
                                        'class'       => 'form-control search-form-control borr-10',
                                        'placeholder' => 'Я ищу...',
                                    ])->label('Поисковая фраза'); ?>
                                </div>
                            </div>
                            <div class="col-12">
                            <div class="col-inne">
                                <div class="custom-control custom-checkbox">
                                    <?= $form->field($model, 'hasReport')->checkbox([
                                        'class'    => 'custom-control-input',
                                        'value'    => '1',
                                        'id'       => 'hasReport',
                                        'template' => '{input}<label class="custom-control-label" for="hasReport">Только с отчетом</label>'
                                    ]) ?>
                                </div>
                            </div>
                            </div>

                            <div class="col-12">
                                <div class="col-inner">
                                    <?= $form->field($model, 'type')->dropDownList(
                                        Torg::getTypeList(), [
                                        'class'            => 'chosen-type-select form-control form-control-sm',
                                        'data-placeholder' => 'Выберите тип имущества',
                                        'tabindex'         => '2',
                                    ])
                                        ->label('Тип имущества'); ?>
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

                            <div class="col-12 <?= ($model->mainCategory) ?? 'hidden' ?>"
                                id="searchlot-subcategory-wrapper">
                                <div class="col-inner">
                                    <?= $form->field($model, 'subCategory')->dropDownList(
                                        $lotsSubcategory,
                                        [
                                            'class'            => 'chosen-the-basic subcategory-load form-control form-control-sm',
                                            'data-placeholder' => 'Все подкатегории',
                                            'id'               => 'searchlot-subcategory',
                                            'disabled'         => $subcategoryCheck,
                                            'multiple'         => true,
                                            'tabindex'         => '2'
                                        ]
                                    )
                                        ->label('Подкатегория'); ?>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="col-inner">
                                    <?= $form->field($model, 'region')->dropDownList(
                                        $regionList,
                                        [
                                            'class'            => 'chosen-the-basic form-control form-control-sm',
                                            'data-placeholder' => 'По всей России',
                                            'tabindex'         => '2',
                                            'multiple'         => true
                                        ]
                                    )
                                        ->label('Регион'); ?>
                                </div>
                            </div>

                        </div>

                        <div
                            class="sidebar-box sidebar-box__collaps <?= ($model->minPrice || $model->maxPrice) ? '' : 'collaps' ?>">

                            <label class="control-label sidebar-box__label">Цена, руб.</label>
                            <div class="box-content">
                                <div class="row">
                                    <div class="col-6">
                                        <?= $form->field($model, 'minPrice')->textInput(['class' => 'lot__price-min form-control', 'placeholder' => 'Цена от'])->label(false); ?>
                                    </div>
                                    <div class="col-6">
                                        <?= $form->field($model, 'maxPrice')->textInput(['class' => 'lot__price-max form-control', 'placeholder' => 'Цена до'])->label(false); ?>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <?php if ($model->type == Torg::PROPERTY_BANKRUPT) : ?>

                        <div class="sidebar-box  sidebar-box__collaps <?= ($model->etp) ? '' : 'collaps' ?>">

                            <label class="control-label sidebar-box__label">Торговые площадки</label>
                            <div class="box-content">
                                <?= $form->field($model, 'etp')->dropDownList(
                                        Etp::getOrganizationList(),
                                        [
                                            'class'            => 'chosen-the-basic form-control',
                                            'prompt'           => 'Все торговые площадки',
                                            'data-placeholder' => 'Все торговые площадки',
                                            'multiple'         => true
                                        ]
                                    )
                                        ->label(false); ?>
                            </div>

                        </div>

                        <?php endif; ?>

                        <?php if ($model->type == Torg::PROPERTY_ZALOG) : ?>
                        <div class="sidebar-box sidebar-box__collaps <?= ($model->owner) ? '' : 'collaps' ?>">
                            <label class="control-label sidebar-box__label">Организации</label>
                            <div class="box-content">
                                <?= $form->field($model, 'owner')->dropDownList(
                                        Owner::getOrganizationList(),
                                        [
                                            'class'            => 'chosen-the-basic form-control',
                                            'prompt'           => 'Все организации',
                                            'data-placeholder' => 'Все организации',
                                            'multiple'         => true
                                        ]
                                    )
                                        ->label(false); ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <div class="sidebar-box sidebar-box__collaps <?= ($model->tradeType) ? '' : 'collaps' ?>">

                            <label class="control-label sidebar-box__label">Тип торгов</label>
                            <div class="box-content">

                                <?= $form->field($model, 'tradeType')->checkboxList(
                                    Lookup::items('TorgOffer'), [
                                    'class' => 'custom-control custom-checkbox',
                                    'item'  => function ($index, $label, $name, $checked, $value) {
                                        $inputId = 'tradetype' . $index;

                                        return "<div><input type=\"checkbox\" name=\"$name\" value=\"$value\" id=\"$inputId\" " . (($checked) ? 'checked' : '') . " class=\"custom-control-input\">"
                                            . "<label for=\"$inputId\" class=\"custom-control-label\">$label</label></div>";
                                    }
                                ])->label(false); ?>

                            </div>

                        </div>

                        <div
                            class="sidebar-box sidebar-box__collaps <?= ($model->haveImage || $model->andArchived || $model->priceDown) ? '' : 'collaps' ?>">
                            <label class="control-label  sidebar-box__label">Другое</label>
                            <div class="box-content">
                                <div class="custom-control custom-checkbox">
                                    <?= $form->field($model, 'andArchived')->checkbox([
                                        'class'    => 'custom-control-input',
                                        'value'    => '1',
                                        'id'       => 'andArchived',
                                        'template' => '{input}<label class="custom-control-label" for="andArchived">Лоты из архива</label>'
                                    ]) ?>
                                </div>
                            </div>
                            <div class="box-content">
                                <div class="custom-control custom-checkbox">
                                    <?= $form->field($model, 'haveImage')->checkbox([
                                        'class'    => 'custom-control-input',
                                        'value'    => '1',
                                        'id'       => 'imageCheck',
                                        'template' => '{input}<label class="custom-control-label" for="imageCheck">Только с фото</label>'
                                    ]) ?>
                                </div>
                            </div>
                            <?php if ($model->type == Torg::PROPERTY_BANKRUPT) : ?>
                                <div class="box-content">
                                    <div class="custom-control custom-checkbox">
                                        <?= $form->field($model, 'priceDown')->checkbox([
                                            'class'    => 'custom-control-input',
                                            'value'    => '1',
                                            'id'       => 'priceDown',
                                            'template' => '{input}<label class="custom-control-label" for="priceDown">Цена снижена</label>'
                                        ]) ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                        </div>

                        <?php if ($model->type == Torg::PROPERTY_BANKRUPT) : ?>
                        <div
                            class="sidebar-box sidebar-box__collaps <?= ($model->type == Torg::PROPERTY_BANKRUPT) ? '' : 'collaps' ?>">
                            <label class="control-label  sidebar-box__label">Дополнительные параметры</label>
                            <div class="box-content col-md-12 mt-20">
                                <div>
                                    <?= $form->field($model, 'efrsb')->textInput(
                                            ['class' => 'form-control', 'placeholder' => 'Введите номер']
                                        )->label('Номер ЕФРСБ'); ?>
                                </div>
                            </div>

                            <div class="box-content col-md-12 mt-10">
                                <div>
                                    <?= $form->field($model, 'bankruptName')->textInput(
                                            ['class' => 'form-control', 'placeholder' => 'Должник']
                                        )->label('Должник'); ?>
                                </div>
                            </div>

                            <div class="box-content col-md-12 mt-10">
                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="control-label">Начало торгов</label>
                                    </div>
                                    <div class="col-md-12">
                                        <?= $form->field($model, 'torgDateRange')->widget(DateRange::classname(), [
                                                'name'          => 'torgDateRange',
                                                'value'         => $model->torgDateRange,
//                                                'readonly'      => true,
                                                'convertFormat' => true,
                                                'hideInput' => true,
                                                'pluginOptions' => [
                                                    'locale'              => ['format' => 'Y-m-d'],
                                                ],
                                                'options' => ['placeholder' => 'Выберите интервал']
                                            ])->label(false); ?>
                                    </div>
                                </div>
                            </div>

                            <div class="box-content col-md-12 mt-10">
                                <div class="custom-control custom-checkbox">
                                    <?= $form->field($model, 'startApplication')->checkbox([
                                            'class'    => 'custom-control-input',
                                            'value'    => '1',
                                            'id'       => 'startApplication',
                                            'template' => '{input}<label class="custom-control-label" for="startApplication">Начало приема заявок</label>'
                                        ]) ?>
                                </div>
                            </div>
                            <div class="box-content col-md-12 mb-10">
                                <div class="custom-control custom-checkbox">
                                    <?= $form->field($model, 'competedApplication')->checkbox([
                                            'class'    => 'custom-control-input',
                                            'value'    => '1',
                                            'id'       => 'competedApplication',
                                            'template' => '{input}<label class="custom-control-label" for="competedApplication">Окончание приема заявок</label>'
                                        ]) ?>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?= Html::submitButton('<i class="ion-android-search"></i> Найти', ['class' => 'btn btn-primary btn-block load-list-click borr-10', 'name' => 'login-button']) ?>
                        <a
                            <?= (Yii::$app->user->isGuest) ? 'href="#loginFormTabInModal-login" class="btn btn-outline-primary btn-block borr-10" data-toggle="modal" data-target="#loginFormTabInModal" data-backdrop="static" data-keyboard="false"' : 'href="#" class="save-lot-search-js btn btn-outline-primary btn-block borr-10"' ?>>
                            <i class="ion-android-notifications"></i>
                            Отслеживать поиск
                        </a>
                        <!-- <div class="custom-control custom-checkbox d-flex justify-content-center">
                            <div class="form-group field-competedApplication">
                                <?= Html::checkbox('search-preset-agree', true, [
                            'class'    => 'custom-control-input',
                            'id'       => 'search-preset-agree',
                            'template' => '{input}'
                        ]) ?>
                                <label class="custom-control-label" for="search-preset-agree">Получать уведомления по новым лотам</label>
                            </div>
                        </div> -->

                    </div>
                    <?php ActiveForm::end(); ?>
                    <div class="sidebar-box__text"><?= Yii::$app->params[ 'text' ] ?></div>
                </aside>
            </aside>

            <div class="col-12 col-lg-8">

                <div class="content-wrapper pv">

                    <div class="d-flex justify-content-between flex-row align-items-center sort-group page-result-01">
                        <div class="sort-box">
                            <!-- <div class="row">
                                <div class="col-md-6">
                                    БД: <?= round(Yii::getLogger()->getDbProfiling()[ 1 ], 3) ?> сек.
                                </div>
                                <div id="profiling_page_load" class="col-md-6"></div>
                            </div> -->
                            <div class="d-flex align-items-center sort-item">
                                <!-- <label class="sort-label d-none d-sm-flex">Сортировка по:</label> -->
                                <?php $form = ActiveForm::begin(['id' => 'sort-lot-form', 'method' => 'GET']); ?>
                                <div class="sort-form">
                                    <?= $form->field($model, 'sortBy')->dropDownList(
                                        $model->getSortMap(), [
                                        'class'            => 'chosen-sort-select form-control sortSelect',
                                        'data-placeholder' => 'Сортировка по',
                                    ])
                                        ->label(false); ?>
                                </div>
                                <?php ActiveForm::end(); ?>
                            </div>
                        </div>
                    </div>

                    <div id="load_list" class="tour-long-item-wrapper-01 load-list">
                        <? if (count($lots) > 0) {
                            foreach ($lots as $key => $lot) {
                                if ($key == 1) { ?>
                                    <div class="mb-30 lot_next__btn long">
                                        <a href="<?= Url::to(['lot/map', 'MapSearch' => $mapGET]) ?>" class="borr-10 btn btn-primary btn-block mr-30">
                                            <i class="oi oi-map-marker"></i> 
                                            Показать лоты на карте
                                        </a>
                                    </div>
                                <? }
                                echo LotBlockSmall::widget(['lot' => $lot, 'long' => true, 'url' => $url]);
                            }
                        } else {
                            echo "<div class='p-15 font-bold'>По данному запросу не удалось найти лоты</div>";
                        } ?>
                    </div>

                </div>

            </div>

        </div>
    </aside>

</section>

<script>
    window.addEventListener('load', function () {
        console.log("Fiered load after " + performance.now() + " ms");
        document.getElementById('profiling_page_load').innerHTML = 'Страница: ' + (Math.round(performance.now()) / 1000) + ' сек.';
    }, false);
</script>