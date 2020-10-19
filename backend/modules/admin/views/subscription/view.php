<?php

/* @var $this yii\web\View */

/* @var $model Subscription */

use common\components\Property;
use common\models\db\Subscription;
use sergmoro1\lookup\models\Lookup;

$this->title = Yii::t('app', 'View');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Subscription'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->id;

?>
<div class='row'>
    <div class='lot-common col-lg-8'>
        <div class='box box-primary'>
            <div class='box-header'>
                <h3 class='box-title'><?= Yii::t('app', 'Subscription') ?></h3>
            </div>
            <div class='box-body'>
                <p class="lead"><?= $model->id ?></p>
                <div class="table-responsive">
                    <table class="table">
                        <tbody>
                        <tr>
                            <th><?= $model->user->getAvatar(['class' => 'img-circle']) ?></th>
                            <td><?= $model->user->fullName ?></td>
                        </tr>
                        <!--                            <tr>-->
                        <!--                                <th style="width:30%">-->
                        <? //= Yii::t('app', 'Product') ?><!--</th>-->
                        <!--                                <td>-->
                        <? //= Lookup::item(Property::PRODUCT_TYPE, $model->product, true) ?><!--</td>-->
                        <!--                            </tr>-->
                        <tr>
                            <th><?= Yii::t('app', 'Invoice') ?></th>
                            <td>
                                <?php if ($model->invoice->paid) : ?>
                                    <?= Yii::t('app', 'Invoice paid') ?>
                                <?php else: ?>
                                    <?= Yii::t('app', 'Invoice not paid') ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th><?= Yii::t('app', 'From at') ?></th>
                            <td><?=  date('d.m.Y', $model->from_at); ?></td>
                        </tr>
                        <tr>
                            <th><?= Yii::t('app', 'Till at') ?></th>
                            <td><?=  date('d.m.Y', $model->till_at); ?></td>
                        </tr>
                        <tr>
                            <th><?= Yii::t('app', 'Tariff name') ?></th>
                            <td><?= $model->tariff->name ?></td>
                        </tr>
                        <tr>
                            <th><?= Yii::t('app', 'Tariff description') ?></th>
                            <td><?= $model->tariff->description ?></td>
                        </tr>
                        <tr>
                            <th><?= Yii::t('app', 'Tariff fee') ?></th>
                            <td><?= $model->tariff->fee ?></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div>
            <div>
