<?php

use common\models\db\Lot;
use sergmoro1\lookup\models\Lookup;
use yii\widgets\Breadcrumbs;
use frontend\modules\models\Category;
use common\models\db\Torg;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use common\models\db\Owner;
use common\models\db\Etp;
use frontend\modules\components\LotBlock;

/* @var $this yii\web\View */
/* @var $queryCategory */
/* @var $model \frontend\modules\models\LotSearch */
/* @var $regionList [] \common\models\db\Region */

/* @var $type */
/* @var $url */
/* @var $lots Lot */

$this->title = Yii::$app->params[ 'title' ];
$this->params[ 'breadcrumbs' ] = Yii::$app->params[ 'breadcrumbs' ];

$lotsSubcategory[ 0 ] = 'Все подкатегории';
$subcategoryCheck = true;


if ($model->mainCategory) {
    $subCategories = Category::findOne(['id' => $model->mainCategory]);
    $leaves = $subCategories->leaves()->all();
    $leaves = ArrayHelper::map($leaves, 'id', 'name');
    $lotsSubcategory += $leaves;
    $subcategoryCheck = false;
}

$traderList = [];

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

                        <div class="row">

                            <div class="col-12">
                                <div class="">
                                    <?= $form->field($model, 'search')->textInput([
                                        'class'       => 'form-control search-form-control',
                                        'placeholder' => 'Поиск: Машина, Квартира...',
                                    ])->label('Поиск'); ?>
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

                            <div class="col-12">
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
                                            'data-placeholder' => 'Выберите регионы',
                                            'tabindex'         => '2',
                                            'multiple'         => true
                                        ]
                                    )
                                        ->label('Регион'); ?>
                                </div>
                            </div>

                        </div>

                        <div class="sidebar-box sidebar-box__collaps <?= ($model->minPrice || $model->maxPrice) ? '' : 'collaps' ?>">

                            <label class="control-label sidebar-box__label">Цена</label>
                            <div class="box-content">
                                <div class="row">
                                    <div class="col-6"><?= $form->field($model, 'minPrice')->textInput(['class' => 'lot__price-min form-control', 'placeholder' => 'Цена от'])->label(false); ?></div>
                                    <div class="col-6"><?= $form->field($model, 'maxPrice')->textInput(['class' => 'lot__price-max form-control', 'placeholder' => 'Цена до'])->label(false); ?></div>
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

                        <div class="sidebar-box sidebar-box__collaps <?= ($model->haveImage || $model->andArchived) ? '' : 'collaps' ?>">
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
                        </div>

                        <?php if ($model->type == Torg::PROPERTY_BANKRUPT) : ?>
                            <div class="sidebar-box sidebar-box__collaps <?= ($model->type == Torg::PROPERTY_BANKRUPT) ? '' : 'collaps' ?>">
                                <label class="control-label  sidebar-box__label">Дополнительные параметры</label>
                                <div class="box-content col-md-12">
                                    <label>Номер ЕФРСБ</label>
                                    <div>
                                        <?= $form->field($model, 'efrsb')->textInput(
                                            ['class' => 'form-control', 'placeholder' => 'Введите номер']
                                        )->label(false); ?>
                                    </div>
                                </div>

                                <div class="box-content col-md-12">
                                    <label>ФИО Должника</label>
                                    <div>
                                        <?= $form->field($model, 'bankruptName')->textInput(
                                            ['class' => 'form-control', 'placeholder' => 'ФИО']
                                        )->label(false); ?>
                                    </div>
                                </div>

                                <div class="box-content col-md-12">
                                    <label>Начало торгов</label>
                                    <div class="row">
                                        <div class="col-md-12">

                                        </div>
                                        <div class="col-md-6">
                                            <?= $form->field($model, 'torgStartDate')->textInput(
                                                ['class' => 'form-control', 'placeholder' => 'От']
                                            )->label(false); ?>
                                        </div>
                                        <div class="col-md-6">
                                            <?= $form->field($model, 'torgEndDate')->textInput(
                                                ['class' => 'form-control', 'placeholder' => 'До']
                                            )->label(false); ?>
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
                                <div class="box-content col-md-12 mt-10">
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
                            <div class="row">
                                <div class="col-md-6">
                                    БД: <?= round(Yii::getLogger()->getDbProfiling()[ 1 ], 3) ?> сек.
                                </div>
                                <div id="profiling_page_load" class="col-md-6"></div>
                            </div>
                            <div class="d-flex align-items-center sort-item">
                                <label class="sort-label d-none d-sm-flex">Сортировка по:</label>
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
                            foreach ($lots as $lot) {
                                echo LotBlock::widget(['lot' => $lot, 'type' => 'long', 'url' => $url]);
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

    var offset = 0;
    var i = 2000;

    $(window).scroll(function () {

        if ($(window).scrollTop() + $(window).height() >= $(document).height() - i) {
            offset = offset + 15;
            i = 0;

            let url;
            if (location.href.indexOf('?', location.search) === -1) {
                url = location.href + '?LotSearch[offset]=' + offset
            } else {
                url = location.href + '&LotSearch[offset]=' + offset
            }

            $.ajax({
                type: "GET",
                url: url,
                data: $(this).serialize(),
                success: function (data) {
                    $('#load_list').append(data);
                    console.log('lots load success');
                    i = 2000;
                },
                error: function (result) {
                    console.log('lots load error');
                    console.log(result);
                }
            });

        }
    });

</script>