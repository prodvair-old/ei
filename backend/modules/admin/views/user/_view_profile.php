<?php
/* @var $this yii\web\View */
/* @var $model common\models\db\User */

use yii\helpers\Url;
use sergmoro1\lookup\models\Lookup;
use common\components\Property;

?>
<div class='box-body box-profile'>
    <?= $model->getAvatar(['class' => 'profile-user-img img-responsive img-circle']) ?>
    <h3 class='profile-username text-center'><?= $model->fullName ?></h3>
    <p class='text-muted text-center'><?= Lookup::item(Property::USER_ROLE, $model->role, true) ?></p>

    <ul class='list-group list-group-unbordered'>
        <li class='list-group-item'>
            <b><?= Yii::t('app', 'Wish lots') ?></b>
            <a class='pull-right'><?= $model->wishLotCount ?></a>
        </li>
        <li class='list-group-item'>
            <b><?= Yii::t('app', 'Reports') ?></b>
            <a class='pull-right'><?= $model->reportCount ?></a>
        </li>
    </ul>

    <a href='<?= Url::to(['user/update', 'id' => $model->id]) ?>' class='btn btn-primary btn-block'>
        <b><span class='glyphicon glyphicon-pencil'></span> <?= Yii::t('app', 'Update') ?></b>
    </a>
</div>
