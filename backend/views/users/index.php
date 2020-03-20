<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\grid\GridView;
use insolita\wgadminlte\LteBox;
use insolita\wgadminlte\LteConst;
use yii\data\ActiveDataProvider;

use backend\models\UserAccess;

$this->params['h1'] = 'Список пользователей';
$this->title = 'Список пользователей';

$dataProvider = new ActiveDataProvider([
    'query' => $users,
    'Pagination' => [
        'pageSize' => 15
    ]
]);
?>
<?php LteBox::begin(['type'=>LteConst::TYPE_DEFAULT]);?>
        <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => [
                        [
                            'attribute' => 'id',
                            'format' => 'ntext',
                            'label' => 'ID',
                        ],
                        [
                            'attribute' => 'username',
                            'format' => 'ntext',
                            'label' => 'Имя пользователя',
                        ],
                        [
                            'attribute' => 'info',
                            'format' => 'ntext',
                            'label' => 'ФИО',
                            'value' => function ($model) {
                                return $model->getFullName();
                            }
                        ],
                        [
                            'attribute' => 'role',
                            'format' => 'ntext',
                            'label' => 'Роль',
                        ],
                        [
                            'attribute' => 'status',
                            'format' => 'ntext',
                            'label' => 'Статус',
                            'value' => function ($model) {
                                if (!empty($model->email_hash)) {
                                    return 'Не подтверждён';
                                }
                                return ($model->status)? 'Активен' : 'Заблокирован';
                            }
                        ],
                        [
                            'attribute' => 'created_at',
                            'format' => ['date', 'php:d.m.Y'],
                            'label' => 'Дата регистрации',
                        ],
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'template' => ((UserAccess::forAdmin('users', 'edit'))? '{update} ':'').' '.((UserAccess::forSuperAdmin('users', 'delete'))? ' {delete}':''),
                            'buttons' => [
                                'delete' => function ($url,$model) {
                                    return Html::a(
                                    '<span class="fa fa-trash-o text-danger"></span>', 
                                    $url, ['aria-label' => 'Удалить', 'title' => 'Удалить', 'data-pjax'=>'1', 'data-confirm' => 'Вы уверены, что хотите удалить этого пользователя?', 'data-method' => 'post']);
                                },
                            ]
                        ],
                    ],
                ]); ?>
<?php LteBox::end()?>
