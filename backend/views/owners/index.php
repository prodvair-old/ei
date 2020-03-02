<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\grid\GridView;
use insolita\wgadminlte\LteBox;
use insolita\wgadminlte\LteConst;
use yii\data\ActiveDataProvider;

use backend\models\UserAccess;

$this->params['h1'] = 'Список организации';
$this->title = 'Список организации';

$dataProvider = new ActiveDataProvider([
    'query' => $owners,
    'Pagination' => [
        'pageSize' => 15
    ]
]);
?>
<? if (UserAccess::forManager('lots', 'add')) { ?>
    <div class="box-header"><a href="<?=Url::to(['owners/create'])?>" class="btn btn-success"><i class="fa fa-plus"></i> Добавить организацию</a></div>
<? } ?>
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
                            'attribute' => 'logo',
                            'label' => 'Логотип',
                            'format' => 'html',    
                            'value' => function ($model) {
                                return Html::img(Yii::$app->params['frontLink'].$model->logo,
                                    ['width' => '70px']);
                            },
                        ],
                        [
                            'attribute' => 'title',
                            'format' => 'ntext',
                            'label' => 'Название',
                        ],
                        [
                            'attribute' => 'email',
                            'format' => 'ntext',
                            'label' => 'E-Mail',
                        ],
                        [
                            'attribute' => 'type',
                            'format' => 'ntext',
                            'label' => 'Тип организации',
                            'value' => function ($model) {
                                if ($model->type = 'bank') {
                                    return 'Банк';
                                } else {
                                    return 'Компания';
                                }
                            }
                        ],
                        [
                            'attribute' => 'createdAt',
                            'format' => ['date', 'php:d.m.Y'],
                            'label' => 'Дата добавления',
                        ],
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'template' => ((UserAccess::forManager('lots', 'edit'))? '{update} ':'').'{link}'.((UserAccess::forManager('lots', 'delete'))? ' {delete}':''),
                            'buttons' => [
                                'link' => function ($url,$model) {
                                    return Html::a(
                                    '<span class="fa fa-eye text-success"></span>', 
                                    Yii::$app->params['frontLink'].'/'.$model->linkEi, ['target' => '_blank']);
                                },
                                'delete' => function ($url,$model) {
                                    return Html::a(
                                    '<span class="fa fa-trash-o text-danger"></span>', 
                                    $url, ['aria-label' => 'Удалить', 'title' => 'Удалить', 'data-pjax'=>'1', 'data-confirm' => 'Вы уверены, что хотите удалить эту организацию?', 'data-method' => 'post']);
                                },
                            ]
                        ],
                    ],
                ]); ?>
<?php LteBox::end()?>