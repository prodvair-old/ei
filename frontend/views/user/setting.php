<?
use yii\widgets\Breadcrumbs;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

use frontend\components\ProfileMenu;

$name = (\Yii::$app->user->identity->info['firstname'] || \Yii::$app->user->identity->info['lastname'])? \Yii::$app->user->identity->info['firstname'].' '.\Yii::$app->user->identity->info['lastname'] : \Yii::$app->user->identity->info['contacts']['emails'][0];
$this->title = "Настройка профиля – $name";
$this->params['breadcrumbs'][] = [
    'label' => 'Профиль',
    'template' => '<li class="breadcrumb-item active" aria-current="page">{link}</li>',
    'url' => ['user/index']
];
$this->params['breadcrumbs'][] = [
    'label' => 'Настройки',
    'template' => '<li class="breadcrumb-item" aria-current="page">{link}</li>',
    'url' => ['user/setting']
];
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
                            
                            <?=ProfileMenu::widget(['page'=>'setting'])?>
                            
                            <!-- <p class="font-sm mt-20">Your last logged-in: <span class="text-primary font700">4 hours ago</span></p> -->

                        </div>
                        
                    </div>
                
                </aside>
                
            </div>
            
            <div class="col-12 col-lg-9">
                
                <div class="content-wrapper">
                    
                    <div class="form-draft-payment">
                    
                        <h3 class="heading-title"><span>Настройка <span class="font200"> профиля</span></span></h3>
                        
                        <div class="clear"></div>

                            <div class="row gap-30">

                                <div class="col-6 col-sm-5 col-md-4 col-lg-4 order-lg-last">

                                    <?php $form = ActiveForm::begin(['action'=>Url::to(['user/setting_image']), 'options' => ['enctype' => 'multipart/form-data', 'id'=>'setting-image']]) ?>

                                    <div class="avatar-upload">
                                        <img class="profile-pic d-block setting-image-tag" src="<?=(Yii::$app->user->identity->avatar)? Yii::$app->user->identity->avatar: 'img/image-man/01.jpg'?>" alt="avatar" />
                                        <label for="avatar-upload">
                                            <div class="upload-button text-secondary line-1">
                                                <div>
                                                    <i class="fas fa-upload text-primary"></i>
                                                    <span class="d-block font12 text-uppercase font700 mt-10 text-primary">Максимальный размер:<br/>250 Мб</span>
                                                </div>
                                            </div>
                                        </label>
                                        <?= $form->field($model_image, 'photo')->fileInput(['class'=>'file-upload', 'id'=>'avatar-upload', 'accept' => 'image/*'])->label(false) ?>
                                        <div class="labeling">
                                            <i class="fas fa-upload"></i> <span class="setting-image-info">Изменить аватарку</span>
                                        </div>
                                    </div>

                                    <?php ActiveForm::end() ?>
                                
                                </div>
                                
                                <div class="col-12 col-md-12 col-lg-8">

                                <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>
                                    <div class="col-inner">
                                    
                                        <div class="row gap-20">
                                        
                                            <div class="col-12 col-sm-4">
                                                <div class="form-group mb-0">
                                                    <?= $form->field($model, 'firstname')->textInput(['class' => 'form-control', 'value' => Yii::$app->user->identity->info['firstname']])->label('Имя') ?>
                                                </div>
                                            </div>
                                            
                                            <div class="col-12 col-sm-4">
                                                <div class="form-group mb-0">
                                                    <?= $form->field($model, 'lastname')->textInput(['class' => 'form-control', 'value' => Yii::$app->user->identity->info['lastname']])->label('Фамилия') ?>
                                                </div>
                                            </div>

                                            <div class="col-12 col-sm-4">
                                                <div class="form-group mb-0">
                                                    <?= $form->field($model, 'middlename')->textInput(['class' => 'form-control', 'value' => Yii::$app->user->identity->info['middlename']])->label('Отчество') ?>
                                                </div>
                                            </div>
                                            
                                            <div class="col-12 col-sm-6">
                                                <div class="form-group mb-0">
                                                    <?= $form->field($model, 'phone')->textInput(['class' => 'form-control', 'value' => Yii::$app->user->identity->info['contacts']['phones'][0]])->label('Номер телефона') ?>
                                                </div>
                                            </div>
                                            
                                            <div class="col-12 col-sm-6">
                                                <div class="form-group mb-0">
                                                    <?= $form->field($model, 'email')->textInput(['class' => 'form-control', 'value' => Yii::$app->user->identity->info['contacts']['emails'][0]])->label('E-mail') ?>
                                                </div>
                                            </div>

                                            <div class="col-12 col-sm-6">
                                                <div class="form-group mb-0 chosen-bg-light">
                                                    <?= $form->field($model, 'birthday')->textInput(['class' => 'form-control', 'onClick' => 'xCal(this, {lang: \'ru\'})', 'value' => Yii::$app->user->identity->info['birthday']])->label('Дата рождения') ?>
                                                </div>
                                            </div>
                                            
                                            <div class="col-12 col-sm-6">
                                                <div class="form-group mb-0">
                                                <?=$form->field($model, 'sex')->dropDownList([
                                                        'Мужской'=>'Мужской', 
                                                        'Женский'=>'Женский'
                                                    ],
                                                    [
                                                        'class'=>'chosen-category-select form-control form-control-sm', 
                                                        'data-placeholder'=>'Все категории', 
                                                        'tabindex'=>'2',
                                                        'options' => [
                                                            Yii::$app->user->identity->info['sex'] => ['Selected' => true]
                                                        ]
                                                    ])
                                                    ->label('Пол');?>
                                                </div>
                                            </div>
                                            
                                            <div class="col-12 col-sm-12">
                                                <div class="form-group mb-0">
                                                    <?= $form->field($model, 'city')->textInput(['class' => 'form-control', 'value' => Yii::$app->user->identity->info['contacts']['city']])->label('Город') ?>
                                                    <?= $form->field($model, 'address')->textInput(['class' => 'form-control', 'value' => Yii::$app->user->identity->info['contacts']['address']])->label('Адрес') ?>
                                                </div>
                                            </div>
                                    
                                        </div>
                                        
                                        <hr class="mt-40 mb-40" />
                                        
                                        <h5 class="text-uppercase">Сменить пароль</h5>
                                        
                                        <div class="row gap-20">
                                            <div class="col-12 col-sm-12">
                                                <div class="form-group mb-0">
                                                    <?= $form->field($model, 'old_password')->passwordInput(['class' => 'form-control'])->label('Старый пароль') ?>
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-6">
                                                <div class="form-group mb-0">
                                                    <?= $form->field($model, 'new_password')->passwordInput(['class' => 'form-control'])->label('Новый пароль') ?>
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-6">
                                                <div class="form-group mb-0">
                                                    <?= $form->field($model, 'repeat_password')->passwordInput(['class' => 'form-control'])->label('Подтвердите пароль') ?>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-30"></div>
                                        
                                        <div class="row gap-10 mt-15 justify-content-center justify-content-md-start">
                                            <div class="col-auto">
                                                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
                                            </div>
                                            <div class="col-auto">
                                                <a href="<?=Url::to('user/index')?>" class="btn btn-secondary">Назад</a>
                                            </div>
                                        </div>
                                        
                                    </div>
                                <?php ActiveForm::end() ?>
                                </div>
                            
                            </div>
                            
                    </div>
                    
                </div>
                
            </div>
            
        </div>
        
    </div>

</section>

<?php
$this->registerJsFile( '/js/cssworld.ru-xcal.js', $options = ['position' => yii\web\View::POS_HEAD], $key = 'date_picker' );
$this->registerCssFile('/css/cssworld.ru-xcal.css');
?>