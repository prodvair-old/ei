<?php

/* @var $this yii\web\View */
/* @var $statistic array */

$this->title = Yii::t('app', 'Dashboard ');
?>
<div class='site-index'>
    <div class='row'>
        <?php foreach($statistic as $caption => $box): ?>
        <div class='col-md-3 col-sm-6 col-xs-12'>
            <div class='info-box'>
                <span class='info-box-icon bg-<?= $box['color'] ?>'><i class='fa fa-<?= $box['icon'] ?>'></i></span>

                <div class='info-box-content'>
                    <span class='info-box-text'><?= Yii::t('app', $caption) ?></span>
                    <span class='info-box-number'><?= number_format($box['value'], 0, '', ' '); ?></span>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
