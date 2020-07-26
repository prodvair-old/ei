<?php
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;

use common\components\Property;
use sergmoro1\lookup\models\Lookup;
use common\models\db\Invoice;

$this->title = Yii::t('app', 'Invoices');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class='row'>
    <div class='col-lg-12'>
        <div class='box model-index table-responsive'>
            <div class='box-body'>
                <?= GridView::widget([
                    'id' => 'model-grid',
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'layout' => "{items}\n{summary}\n{pager}",
                    'options' => ['class' => false],
                    'columns' => [
                        [
                            'attribute' => 'id',
                            'options' => ['style' => 'width:6%'],
                        ],
                        [
                            'attribute' => 'product',
                            'filter' => Lookup::items(Property::PRODUCT_TYPE, true),
                            'value' => function($data) {
                                return Lookup::item(Property::PRODUCT_TYPE, $data->product, true);
                            },
                        ],
                        [
                            'attribute' => 'sum',
                            'value' => function($data) {
                                return $data->getSum();
                            },
                        ],
                        [
                            'attribute' => 'paid',
                            'filter' => Invoice::getPaidVariants(),
                            'value' => function($data) {
                                return $data->getPaid();
                            },
                        ],
                        [
                            'attribute' => 'created_at',
                            'value' => function($data) {
                                return date('d.m.Y', $data->created_at);
                            },
                        ],

                        [
                            'class' => 'yii\grid\ActionColumn',
                            'template' => ('{view}{delete}'), 
                            'options' => ['style' => 'width:6%'],
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>
