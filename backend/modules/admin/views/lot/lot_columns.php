<?php
/**
 * Определение колонок для табличного вывода Лотов - lot/index, lot/more.
 */

use yii\helpers\Html;
use common\components\Property;
use sergmoro1\lookup\models\Lookup;
use common\models\db\Category;

return [
    [
        'attribute' => 'id',
        'options' => ['style' => 'width:8%;'],
        'format' => 'raw',
        'value' => function($data) {
             return $data['id'] . ' ' .
                Html::a('<i class="fa fa-book"></i>', ['report/create', 'lot_id' => $data['id']], ['title' => Yii::t('app', 'Add report')]);
        }
    ],
    [
        'attribute' => 'title',
        'options' => ['style' => 'width:20%;'],
    ],
    [
        'attribute' => 'status',
        'filter' => Lookup::items(Property::LOT_STATUS, true),
        'value' => function($data) {
            return $data['status'];
        },
        'options' => ['style' => 'width:10%;'],
    ],
    [
        'attribute' => 'reason',
        'filter' => Lookup::items(Property::LOT_REASON, true),
        'value' => function($data) {
            return $data['reason'];
        },
        'options' => ['style' => 'width:10%;'],
    ],
    [
        'attribute' => 'property',
        'filter' => Lookup::items(Property::TORG_PROPERTY, true),
        'value' => function($data) {
            return $data['property'];
        },
        'options' => ['style' => 'width:10%;'],
    ],

    [
        'attribute' => 'category_id',
        'filter' => '<select id="lot-category_id" class="form-control" name="LotSearch[category_id]"></select>',
        'value' => function($data) {
            return $data['category_name'];
        },
        'options' => ['style' => 'width:20%;'],
    ],
    [
        'attribute' => 'start_price',
        'options' => ['style' => 'width:10%;'],
    ],
    [
        'attribute' => 'end_at',
        'value' => function($data) {
            return date('d.m.y', $data['end_at']);
        },
        'options' => ['style' => 'width:9%;'],
    ],
    [
        'class' => 'yii\grid\ActionColumn',
        'template' => '{view}{update}{delete}', 
        'options' => ['style' => 'width:6%;'],
    ],
];
