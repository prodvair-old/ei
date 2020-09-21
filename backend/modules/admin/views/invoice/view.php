<?php

/* @var $this yii\web\View */
/* @var $model common\models\db\Invoice */

use common\components\Property;
use sergmoro1\lookup\models\Lookup;

$this->title = Yii::t('app', 'View');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Invoices'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->numberDate;

?>
<div class='row'>
    <div class='lot-common col-lg-8'>
        <div class='box box-primary'>
            <div class='box-header'>
                <h3 class='box-title'><?= Yii::t('app', 'Invoice') ?></h3>
            </div>
            <div class='box-body'>
                <p class="lead"><?= $model->numberDate ?></p>
                <div class="table-responsive">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th><?= $model->user->getAvatar(['class' => 'img-circle']) ?></th>
                                <td><?= $model->user->fullName ?></td>
                            </tr>
                            <tr>
                                <th style="width:30%"><?= Yii::t('app', 'Product') ?></th>
                                <td><?= Lookup::item(Property::PRODUCT_TYPE, $model->product, true) ?></td>
                            </tr>
                            <tr>
                                <th><?= Yii::t('app', 'Name') . ' | ' . Yii::t('app', 'Title') ?></th>
                                <td><?= $model->productLink ?></td>
                            </tr>
                            <tr>
                                <th><?= Yii::t('app', 'Sum') ?></th>
                                <td><?= $model->getSum() ?></td>
                            </tr>
                            <tr>
                                <th><?= Yii::t('app', 'Paid') ?></th>
                                <td><?= $model->getPaid() ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <div>
<div>
