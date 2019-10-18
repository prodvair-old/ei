<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use frontend\components\service\ServiceOur;
use frontend\components\service\ServiceHelp;
use frontend\components\site\OurFeatures;
use frontend\components\site\SiteFooterImage;

$this->title = 'Service';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-service">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>This is the About page. You may modify the following file to customize its content:</p>

    <code><?= __FILE__ ?></code>
    <div class="container pv-100">
        <div class="section-title w-100 text-center">
            <h2><span><span>Our</span> services</span></h2>
            <p>Considered an invitation do introduced sufficient understood instrument it.</p>
        </div>

        <div class="row equal-height cols-1 cols-sm-2 cols-xl-4 gap-30 mb-40">
            <?=ServiceOur::widget()?>
        </div>

        <div class="mb-100"></div>

        <?=ServiceHelp::widget()?>
        
    </div>

    <div class="container">

        <div class="section-title text-center w-100">
            <h2><span><span>Our</span> features</span></h2>
            <p>He doors quick child an point</p>
        </div>

        <div class="row cols-1 cols-sm-2 cols-lg-3 gap-20 gap-md-40">
            <?=OurFeatures::widget()?>
        </div>

        <div class="mb-100"></div>

        <?=SiteFooterImage::widget()?>
    </div>
</div>
