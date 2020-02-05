<?php

use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

use common\models\Query\LotsCategory;
use common\models\Query\Regions;

$lotsCategory = LotsCategory::find()->where(['or', ['not', [$type . '_categorys' => null]], ['translit_name' => 'lot-list']])->orderBy('id ASC')->all();

$regionList[0] = 'Все регионы';
$regions = Regions::find()->orderBy('id ASC')->all();
foreach ($regions as $region) {
  $regionList[$region->id] = $region->name;
}
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


<?php $form = ActiveForm::begin(['method' => 'get', 'action' => '/'.$url.'/lot-list', 'options' => ['enctype' => 'multipart/form-data', 'class' => 'card-search-form']]) ?>

<div class="card card-search">
  <div class="card-body">

    <div class="input-search">
      <?= $form->field($model, 'search')->textInput([
        'class' => 'form-control',
        'placeholder' => 'Поиск: Машина, Квартира...',
        'tabindex' => '2',
        "input-group" => true,
      ])->label(false); ?>

      <?= Html::submitButton('<i class="ion-android-search"></i>', ['class' => 'btn btn-primary btn-block btn-search', 'style' => $btnStyle, 'name' => 'login-button']) ?>
    </div>
    <style>
      .card-search {
        margin-top: 50px;
      }

      .input-search {

        position: relative;
      }

      .input-search .form-control {
        border: 3px solid <?= ($btnColor)? $btnColor : '#F04E23'?>
      }

      .btn-search {
        position: absolute;
        top: 0;
        right: 0;
        width: 5rem;
        border: 3px solid <?= ($btnColor)? $btnColor : '#F04E23'?>
      }

    </style>

    <div class="row cols-1 cols-sm-3 gap-1">
      <div class="col">
        <div class="col-inner height-100">
          <?= $form->field($model, 'type')->dropDownList([
            'all' => 'Все типы',
            'bankrupt' => 'Банкротное имущество',
            'arrest' => 'Арестованное имущество',
            'zalog' => 'Имущество организаций',
          ], [
            'class' => 'chosen-type-select form-control form-control-sm',
            'data-placeholder' => 'Выберите тип лота',
            'tabindex' => '2',
            'options' => [
              $url => ['Selected' => true]
            ]
          ])
            ->label('Тип лота'); ?>
        </div>
      </div>

      <div class="col">
        <div class="col-inner height-100">
          <?= $form->field($model, 'category')->dropDownList(
            ArrayHelper::map($lotsCategory, 'id', 'name'),
            [
              'class' => 'chosen-category-select form-control form-control-sm',
              'data-placeholder' => 'Все категории',
              'tabindex' => '2'
            ]
          )
            ->label('Категория'); ?>
        </div>
      </div>

      <div class="col">
        <div class="col-inner">
          <?= $form->field($model, 'region')->dropDownList(
            $regionList,
            [
              'class' => 'chosen-the-basic form-control form-control-sm',
              'data-placeholder' => 'Все регионы',
              'tabindex' => '2',
              'multiple' => false
            ]
          )
            ->label('Регион'); ?>
        </div>
      </div>
    </div>

    <?php ActiveForm::end() ?>
  </div>
</div>