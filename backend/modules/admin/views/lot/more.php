<?php
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;

use common\components\Property;
use common\models\db\Category;
use sergmoro1\lookup\models\Lookup;

?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'layout' => "{items}\n{summary}",
    'options' => ['tag' => false],
    'columns' => [
        [
            'attribute' => 'id',
            'options' => ['style' => 'width:5%;'],
        ],
        [
            'attribute' => 'title',
            'options' => ['style' => 'width:25%;'],
        ],
        [
            'attribute' => 'status',
            'filter' => Lookup::items(Property::LOT_STATUS, true),
            'value' => function($data) {
                return Lookup::item(Property::LOT_STATUS, $data->status, true);
            },
            'options' => ['style' => 'width:10%;'],
        ],
        [
            'attribute' => 'reason',
            'filter' => Lookup::items(Property::LOT_REASON, true),
            'value' => function($data) {
                return Lookup::item(Property::LOT_REASON, $data->reason, true);
            },
            'options' => ['style' => 'width:10%;'],
        ],
        [
            'attribute' => 'property',
            'filter' => Lookup::items(Property::TORG_PROPERTY, true),
            'value' => function($data) {
                return Lookup::item(Property::TORG_PROPERTY, $data->torg->property, true);
            },
            'options' => ['style' => 'width:10%;'],
        ],

        [
            'attribute' => 'category_id',
            'filter' => Category::items(),
            'value' => function($data) {
                $c = count($data->categories);
                return $c > 0 ? ($data->categories[0]->name . ($c > 1 ? " (+$c)" : '')) : '-';
            },
            'options' => ['style' => 'width:15%;'],
        ],
        [
            'attribute' => 'start_price',
            'options' => ['style' => 'width:10%;'],
        ],
        [
            'attribute' => 'end_at',
            'value' => function($data) {
                return date('d.m.y', $data->torg->end_at);
            },
            'options' => ['style' => 'width:9%;'],
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{view}{update}{delete}', 
            'options' => ['style' => 'width:6%;'],
        ],
    ],
]); ?>
