<?php
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

use common\models\Query\LotsCategory;
use common\models\Query\Regions;


switch ($type) {
    case 'bankrupt':
            $lotsCategory = LotsCategory::find()->where(['or', ['not', ['bankrupt_categorys' => null]], ['translit_name' => 'lot-list']])->orderBy('id ASC')->all();
        break;
    case 'arrest':
            $lotsCategory = LotsCategory::find()->where(['or', ['not', ['arrest_categorys' => null]], ['translit_name' => 'lot-list']])->orderBy('id ASC')->all();
        break;
}
$this->registerJsVar( 'lotType', $type, $position = yii\web\View::POS_HEAD );
$this->registerJsVar( 'categorySelected', 0, $position = yii\web\View::POS_HEAD );
?>
<div class="search-form-main">
    <?php $form = ActiveForm::begin(['method' => 'get','action' => '/bankrupt/lot-list','options' => ['enctype' => 'multipart/form-data']]) ?>
        <div class="from-inner">
            
            <div class="row shrink-auto-sm gap-1">
            
                <div class="col-12 col-auto">
                    <div class="col-inner">
                        <div class="row cols-1 cols-sm-4 gap-1">
            
                            <div class="col">
                                <div class="col-inner height-100">
                                    <?=$form->field($model, 'type')->dropDownList([
                                            'bankrupt' => 'Банкротное имущество',
                                            'arrest' => 'Арестованное имущество',
                                            'zalog' => 'Залоговое имущество',
                                        ], [
                                            'class'=>'chosen-type-select form-control form-control-sm', 
                                            'data-placeholder'=>'Выберите тип лота', 
                                            'tabindex'=>'2',
                                            'options' => [
                                                'zalog' => ['disabled' => true, 'title'=>'Скоро'],
                                                $type => ['Selected' => true]
                                            ]])
                                        ->label('Тип лота');?>
                                </div>
                            </div>

                            <div class="col">
                                <div class="col-inner height-100">
                                    <?=$form->field($model, 'category')->dropDownList(
                                            ArrayHelper::map($lotsCategory, 'id', 'name'),
                                        [
                                            'class'=>'chosen-category-select form-control form-control-sm', 
                                            'data-placeholder'=>'Все категории', 
                                            'tabindex'=>'2'
                                        ])
                                        ->label('Категория');?>
                                </div>
                            </div>

                            <div class="col">
                                <div class="col-inner height-100">
                                    <?=$form->field($model, 'search')->textInput([
                                            'class'=>'form-control form-control-sm', 
                                            'placeholder'=>'Например: Машина, Квартира...',
                                            'tabindex'=>'2',
                                        ])
                                        ->label('Поиск');?>
                                </div>
                            </div>

                            <div class="col">
                                <div class="col-inner">
                                    <?=$form->field($model, 'region')->dropDownList(
                                            ArrayHelper::map(Regions::find()->orderBy('id ASC')->all(), 'id', 'name'),
                                        [
                                            'class'=>'chosen-the-basic form-control form-control-sm', 
                                            'data-placeholder'=>'Все регионы', 
                                            'tabindex'=>'2',
                                            'multiple' => true
                                        ])
                                        ->label('Регион');?>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-12 col-shrink">
                    <div class="col-inner">
                        <?= Html::submitButton('<i class="ion-android-search"></i>', ['class' => 'btn btn-primary btn-block', 'name' => 'login-button']) ?>
                    </div>
                </div>
                
            </div>
        </div>
    <?php ActiveForm::end() ?>
</div>