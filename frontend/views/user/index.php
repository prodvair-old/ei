<?
use yii\widgets\Breadcrumbs;
use yii\helpers\Html;
use yii\helpers\Url;

$name = (\Yii::$app->user->identity->info['firstname'] || \Yii::$app->user->identity->info['lastname'])? \Yii::$app->user->identity->info['firstname'].' '.\Yii::$app->user->identity->info['lastname'] : \Yii::$app->user->identity->info['contacts']['emails'][0];
$this->title = "Профиль – $name";
$this->params['breadcrumbs'][] = [
    'label' => 'Профиль',
    'template' => '<li class="breadcrumb-item active" aria-current="page">{link}</li>',
    'url' => ['user/index']
]
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
                
                <aside class="sticky-kit sidebar-wrapper">

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
                            
                            <nav class="menu-vertical-01 mt-20">
                    
                                <ul>
                                    
                                    <li class="active"><a href="<?=Url::to('user/index')?>">Профиль</a></li>
                                    <li><a href="<?=Url::to('user/wish_list')?>">Избранные</a></li>
                                    <li><a href="<?=Url::to('user/setting')?>">Настройки</a></li>
                                    <li><a href="<?=Url::to(['site/logout'])?>">Выйти</a></li>
                                    
                                </ul>

                            </nav>
                            
                            <!-- <p class="font-sm mt-20">Your last logged-in: <span class="text-primary font700">4 hours ago</span></p> -->

                        </div>
                        
                    </div>
                
                </aside>
                
            </div>
            
            <div class="col-12 col-lg-9">
                
                <div class="content-wrapper">
                    
                    <div class="form-draft-payment">
                    
                        <h3 class="heading-title"><span>Мой <span class="font200"> профиль</span></span></h3>
                        
                        <div class="clear"></div>

                        <div>
                        <a href="#" class="toast-js"> Уведомления</a>
                        </div>

                        <div class="toast">
                            Текст сообщения...
                            <button class="toast__close" type="button">&times;</button>
                        </div>
                        
                        
                    </div>
                    
                </div>
                
            </div>
            
        </div>
        
    </div>

</section>