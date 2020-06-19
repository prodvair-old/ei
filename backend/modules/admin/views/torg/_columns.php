<?php
/**
 * Определение колонок для табличного вывода Торгов - torg/index, torg/more.
 */

use yii\helpers\Url;
use yii\helpers\Html;
use common\components\Property;
use sergmoro1\lookup\models\Lookup;
use common\models\db\Torg;

return [
    [
        'attribute' => 'id',
        'format' => 'raw',
        'options' => ['style' => 'width:7%;'],
        'value' => function($data) {
             return $data['id'] . ' ' . ($data['property_id'] == Torg::PROPERTY_ZALOG
                ? ' ' . Html::a('<i class="fa fa-money"></i>', ['lot/create', 'torg_id' => $data['id']], ['title' => Yii::t('app', 'Add lot')])
                : ''
            );
        }
    ],
    [
        'attribute' => 'msg_id',
        'options' => ['style' => 'width:15%'],
        'value' => function($data) {
            return strlen($data['msg_id']) > 12 ? substr($data['msg_id'], 0, 11) . '...' : $data['msg_id'];
        },
    ],
    [
        'attribute' => 'property',
        'filter' => Lookup::items(Property::TORG_PROPERTY, true),
        'value' => function($data) {
            return $data['property'];
        },
    ],
    [
        'attribute' => 'offer',
        'filter' => Lookup::items(Property::TORG_OFFER, true),
        'value' => function($data) {
            return $data['offer'];
        },
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
    ],
    [
        'header' => Yii::t('app', 'Stage'),
        'value' => function($data) {
            return Torg::getStage($data['id'], $data['property_id']);
        },
    ],

    [
        'class' => 'yii\grid\ActionColumn',
        'template' => '{view}{update}{delete}', 
        'options' => ['style' => 'width:6%'],
        'buttons' => [
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
