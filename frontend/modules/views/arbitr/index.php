<?php

/* @var $this yii\web\View */
/* @var $model Manager */
/* @var $searchModel ManagerSearch */
/* @var $offsetStep */

use common\models\db\Manager;
use frontend\assets\ScrollAsset;
use frontend\modules\models\ManagerSearch;
use yii\widgets\Breadcrumbs;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

ScrollAsset::register($this);
$this->registerJsVar('offsetStep', $offsetStep, $position = yii\web\View::POS_END);
$this->registerJsVar('modelSearchName', 'ManagerSearch', $position = yii\web\View::POS_END);

$this->title = 'Реестр арбитражных управляющих';
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
        <hr>
        <div class="row equal-height gap-30 gap-lg-40">


            <div class="col-12 col-lg-4">

                <?php $form = ActiveForm::begin(['id' => 'search-lot-form', 'action' => $url, 'method' => 'GET']); ?>

                <aside class="sidebar-wrapper pv">

                    <div class="secondary-search-box mb-30 borr-10 border-dots">

                        <h4 class="bg-white">Поиск Арбитражных управляющих</h4>

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
                                <div class="col-inner">
                                    <?= $form->field($searchModel, 'search')->textInput([
                                            'class'       => 'form-control search-form-control borr-10 mt-5 pl-10 pt-5 pr-10 pb-5',
                                            'placeholder' => 'Я ищу...',
                                            'tabindex'    => '2',
                                        ])->label('Поисковый запрос'); ?>
                                    <p class="text-muted pl-20 pr-20">Пример: ФИО</p>
                                </div>
                            </div>

                            <div class="box-content col-12">
                                <div class="custom-control custom-checkbox pl-10">
                                    <?= $form->field($searchModel, 'isVerified')->checkbox([
                                        'class'    => 'custom-control-input',
                                        'value'    => '1',
                                        'id'       => 'isVerified',
                                        'template' => '<div class="col-md-8">{input}<label class="custom-control-label" for="isVerified">Только верифицированные</label></div>'
                                    ]) ?>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="col-inner">
                                    <?= $form->field($searchModel, 'inn')->textInput([
                                            'class'       => 'form-control form-control-sm border-dots borr-10 mt-5 pl-10 pt-5 pr-10 pb-5',
                                            'placeholder' => 'Например: 742403257984',
                                            'tabindex'    => '2',
                                        ])->label('Инн'); ?>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="col-inner">
                                    <?= $form->field($searchModel, 'regNumber')->textInput([
                                        'class'       => 'form-control form-control-sm border-dots borr-10 mt-5 pl-10 pt-5 pr-10 pb-5',
                                        'placeholder' => 'Например: 0007',
                                        'tabindex'    => '2',
                                    ])
                                        ->label('Рег.номер'); ?>
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
                                    <?= Html::submitButton('<i class="ion-android-search"></i> Найти', ['class' => 'btn btn-primary btn-block load-list-click borr-10']) ?>
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
                                <label class="sort-label d-none d-sm-flex">Найдено Арбитражных
                                    управляющих: <?= $count ?></label>
                            </div>
                        </div>
                    </div>

                    <div class="slider__item green slider__item-full">
                        <div class="slider__item__title">Вы арбитражный управляющий?<br> — Получите CRM арбитражника!</div>
                        <p class="slider__item__text mb-0">Вам будет доступно:</p>
                        <ul class="slider__item__list">
                            <li><i class="fa fa-check pr-5"></i>Управление вашими лотами</li>
                            <li><i class="fa fa-check pr-5"></i>Просмотр статистики и аналитики</li>
                            <li><i class="fa fa-check pr-5"></i>Добавление отчетов</li>
                            <li><i class="fa fa-check pr-5"></i>Работа с заявками</li>
                            <li><i class="fa fa-check pr-5"></i>Управление личными данными</li>
                        </ul>
                        <div class="mt-30"></div>
                        <a href="/contact" class="slider__item__link">
                            Получить верификацию
                            <i class="ion-ios-arrow-forward"></i>
                        </a>
                        <img src="/uploads/site/4.png" alt="">
                    </div>

                    <div id="load_list" class="row equal-height cols-1 cols-sm-2 gap-20 mb-25 load-list">
                        <?= $this->render('block', ['model' => $model]) ?>
                    </div>

                </div>

            </div>

        </div>

    </div>

</section>