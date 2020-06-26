<?php
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;

use common\components\Property;
use sergmoro1\lookup\models\Lookup;
use common\components\Name;

$this->title = Yii::t('app', 'Orders');
$this->params['breadcrumbs'][] = $this->title;

$script = <<<JS
$('body').on('click', '.btn.search-submit', function(){ $("#model-grid").yiiGridView("applyFilter"); });
JS;

$this->registerJs($script);
?>

<div class='row'>
    <div class='col-lg-12'>
        <div class='box model-index table-responsive'>
            <div class='box-header'>
                <?= Html::submitButton(Yii::t('app', 'Find'), ['class' => 'btn btn-primary search-submit']) ?>
            </div>

            <div class='box-body'>
                <?= GridView::widget([
                    'id' => 'model-grid',
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'filterOnFocusOut' => false,
                    'layout' => "{items}\n{summary}\n{pager}",
                    'options' => ['class' => false],
                    'columns' => [
                        [
                            'attribute' => 'lot_id',
                            'options' => ['style' => 'width:6%'],
                        ],
                        'title',
                        'username',
                        [
                            'attribute' => 'full_name',
                            'filter' => true,
                            'value' => function($data) {
                                return Name::getFull($data['first_name'], $data['last_name']);
                            },
                        ],
                        'phone',
                        [
                            'attribute' => 'bid_price',
                            'value' => function($data) {
                                return number_format($data['bid_price'], 0, '', ' ');
                            },
                            'options' => ['style' => 'width:8%'],
                        ],
                        [
                            'attribute' => 'created_at',
                            'value' => function($data) {
                                return date('d.m.Y H:i', $data['created_at']);
                            },
                        ],

                        [
                            'class' => 'yii\grid\ActionColumn',
                            'template' => ('{delete}'), 
                            'options' => ['style' => 'width:6%'],
                        ],
                    
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>
