<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use frontend\components\faq\faqFormAskQuestion;
use frontend\components\faq\faqQuestion;
use frontend\components\faq\faqSearch;

$this->title = 'FAQ';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-faq">
    <div class="container text-center">
        <h1 class="page-wrapper pb-20"><?= Html::encode($this->title) ?></h1>
    </div>

    <section class="page-wrapper pt-50 pb-50">
        <?=faqQuestion::widget()?>
    </section>

    <section class="page-wrapper pt-50 pb-50">
        <?=faqFormAskQuestion::widget()?>
    </section>

</div>
