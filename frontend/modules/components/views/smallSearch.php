<?php

use common\models\db\Region;
use common\models\db\Torg;
use frontend\modules\models\Category;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

/* @var $regionList Region[] */
/* @var $color */
/* @var $url */
/* @var $btnColor */
/* @var $type */

$this->registerJsVar('lotType', $type, $position = yii\web\View::POS_HEAD);
$this->registerJsVar('categorySelected', 0, $position = yii\web\View::POS_HEAD);

$btnStyle = ($btnColor) ? "background: $btnColor; border-color: $btnColor;" : '';
?>
<? if ($color) { ?>
    <style>
        .hero-banner-01 .search-form-main .form-group label {
            color: <?= $color ?>;
        }

        .hero-banner-01 .search-form-main .form-group .form-control::-webkit-input-placeholder {
            color: <?= $color ?>;
        }

        .hero-banner-01 .search-form-main .form-group .form-control::-moz-placeholder {
            color: <?= $color ?>;
        }

        .hero-banner-01 .search-form-main .form-group .form-control:-moz-placeholder {
            color: <?= $color ?>;
        }

        .hero-banner-01 .search-form-main .form-group .form-control:-ms-input-placeholder {
            color: <?= $color ?>;
        }

        .chosen-container-single a:not([href]):not([tabindex]).chosen-single:not(.chosen-default) {
            color: <?= $color ?> !important;
        }

        .chosen-container-single a:not([href]):not([tabindex]) {
            color: <?= $color ?> !important;
        }
    </style>
<? } ?>

<?php $form = ActiveForm::begin(['method' => 'get', 'action' => '/' . $url . '/lot-list', 'options' => ['enctype' => 'multipart/form-data', 'class' => 'card-search-form', 'id' => 'mainSearchForm']]) ?>

<div class="card card-search search borr-20" style="margin-top:25px;">
    <div class="card-body">
        <div class="input-search">
            <?= $form->field($model, 'search')->textInput([
                'class'       => 'form-control search__field',
                'placeholder' => 'Введите поисковую фразу',
            ])->label(false); ?>

            <?= Html::submitButton('<i class="ion-android-search"></i>', ['class' => 'btn btn-primary btn-block btn-search search__btn', 'style' => $btnStyle, 'name' => 'login-button', 'id' => 'buttonSearch']) ?>
        </div>
        <style>
            .card-search {
                margin-top: 50px;
            }

            .input-search {

                position: relative;
            }

            .input-search .form-control {
                border: 3px solid<?= ($btnColor)? $btnColor : '#077751'?>
            }

            .btn-search {
                position: absolute;
                top: 0;
                right: 0;
                width: 5rem;
                border: 3px solid<?= ($btnColor)? $btnColor : '#077751'?>
            }

        </style>

        <div class="row cols-1 cols-sm-3 gap-10">
            <div class="col">
                <div class="col-inner height-100">
                    <?= $form->field($model, 'type')->dropDownList(
                        Torg::getTypeList(),
                        [
                            'class'            => 'chosen-type-select form-control form-control-sm',
                            'data-placeholder' => 'Во всех типах',
                            'tabindex'         => '2',
                            'options'          => [
                                $url => ['Selected' => true]
                            ]
                        ])
                        ->label(false); ?>
                </div>
            </div>

            <div class="col">
                <div class="col-inner height-100">
                    <?= $form->field($model, 'mainCategory')->dropDownList(
                        Category::getMainCategoriesList(),
                        [
                            'class'            => 'chosen-category-select form-control form-control-sm',
                            'data-placeholder' => 'Во всех категориях',
                            'tabindex'         => '2'
                        ]
                    )
                        ->label(false); ?>
                </div>
            </div>

            <div class="col">
                <div class="col-inner">
                    <?= $form->field($model, 'region')->dropDownList(
                        $regionList,
                        [
                            'class'            => 'chosen-the-basic form-control form-control-sm',
                            'data-placeholder' => 'По всей России',
                            'tabindex'         => '2',
                            'multiple'         => false
                        ]
                    )
                        ->label(false); ?>
                </div>
            </div>
        </div>

        <?php ActiveForm::end() ?>
    </div>
</div>