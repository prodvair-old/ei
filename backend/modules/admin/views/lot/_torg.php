<?php

/* @var $this yii\web\View */
/* @var $torg common\models\db\Torg */

?>
<div class='row'>
    <div class='col-sm-3'>
        <?= $torg->msg_id ?>
    </div>
    <div class='col-sm-2'>
        <?= date('d.m.Y', $torg->started_at) ?>
    </div>
    <div class='col-sm-7'>
        <?= $torg->responsible->fullName ?>
    </div>
</div>
