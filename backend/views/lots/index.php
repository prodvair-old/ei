<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yiister\adminlte\widgets\grid\GridView;
use yiister\adminlte\widgets\Box;
use yii\data\ActiveDataProvider;

use backend\models\UserAccess;

$this->params['h1'] = 'Список лотов';
$this->title = 'Список лотов';

$dataProvider = new ActiveDataProvider([
    'query' => $lots,
    'Pagination' => [
        'pageSize' => 20
    ]
]);

if (UserAccess::forManager('add')) {
    $title = '<a href="'.Url::to(['organization/add']).'" class="btn btn-success"><i class="fa fa-plus"></i> Добавить организацию</a>';
} else {
    $title = 'Список лотов';
}

?>
<div class="row">
    <div class="col-md-12">
        <?php $box = Box::begin(['title'=>$title]); ?>
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' => [
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => ((UserAccess::forManager('edit'))? '{update} ':'').'{link}'.((UserAccess::forManager('delete'))? ' {delete}':''),
                        'buttons' => [
                            'link' => function ($url,$model) {
                                return Html::a(
                                '<span class="fa fa-link text-success"></span>', 
                                $model->url);
                            },
                        ]
                    ],
                    [
                        'attribute' => 'id',
                        'format' => 'ntext',
                        'label' => 'ID',
                    ],
                    [
                        'attribute' => 'title',
                        'format' => 'ntext',
                        'label' => 'Название',
                    ],
                    [
                        'attribute' => 'city',
                        'format' => 'ntext',
                        'label' => 'Город',
                    ],
                    [
                        'attribute' => 'torg.publishedDate',
                        'format' => ['date', 'php:d.m.Y'],
                        'label' => 'Дата добавления',
                    ],

                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => ((UserAccess::forManager('edit'))? '{update} ':'').'{link}'.((UserAccess::forManager('delete'))? ' {delete}':''),
                        'buttons' => [
                            'link' => function ($url,$model) {
                                return Html::a(
                                '<span class="fa fa-link text-success"></span>', 
                                $model->link);
                            },
                        ]
                    ],
                ],
            ]); ?>
        <?php $box->end()?>
    </div>
</div>