<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use frontend\components\contact\ContactData;
use frontend\components\contact\ContactFindSociety;
use frontend\components\contact\ContactFormSendMessage;
use frontend\components\contact\ContactMap;
use frontend\components\contact\ContactTitleCategory;

$this->title = 'Контакты';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="main-wrapper scrollspy-container">

    <section class="page-wrapper page-result pb-0">
        
        <?=ContactTitleCategory::widget()?>

        <div class="container pv-60">

            <h1 class="text-center pb-10"><?= Html::encode($this->title) ?></h1>

            <!-- <?=ContactMap::widget()?> -->

            <div class="mb-50"></div>

                <div class="row gap-50 gap-lg-0">
                        
                    <div class="col-12 col-lg-5 col-xl-4">
            
                        <h4 class="heading-title"><span>Написать <span class="font200">нам:</span></span></h4>
        
                        <?=ContactFormSendMessage::widget()?>

                    </div>
            
                    <div class="col-12 col-lg-6 ml-auto">
                            
                        <h4 class="heading-title"><span>Контактная <span class="font200">информация:</span></span></h4>
                        <p class="post-heading mb-10">ОБЩЕСТВО С ОГРАНИЧЕННОЙ ОТВЕТСТВЕННОСТЬЮ "ЕДИНЫЙ ИНФОРМАТОР"</p>
                
                        <?=ContactData::widget()?>

                        <div class="mb-50"></div>
            
                        <?=ContactFindSociety::widget(['vk'=>'dsfsdf', 'google'=>'gadawd'])?>

                    </div>

                </div>

            </div>

        </div>

    </section>

</div>
