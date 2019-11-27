<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use frontend\components\service\ServiceOur;
use frontend\components\service\ServiceHelp;
use frontend\components\service\ServiceDescription;
use frontend\components\site\OurFeatures;
use frontend\components\site\SiteFooterImage;

$this->title = 'Услуги';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-service">

    <div class="container pt-100">
        <div class="section-title w-100 text-center pt-60">
            <h1>Воспользуйтесь нашими услугами</h1>
            <p>Мы предостовляем только качественные услуги</p>
        </div>

        <div class="row equal-height cols-1 cols-sm-2 cols-xl-4 gap-30 mb-40">
            <?=ServiceOur::widget()?>
        </div>
        
        <div class="mb-100"></div>

        <div class="row">
            <?=ServiceDescription::widget()?>
        </div>

        <div class="mb-100"></div>

        <?=ServiceHelp::widget()?>
        
    </div>

    <div class="container">

        <div class="section-title text-center w-100">
            <h2>Наши <span class="lowercase">преимущества</span></h2>
            <p>Вот почему выбирают именно нас</p>
        </div>

        <div class="row cols-1 cols-sm-2 cols-lg-3 gap-20 gap-md-40">
            <?=OurFeatures::widget()?>
        </div>

        <div class="mb-100"></div>

        <?=SiteFooterImage::widget()?>
    </div>
</div>
