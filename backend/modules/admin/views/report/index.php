<?php
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;

use sergmoro1\lookup\models\Lookup;
use common\components\Property;
use common\components\Name;

$this->title = Yii::t('app', 'Reports');
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
                            'attribute' => 'id',
                            'options' => ['style' => 'width:6%'],
                        ],
                        [
                            'attribute' => 'lot_id',
                            'format' => 'raw',
                            'value' => function($data) {
                                return Html($data['lot_id'], ['lot/view', 'id' => $data['lot_id']]);
                            },
                        ],
                        'title',
                        'cost',
                        [
                            'attribute' => 'status',
                            'filter' => Lookup::items(Property::REPORT_STATUS, true),
                            'value' => function($data) {
                                return $data['status'];
                            },
                        ],
                        [
                            'header' => Yii::t('app', 'Property'),
                            'value' => function($data) {
                                return $data['property'];
                            },
                        ],
                        'username',
                        [
                            'header' => Yii::t('app', 'Full name'),
                            'value' => function($data) {
                                return Name::getFull($data['first_name'], $data['last_name']);
                            },
                        ],

                        [
                            'class' => 'yii\grid\ActionColumn',
                            'template' => ('{view}{update}{delete}'), 
                            'options' => ['style' => 'width:6%'],
                        ],
                    
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>
