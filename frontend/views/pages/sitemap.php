<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use frontend\components\sitemap\SitemapMain;

$this->title = 'Карта сайта';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sitemap">
    <h1><?= Html::encode($this->title) ?></h1>

    <?=SitemapMain::widget()?>



</div>
