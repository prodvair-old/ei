<?php
/**
 * Определение колонок для табличного вывода Лотов - lot/index, lot/more.
 */

use yii\helpers\Url;
use yii\helpers\Html;
use common\components\Property;
use sergmoro1\lookup\models\Lookup;
use common\models\db\Torg;

return [
    [
        'attribute' => 'id',
        'options' => ['style' => 'width:5%;'],
    ],
    [
        'attribute' => 'msg_id',
        'options' => ['style' => 'width:10%;'],
    ],
    [
        'attribute' => 'property',
        'filter' => Lookup::items(Property::TORG_PROPERTY, true),
        'value' => function($data) {
            return $data['property'];
        },
        'options' => ['style' => 'width:15%;'],
    ],
    [
        'attribute' => 'offer',
        'filter' => Lookup::items(Property::TORG_OFFER, true),
        'value' => function($data) {
            return $data['offer'];
        },
        'options' => ['style' => 'width:15%;'],
    ],
    [
        'attribute' => 'started_at',
        'value' => function($data) {
            return date('d.m.Y', $data['started_at']);
        },
        'options' => ['style' => 'width:7%;'],
    ],
    [
        'attribute' => 'end_at',
        'value' => function($data) {
            return date('d.m.Y', $data['end_at']);
        },
        'options' => ['style' => 'width:7%;'],
    ],
    [
        'header' => Yii::t('app', 'Lots count'),
        'value' => function($data) {
            return $data['lot_count'];
        },
        'options' => ['style' => 'width:5%;'],
    ],
    [
        'header' => Yii::t('app', 'Publisher'),
        'value' => function($data) {
            $company = $data['bankrupt_company'] . $data['owner_company'] . $data['other_company'];
            $person = $data['bankrupt_person'] . $data['owner_person'] . $data['other_person'];
            return $company . ($company && $person ? ', ' . $person : $person);
        },
        'options' => ['style' => 'width:5%;'],
    ],

    [
        'class' => 'yii\grid\ActionColumn',
        'template' => ('{view}{update}{delete} {lot}'), 
        'options' => ['style' => 'width:6%'],
        'buttons' => [
            'lot' => function ($url, $model) {
                return $model['property_id'] == Torg::PROPERTY_ZALOG
                    ? Html::a(Yii::$app->params['icons']['lot'], Url::to(['lot/create', 'torg_id' => $model['id']]), ['title' => Yii::t('app', 'Add lot')])
                    : '';
            },
            'update' => function ($url, $model) {
                return $model['property_id'] == Torg::PROPERTY_ZALOG
                    ? Html::a(Yii::$app->params['icons']['pencil'], $url)
                    : '';
            },
            'delete' => function ($url, $model) {
                return $model['property_id'] == Torg::PROPERTY_ZALOG
                    ? Html::a(Yii::$app->params['icons']['trash'], $url)
                    : '';
            },
        ],
    ],
];
