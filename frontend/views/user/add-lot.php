<?
use yii\widgets\Breadcrumbs;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

use frontend\components\ProfileMenu;
use common\models\Query\LotsCategory;

$name = (\Yii::$app->user->identity->info['firstname'] || \Yii::$app->user->identity->info['lastname'])? \Yii::$app->user->identity->info['firstname'].' '.\Yii::$app->user->identity->info['lastname'] : \Yii::$app->user->identity->info['contacts']['emails'][0];
$this->title = "Добавить лот – $name";
$this->params['breadcrumbs'][] = [
    'label' => 'Профиль',
    'template' => '<li class="breadcrumb-item active" aria-current="page">{link}</li>',
    'url' => ['user/index']
];
$this->params['breadcrumbs'][] = [
    'label' => 'Новый лот',
    'template' => '<li class="breadcrumb-item" aria-current="page">{link}</li>',
    'url' => ['user/add-lot']
];

$lotsCategory = LotsCategory::find()->where(['or', ['not', ['zalog_categorys' => null]], ['translit_name' => 'lot-list']])->orderBy('id ASC')->all();
$this->registerJsVar('lotType', 'zalog', $position = yii\web\View::POS_HEAD);
?>

<section class="page-wrapper page-detail">
			
    <div class="page-title border-bottom pt-25 mb-0 border-bottom-0">
    
        <div class="container">
        
            <div class="row gap-15 align-items-center">
            
                <div class="col-12 col-md-7">
                    
                    <nav aria-label="breadcrumb">
                        <?= Breadcrumbs::widget([
                            'itemTemplate' => '<li class="breadcrumb-item">{link}</li>',
                            'encodeLabels' => false,
                            'tag' => 'ol',
                            'activeItemTemplate' => '<li class="breadcrumb-item active" aria-current="page">{link}</li>',
                            'homeLink' => ['label' => '<i class="fas fa-home"></i>', 'url' => '/'],
                            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                        ]) ?>
                    </nav>
                    
                </div>
                
            </div>
    
        </div>
        
    </div>
    
    <div class="container pt-30">

        <div class="row gap-20 gap-lg-40">
            
            <div class="col-12 col-lg-3">
                
                <aside class="-kit sidebar-wrapper">

                    <div class="bashboard-nav-box">
                    
                        <div class="box-heading"><h3 class="h6 text-white text-uppercase">Вы авторизованы как:</h3></div>
                        <div class="box-content">
                        
                            <div class="dashboard-avatar mb-10">
                        
                                <div class="image">
                                    <img class="setting-image-tag" src="<?=(Yii::$app->user->identity->avatar)? Yii::$app->user->identity->avatar: 'img/image-man/01.jpg'?>" alt="Image" />
                                </div>
                                
                                <div class="content">
                                    <h6><?=$name?></h6>
                                    <p class="mb-15"><?=(\Yii::$app->user->identity->info['firstname'] || \Yii::$app->user->identity->info['lastname'])? \Yii::$app->user->identity->info['contacts']['emails'][0]: ''?></p>
                                </div>
                                
                            </div>
                            
                            <?=ProfileMenu::widget(['page'=>'addlot'])?>
                            
                            <!-- <p class="font-sm mt-20">Your last logged-in: <span class="text-primary font700">4 hours ago</span></p> -->

                        </div>
                        
                    </div>
                
                </aside>
                
            </div>
            
            <div class="col-12 col-lg-9">
                
                <div class="content-wrapper">
                    
                    <div class="form-draft-payment">
                    
                        <h3 class="heading-title"><span>Новый <span class="font200"> лот</span></span></h3>
                        
                        <div class="clear"></div>

                        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>
                            <div class="row gap-30">

                                <div class="col-12">

                                
                                    <div class="avatar-upload">
                                        <div class="row lot-load-images">

                                        <!-- <div class="col-2 load-image-lot">
                                            <img class="profile-pic d-block setting-image-tag" src="img/image-man/01.jpg" alt="avatar" />
                                            <a href="#"><i class="fa fa-trash"></i></a>
                                        </div> -->

                                        </div> 
                                        
                                        <label for="lot-upload" class="lot-upload">
                                            <div class="upload-button text-secondary line-1">
                                                <div>
                                                    <i class="fas fa-upload text-primary"></i>
                                                </div>
                                            </div>
                                        </label>
                                        <?= $form->field($model, 'images[]')->fileInput(['multiple' => true, 'class'=>'file-upload', 'id'=>'lot-upload', 'accept' => 'image/*'])->label(false) ?>
                                        <div class="labeling">
                                            <i class="fas fa-upload"></i> <span class="setting-image-info">Загрузить фото лота</span>
                                        </div>
                                    </div>

                                </div>
                                
                                <div class="col-12">
                                
                                    <div class="col-inner">
                                    
                                        <div class="row gap-20">
                                        
                                            <div class="col-12 col-sm-6">
                                                <div class="form-group mb-0">
                                                    <?= $form->field($model, 'lotId')->textInput(['class' => 'form-control', 'placeholder' => 'Ваш ID лота'])->label('ID лота *') ?>
                                                </div>
                                            </div>

                                            <div class="col-12 col-sm-6">
                                                <div class="form-group mb-0">
                                                <?=$form->field($model, 'tradeType')->dropDownList([
                                                        'Аукцион'=>'Аукцион', 
                                                        'Публичное предложение'=>'Публичное предложение'
                                                    ],
                                                    [
                                                        'class'=>'chosen-category-select form-control form-control-sm',
                                                        'data-placeholder'=>'Тип торгов', 
                                                        'tabindex'=>'2',
                                                    ])
                                                    ->label('Тип торгов *');?>
                                                    <?// $form->field($model, 'tradeTipeId')->hiddenInput(['class' => 'form-control'])->label(false) ?>
                                                </div>
                                            </div>

                                            <div class="col-12 col-sm-6">
                                                <div class="form-group mb-0">
                                                    <?=$form->field($model, 'categoryIds')->dropDownList(
                                                            ArrayHelper::map($lotsCategory, 'id', 'name'),
                                                        [
                                                            'class'=>'chosen-category-select form-control form-control-sm', 
                                                            'data-placeholder'=>'Все категории', 
                                                            'tabindex'=>'2'
                                                        ])
                                                        ->label('Категория *');?>
                                                </div>
                                            </div>

                                            <div class="col-12 col-sm-6">
                                                <div class="form-group mb-0">
                                                    <?=$form->field($model, 'subCategory')->dropDownList(
                                                            [0=>'Все подкатегории'],
                                                        [
                                                            'class'=>'chosen-the-basic subcategory-load form-control form-control-sm', 
                                                            'data-placeholder'=>'Все подкатегории', 
                                                            'id' => 'searchlot-subcategory',
                                                            'disabled' => true,
                                                            'multiple' => true,
                                                            'tabindex'=>'2'
                                                        ])
                                                        ->label('Подкатегория *');?>
                                                </div>
                                            </div>

                                            <hr>

                                            <div class="col-12">
                                                <div class="form-group mb-0">
                                                    <?= $form->field($model, 'title')->textInput(['class' => 'form-control', 'placeholder' => 'Заголовок'])->label('Заголовок лота *') ?>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div class="form-group mb-0">
                                                    <?= $form->field($model, 'description')->textarea(['class' => 'form-control', 'placeholder' => 'Описание'])->label('Описание лота *') ?>
                                                </div>
                                            </div>

                                            <div class="col-12 col-sm-6">
                                                <div class="form-group mb-0 chosen-bg-light">
                                                    <?= $form->field($model, 'publicationDate')->textInput(['class' => 'form-control datepicker-basic', 'placeholder' => 'Дата публикации'])->label('Дата публикации *') ?>
                                                </div>
                                            </div>

                                            <div class="col-12 col-sm-6">
                                                <div class="form-group mb-0 chosen-bg-light">
                                                    <?= $form->field($model, 'startingDate')->textInput(['class' => 'form-control datepicker-basic', 'placeholder' => 'Дата начала торгов'])->label('Дата начала торгов *') ?>
                                                </div>
                                            </div>

                                            <div class="col-12 col-sm-6">
                                                <div class="form-group mb-0 chosen-bg-light">
                                                    <?= $form->field($model, 'endingDate')->textInput(['class' => 'form-control datepicker-basic', 'placeholder' => 'Дата окончания приёма заявок'])->label('Дата окончания приёма заявок *') ?>
                                                </div>
                                            </div>

                                            <div class="col-12 col-sm-6">
                                                <div class="form-group mb-0 chosen-bg-light">
                                                    <?= $form->field($model, 'completionDate')->textInput(['class' => 'form-contro datepicker-basic', 'placeholder' => 'Дата завершения торгов'])->label('Дата завершения торгов *') ?>
                                                </div>
                                            </div>

                                            <hr>

                                            <div class="col-12 col-sm-4">
                                                <div class="form-group mb-0">
                                                    <?= $form->field($model, 'startingPrice')->textInput(['class' => 'form-control', 'placeholder' => 'Начальная цена лота'])->label('Начальная цена *') ?>
                                                </div>
                                            </div>
                                            
                                            <div class="col-12 col-sm-4">
                                                <div class="form-group mb-0">
                                                    <?= $form->field($model, 'step')->textInput(['class' => 'form-control', 'placeholder' => 'Шаг аукциона'])->label('Шаг аукциона') ?>
                                                </div>
                                            </div>

                                            <div class="col-12 col-sm-4">
                                                <div class="form-group mb-0">
                                                    <?= $form->field($model, 'stepCount')->textInput(['class' => 'form-control', 'placeholder' => 'Количество шагов'])->label('Количество шагов') ?>
                                                </div>
                                            </div>

                                            <hr>

                                            <div class="col-12 col-sm-3">
                                                <div class="form-group mb-0">
                                                    <?= $form->field($model, 'country')->textInput(['class' => 'form-control', 'placeholder' => 'Страна'])->label('Страна *') ?>
                                                </div>
                                            </div>

                                            <div class="col-12 col-sm-3">
                                                <div class="form-group mb-0">
                                                    <?= $form->field($model, 'city')->textInput(['class' => 'form-control', 'placeholder' => 'Город'])->label('Город *') ?>
                                                </div>
                                            </div>

                                            <div class="col-12 col-sm-6">
                                                <div class="form-group mb-0">
                                                    <?= $form->field($model, 'address')->textInput(['class' => 'form-control', 'placeholder' => 'Адрес лота'])->label('Адрес *') ?>
                                                </div>
                                            </div>
                                            
                                            <hr>

                                            <div class="col-12 col-sm-6">
                                                <div class="form-group mb-0">
                                                    <?= $form->field($model, 'procedureDate')->textInput(['class' => 'form-control datepicker-basic', 'placeholder' => 'Срок проведения процедуры', 'onClick' => 'xCal(this, {lang: \'ru\'})'])->label('Срок проведения процедуры') ?>
                                                </div>
                                            </div>

                                            <div class="col-12 col-sm-6">
                                                <div class="form-group mb-0">
                                                    <?= $form->field($model, 'conclusionDate')->textInput(['class' => 'form-control datepicker-basic', 'placeholder' => 'Срок заключения Договора', 'onClick' => 'xCal(this, {lang: \'ru\'})'])->label('Срок заключения Договора') ?>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div class="form-group mb-0">
                                                    <?= $form->field($model, 'viewInfo')->textarea(['class' => 'form-control', 'placeholder' => 'Дата, время и порядок осмотра лота'])->label('Дата, время и порядок осмотра лота') ?>
                                                </div>
                                            </div>

                                            <hr>
                                            
                                            <div class="col-12 col-sm-4">
                                                <div class="form-group mb-0">
                                                    <?= $form->field($model, 'collateralPrice')->textInput(['class' => 'form-control', 'placeholder' => 'Размер задатка'])->label('Размер задатка') ?>
                                                </div>
                                            </div>

                                            <div class="col-12 col-sm-8">
                                                <div class="form-group mb-0">
                                                    <?= $form->field($model, 'currentPeriod')->textInput(['class' => 'form-control', 'placeholder' => 'Период действия текущей цены аукциона (для торговой процедуры в форме аукциона «на повышение»)'])->label('Период действия текущей цены') ?>
                                                </div>
                                            </div>
                                    
                                            <div class="col-12">
                                                <div class="form-group mb-0">
                                                    <?= $form->field($model, 'paymentDetails')->textarea(['class' => 'form-control', 'placeholder' => 'Реквизиты для оплаты задатка'])->label('Реквизиты для оплаты') ?>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div class="form-group mb-0">
                                                    <?= $form->field($model, 'additionalConditions')->textarea(['class' => 'form-control', 'placeholder' => 'Дополнительные условия и критерии определения победителя'])->label('Дополнительные условия') ?>
                                                </div>
                                            </div>

                                        </div>
                                        
                                        <div class="mb-30"></div>
                                        
                                        <div class="row gap-10 mt-15 justify-content-center justify-content-md-start">
                                            <div class="col-auto">
                                                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
                                            </div>
                                            <div class="col-auto">
                                                <a href="<?=Url::to(['user/lots'])?>" class="btn btn-secondary">Назад</a>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                            
                            </div>

                        <?php ActiveForm::end() ?>
                    </div>
                    
                </div>
                
            </div>
            
        </div>
        
    </div>

</section>

<script>
    $.datetimepicker.setLocale('ru');
    $('.datepicker-basic').datetimepicker({
        format: 'Y-m-d H:m:s',
        inline: true,
        lang: 'ru',
    });                                                    
</script>

<?php
// $this->registerJsFile( 'https://code.jquery.com/jquery-3.4.1.min.js', $options = ['position' => yii\web\View::POS_END], $key = 'jq' );
// $this->registerJsFile( '/js/data_picker.js', $options = ['position' => yii\web\View::POS_HEAD], $key = 'date_picker' );
// $this->registerCssFile('/css/data_picker.css');
?>