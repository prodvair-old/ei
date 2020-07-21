<?php

/* @var $this yii\web\View */
/* @var $common JSON array */

use common\models\db\Stat;

?>
<?php foreach($common as $name => $var): ?>
    <?php if (!($var['value'] === -1)): ?>
        <div class='col-md-3 col-sm-6 col-xs-12'>
            <div class='info-box'>
                <span class='info-box-icon bg-<?= $var['color'] ?>'><i class='fa fa-<?= $var['icon'] ?>'></i></span>

                <div class='info-box-content'>
                    <span class='info-box-text'><?= Yii::t('app', $var['caption']) ?></span>
                    <span class='info-box-number'>
                        <?= Stat::format($var['value']) ?>
                    </span>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php endforeach; ?>
