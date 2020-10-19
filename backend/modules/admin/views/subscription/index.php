<?php
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

/* @var $searchModel SubscriptionSearch */

use backend\modules\admin\models\SubscriptionSearch;
use yii\grid\GridView;

$this->title = Yii::t('app', 'Subscriptions');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class='row'>
    <div class='col-lg-12'>
        <div class='box model-index table-responsive'>
            <div class='box-body'>
                <?= GridView::widget([
                    'id'           => 'model-grid',
                    'dataProvider' => $dataProvider,
                    'filterModel'  => $searchModel,
                    'layout'       => "{items}\n{summary}\n{pager}",
                    'options'      => ['class' => false],
                    'columns'      => [
                        [
                            'attribute' => 'id',
                            'options'   => ['style' => 'width:6%'],
                        ],
                        [
                            'attribute' => 'user_id',
                        ],
                        [
                            'attribute' => 'tariff_id',
                        ],
                        [
                            'attribute' => 'invoice_id',
                        ],
                        [
                            'attribute' => 'invoice_id',
                            'value'     => function ($data) {
                                return ($data->invoice->paid) ? 'paid' : 'not paid';
                            },
                            'label' => Yii::t('app', 'Invoice')
                        ],
                        [
                            'attribute' => 'from_at',
                            'value'     => function ($data) {
                                return date('d.m.Y', $data->created_at);
                            },
                        ],
                        [
                            'attribute' => 'till_at',
                            'value'     => function ($data) {
                                return date('d.m.Y', $data->created_at);
                            },
                        ],
                        [
                            'attribute' => 'created_at',
                            'value'     => function ($data) {
                                return date('d.m.Y', $data->created_at);
                            },
                        ],

                        [
                            'class'    => 'yii\grid\ActionColumn',
                            'template' => ('{view}{delete}'),
                            'options'  => ['style' => 'width:6%'],
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>
