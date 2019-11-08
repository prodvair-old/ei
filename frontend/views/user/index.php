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
                                    <img src="img/image-man/01.jpg" alt="Image" />
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
                    
                        <h3 class="heading-title"><span>My <span class="font200"> profile</span></span></h3>
                        
                        <div class="clear"></div>

                        <form>
                        
                            <div class="row gap-30">
                            
                                <div class="col-6 col-sm-5 col-md-4 col-lg-4 order-lg-last">
                                
                                    <div class="avatar-upload">
                                        <img class="profile-pic d-block" src="img/image-man/01.jpg" alt="avatar" />
                                        <div class="upload-button text-secondary line-1">
                                            <div>
                                            <i class="fas fa-upload text-primary"></i>
                                            <span class="d-block font12 text-uppercase font700 mt-10 text-primary">Maximum file size:<br/>250 mb</span>
                                            </div>
                                        </div>
                                        <input class="file-upload" type="file" accept="image/*"/>
                                        <div class="labeling">
                                            <i class="fas fa-upload"></i> Change avatar
                                        </div>
                                    </div>
                                
                                </div>
                                
                                <div class="col-12 col-md-12 col-lg-8">
                                
                                    <div class="col-inner">
                                    
                                        <div class="row gap-20">
                                        
                                            <div class="col-12 col-sm-6">
                                                <div class="form-group mb-0">
                                                    <label>First Name</label>
                                                    <input type="text" class="form-control" value="Christine">
                                                </div>
                                            </div>
                                            
                                            <div class="col-12 col-sm-6">
                                                <div class="form-group mb-0">
                                                    <label>Last Name</label>
                                                    <input type="text" class="form-control" value="Gateau">
                                                </div>
                                            </div>
                                            
                                            <div class="col-12 col-sm-6">
                                                <div class="form-group mb-0 chosen-bg-light">
                                                    <label>Born</label>
                                                    <div class="row gap-5">
                                                        <div class="col-4">
                                                            <select data-placeholder="day" class="chosen-the-basic form-control" tabindex="2">
                                                                <option></option>
                                                                <option value="1">01</option>
                                                                <option value="2" selected>02</option>
                                                                <option value="3">03</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-4">
                                                            <select data-placeholder="month" class="chosen-the-basic form-control" tabindex="2">
                                                                <option></option>
                                                                <option value="1">Jan</option>
                                                                <option value="2" selected>Feb</option>
                                                                <option value="3">Mar</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-4">
                                                            <select data-placeholder="year" class="chosen-the-basic form-control" tabindex="2">
                                                                <option></option>
                                                                <option value="1">1985</option>
                                                                <option value="2" selected>1986</option>
                                                                <option value="3">1987</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-12 col-sm-6">
                                                <div class="form-group mb-0">
                                                    <label>Email</label>
                                                    <input type="email" class="form-control" value="myemail@gmail.com">
                                                </div>
                                            </div>
                                            
                                            <div class="col-12 col-sm-12">
                                                <div class="form-group mb-0">
                                                    <label>Street 1</label>
                                                    <input type="text" class="form-control" value="254">
                                                    <input type="text" class="form-control mt-5">
                                                </div>
                                            </div>
                                            
                                            <div class="col-12 col-sm-6">
                                                <div class="form-group mb-0">
                                                    <label>City/town</label>
                                                    <input type="text" class="form-control" value="Somewhere ">
                                                </div>
                                            </div>
                                            
                                            <div class="col-12 col-sm-6">
                                                <div class="form-group mb-0">
                                                    <label>Province/State</label>
                                                    <input type="text" class="form-control" value="Paris">
                                                </div>
                                            </div>
                                            
                                            <div class="col-12 col-sm-6">
                                                <div class="form-group mb-0">
                                                    <label>Zip Code</label>
                                                    <input type="text" class="form-control" value="35214">
                                                </div>
                                            </div>
                                            
                                            <div class="col-12 col-sm-6">
                                                <div class="form-group  mb-0 chosen-bg-light">
                                                    <label>Country</label>
                                                    <select data-placeholder="country" class="chosen-the-basic form-control" tabindex="2">
                                                        <option></option>
                                                        <option value="1">Thailand</option>
                                                        <option value="2" selected>France</option>
                                                        <option value="3">China</option>
                                                        <option value="4">Malaysia </option>
                                                        <option value="5">Italy</option>
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="col-12 col-sm-6">
                                                <div class="form-group mb-0">
                                                    <label>Phone Number</label>
                                                    <input type="text" class="form-control" value="+66-85-221-5489">
                                                </div>
                                            </div>
                                    
                                        </div>
                                        
                                        <hr class="mt-40 mb-40" />
                                        
                                        <h5 class="text-uppercase">Social medias</h5>
                                        
                                        <div class="row gap-20">
                                            <div class="col-12 col-sm-8">
                                                <div class="form-group mb-0">
                                                    <label><i class="fab fa-facebook mr-5"></i> Facebook</label>
                                                    <input type="text" class="form-control" value="https://www.facebook.com/user">
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-8">
                                                <div class="form-group mb-0">
                                                    <label><i class="fab fa-twitter mr-5"></i> Twitter</label>
                                                    <input type="text" class="form-control" value="https://www.twitter.com/user">
                                                </div>
                                            </div>
                                            <div class="col-12 col-sm-8">
                                                <div class="form-group mb-0">
                                                    <label><i class="fab fa-google-plus mr-5"></i> Google+</label>
                                                    <input type="text" class="form-control" value="https://www.google.com/plus/user">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-30"></div>
                                        
                                        <div class="row gap-10 mt-15 justify-content-center justify-content-md-start">
                                            <div class="col-auto">
                                                <a href="#" class="btn btn-primary">Save</a>
                                            </div>
                                            <div class="col-auto">
                                                <a href="#" class="btn btn-secondary">Cancel</a>
                                            </div>
                                        </div>
                                        
                                    </div>
                                
                                </div>
                            
                            </div>
                            
                        </form>
                        
                    </div>
                    
                </div>
                
            </div>
            
        </div>
        
    </div>

</section>