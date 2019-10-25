<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use frontend\components\contact\ContactData;
use frontend\components\contact\ContactFindSociety;
use frontend\components\contact\ContactFormSendMessage;
use frontend\components\contact\ContactMap;
use frontend\components\contact\ContactTitleCategory;

$this->title = 'Contact';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-contact">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>This is the About page. You may modify the following file to customize its content:</p>

    <code><?= __FILE__ ?></code>

    <div class="main-wrapper scrollspy-container">

        <section class="page-wrapper page-result pb-0">
            
            <?=ContactTitleCategory::widget()?>

            <div class="container pv-60">

                <?=ContactMap::widget()?>

                <div class="mb-50"></div>

                    <div class="row gap-50 gap-lg-0">
                            
                        <div class="col-12 col-lg-5 col-xl-4">
                
                            <h4 class="heading-title"><span>Drop us <span class="font200">a message:</span></span></h4>
            
                            <?=ContactFormSendMessage::widget()?>

                        </div>
                
                        <div class="col-12 col-lg-6 ml-auto">
                                
                            <h4 class="heading-title"><span>Contact <span class="font200">information:</span></span></h4>
                            <p class="post-heading">Excited him now natural saw passage offices you minuter. At by asked being court hopes</p>
                    
                            <?=ContactData::widget()?>

                            <div class="mb-50"></div>
                
                            <?=ContactFindSociety::widget()?>

                        </div>

                    </div>

                </div>

            </div>

        </section>

    </div>
    
</div>
