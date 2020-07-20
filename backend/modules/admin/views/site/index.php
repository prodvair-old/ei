<?php

/* @var $this yii\web\View */

use backend\modules\admin\widgets\Stat;

$this->title = Yii::t('app', 'Dashboard ');
?>
<div class="site-index">
    <div class="row">
        <?= Stat::widget(['sid' => 'common']) ?>
    </div>
    <div class="row">
        <div class="col-md-8">
            <?= Stat::widget(['sid' => 'lot', 'user_dependent' => true]) ?>
        </div>
    </div>
</div>
