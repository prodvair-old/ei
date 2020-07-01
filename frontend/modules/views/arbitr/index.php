<?php

/* @var $this yii\web\View */
/* @var $model Manager */
/* @var $searchModel ManagerSearch */

use common\models\db\Manager;
use frontend\modules\models\ManagerSearch;
use yii\widgets\Breadcrumbs;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Арбитражные управляющие по делам банкротов';
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

                    <div class="secondary-search-box mb-30">

                        <h4 class="">Поиск</h4>

                        <div class="row">

                            <div class="col-12">
                                <div class="col-inner">
                                    <?= $form->field($searchModel, 'search')->textInput([
                                        'class'       => 'form-control form-control-sm',
                                        'placeholder' => 'Например: Иванов Иван',
                                        'tabindex'    => '2',
                                    ])
                                        ->label('ФИО'); ?>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="col-inner">
                                    <?= $form->field($searchModel, 'inn')->textInput([
                                        'class'       => 'form-control form-control-sm',
                                        'placeholder' => 'Например: 742403257984',
                                        'tabindex'    => '2',
                                    ])
                                        ->label('Инн'); ?>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="col-inner">
                                    <?= $form->field($searchModel, 'regNumber')->textInput([
                                        'class'       => 'form-control form-control-sm',
                                        'placeholder' => 'Например: 0007',
                                        'tabindex'    => '2',
                                    ])
                                        ->label('Рег.номер'); ?>
                                </div>
                            </div>

                            <div class="box-content col-12">
                                <div class="custom-control custom-checkbox">
                                    <?= $form->field($searchModel, 'torgsIsActive')->checkbox([
                                        'class'    => 'custom-control-input',
                                        'value'    => '1',
                                        'id'       => 'andArchived',
                                        'template' => '{input}<label class="custom-control-label" for="andArchived">Активные торги</label>'
                                    ]) ?>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="col-inner ph-20 pv-15">
                                    <?= Html::submitButton('<i class="ion-android-search"></i> Поиск', ['class' => 'btn btn-primary btn-block load-list-click']) ?>
                                </div>
                            </div>

                        </div>

                    </div>

                    <div class="sidebar-box">
                        <p><?= Yii::$app->params[ 'text' ] ?></p>
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
                                <label class="sort-label d-none d-sm-flex">Найдено Арбитражных
                                    управляющих: <?= $count ?></label>
                            </div>
                        </div>
                    </div>

                    <div id="load_list" class="row equal-height cols-1 cols-sm-2 gap-20 mb-25 load-list">
                        <? foreach ($model as $arbitr) : ?>
                            <div class="col">

                                <figure class="tour-grid-item-01">

                                    <a href="<?= Url::to(['/arbitr']) ?>/<?= $arbitr->id ?>">

                                        <figcaption class="content">
                                            <h5><?= $arbitr->profileRel->fullName ?></h5>
                                            <ul class="item-meta">
                                                <li>
                                                    <i class="elegent-icon-pin_alt text-warning"></i> <?= $arbitr->placeRel->address ?>
                                                </li>
                                            </ul>
                                        </figcaption>

                                    </a>

                                </figure>

                            </div>
                        <?php endforeach; ?>
                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

<script>
    var offset = 0;
    var i = 2000;

    $(window).scroll(function () {

        if ($(window).scrollTop() + $(window).height() >= $(document).height() - i) {
            offset = offset + 15;
            i = 0;

            let url;
            if (location.href.indexOf('?', location.search) === -1) {
                url = location.href + '?ManagerSearch[offset]=' + offset
            } else {
                url = location.href + '&ManagerSearch[offset]=' + offset
            }

            $.ajax({
                type: "GET",
                url: url,
                data: $(this).serialize(),
                success: function (data) {
                    $('#load_list').append(data);
                    console.log('arbitr load success');
                    i = 2000;
                },
                error: function (result) {
                    console.log('arbitr load error');
                    console.log(result);
                }
            });

        }
    });

</script>