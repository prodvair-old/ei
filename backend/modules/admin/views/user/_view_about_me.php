<?php
/* @var $this yii\web\View */
/* @var $model common\models\db\User */

use yii\helpers\Url;
use sergmoro1\lookup\models\Lookup;
use common\components\Property;
use common\models\db\User;

?>
<div class="box-header with-border">
    <h3 class="box-title"><?= Yii::t('app', 'About Me') ?></h3>
</div>
<div class="box-body">
    <strong><?= Yii::t('app', 'Activity') ?></strong>
    <p class="text-muted">
        <?= Lookup::item(Property::PERSON_ACTIVITY, $model->profile->activity, true) ?>
    </p>

    <strong><?= Yii::t('app', 'Status') ?></strong>
    <p class="text-muted">
        <?= Lookup::item(Property::USER_STATUS, $model->status, true) ?>
    </p>

    <hr>

    <strong><?= Yii::t('app', 'Notifications') ?></strong>

    <p>
        <i class="fa fa-<?= $model->notification->new_picture ? 'check-' : '' ?>square-o margin-r-5"></i>
        <?= $model->notification->getAttributeLabel('new_picture') ?>
    </p>
    <p>
        <i class="fa fa-<?= $model->notification->new_report ? 'check-' : '' ?>square-o margin-r-5"></i>
        <?= $model->notification->getAttributeLabel('new_report') ?>
    </p>
    <p>
        <i class="fa fa-<?= $model->notification->price_reduction ? 'check-' : '' ?>square-o margin-r-5"></i>
        <?= $model->notification->getAttributeLabel('price_reduction') ?>
    </p>

    <hr>

    <strong><i class="fa fa-phone margin-r-5"></i> <?= Yii::t('app', 'Phone') ?></strong>

    <p class="text-muted"><?= ($phone = $model->profile->phone) ?: Yii::t('app', 'not defined') ?></p>

</div>
