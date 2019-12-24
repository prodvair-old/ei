<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

use frontend\components\faq\FaqFormAskQuestion;
use frontend\components\service\ServiceLot;

$this->title = 'Оценка лота';
$this->params['breadcrumbs'][] = $this->title;
?>

<section class="service-lot">
    <h1 class="pt-30"><?= Html::encode($this->title)?></h1>

    <div class="container">
        <?=ServiceLot::widget()?>
    </div>
</section>