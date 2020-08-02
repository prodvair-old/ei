<?php

/* @var $this yii\web\View */
/* @var $model Bankrupt */
/* @var $searchModel BankruptSearch */
/* @var $offsetStep */

use common\models\db\Bankrupt;
use frontend\assets\ScrollAsset;
use frontend\modules\models\BankruptSearch;
use yii\widgets\Breadcrumbs;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

ScrollAsset::register($this);
$this->registerJsVar('offsetStep', $offsetStep, $position = yii\web\View::POS_END);
$this->registerJsVar('modelSearchName', 'BankruptSearch', $position = yii\web\View::POS_END);

$this->title = 'Реестр должников';
$this->params[ 'breadcrumbs' ] = Yii::$app->params[ 'breadcrumbs' ];
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

    <div class="container">
        <h1 class="h3 mt-40 line-125 "><?= $this->title ?></h1>
        <div class="row equal-height gap-30 gap-lg-40">

            <div class="col-12 col-lg-4">

                <?php $form = ActiveForm::begin(['id' => 'search-lot-form', 'method' => 'GET']); ?>

                <aside class="sidebar-wrapper pv">

                    <div class="secondary-search-box mb-30 borr-10 border-dots">

                        <h4 class="bg-white">Поиск должников</h4>

                        <style>
                            .custom-control-label::before, .custom-control-label::after {
                                top: -3px
                            }
                            .search-form-control {
                                border: 2px solid #077751!important;
                            }
                            .search-box {
                                -webkit-box-shadow: none;
                                box-shadow: none;
                                border-radius: 3px;
                                border: 1px solid #d6dade;
                                padding: 15px;
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

                        <div class="row">
                            <div class="col-12">
                                <div class="">
                                    <?= $form->field($searchModel, 'search')->textInput([
                                        'class'       => 'form-control search-form-control borr-10 mt-5 pl-10 pt-5 pr-10 pb-5',
                                        'placeholder' => 'Я ищу...',
                                    ])->label('Поисковый запрос'); ?>
                                    <p class="text-muted pl-20 pr-20">Пример: организации / ФИО / ИНН</p>
                                </div>
                            </div>

                            <div class="box-content col-12">
                                <div class="custom-control custom-checkbox pl-10">
                                    <?= $form->field($searchModel, 'torgsIsActive')->checkbox([
                                        'class'    => 'custom-control-input',
                                        'value'    => '1',
                                        'id'       => 'torgsIsActive',
                                        'template' => '<div class="col-md-8">{input}<label class="custom-control-label" for="torgsIsActive">Активные торги</label></div>'
                                    ]) ?>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="col-inner ph-20 pv-15">
                                    <?= Html::submitButton('<i class="ion-android-search"></i> Найти',
                                        ['class' => 'btn btn-primary btn-block load-list-click borr-10']) ?>
                                </div>
                            </div>

                        </div>

                    </div>

                    <? if (Yii::$app->params[ 'text' ]) : ?>
                    <div class="sidebar-box">
                        <p><?= Yii::$app->params[ 'text' ] ?></p>
                    </div>
                    <? endif; ?>

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
                                <label class="sort-label d-none d-sm-flex">Найдено должников: <?= $count ?></label>
                            </div>
                        </div>
                    </div>

                    <div id="load_list" class="row equal-height cols-1 cols-sm-2 gap-20 mb-25 load-list">
                        <?= $this->render('block', ['model' => $model]) ?>
                    </div>

                </div>

            </div>

        </div>

    </div>

</section>