<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use frontend\components\sitemap\SitemapMain;

$this->title = 'Карта сайта';
$this->params['breadcrumbs'][] = $this->title;
?>
<section class="sitemap">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="container">
        <?=SitemapMain::widget()?>
    </div>
</section>
