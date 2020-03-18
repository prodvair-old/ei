<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\grid\GridView;
use insolita\wgadminlte\LteBox;
use insolita\wgadminlte\LteConst;
use yii\data\ActiveDataProvider;

use backend\models\UserAccess;

$this->params['h1'] = 'Список лотов';
$this->title = 'Список лотов';

$dataProvider = new ActiveDataProvider([
    'query' => $lots,
    'Pagination' => [
        'pageSize' => 15
    ]
]);
?>
<div class="box-header">
    <div class="row">
        <? if (UserAccess::forManager('lots', 'add') || UserAccess::forAgent('lots', 'add')) { ?>
            <div class="col-lg-3">
                <a href="<?=Url::to(['lots/create'])?>" class="btn btn-success"><i class="fa fa-plus"></i> Добавить лот</a>
            </div>
        <? } ?>
        <div class="col-lg-9">
            <?php $form = ActiveForm::begin(['method' => 'GET']); ?>
                <?= $form->field($model, 'search')?>
                <?= Html::submitButton('Найти', ['class' => 'btn btn-primary']) ?>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
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
                            'attribute' => 'title',
                            'format' => 'ntext',
                            'label' => 'Название',
                        ],
                        [
                            'attribute' => 'torg.publishedDate',
                            'format' => ['date', 'php:d.m.Y'],
                            'label' => 'Дата добавления',
                        ],
                        [
                            'attribute' => 'torg.startDate',
                            'format' => ['date', 'php:d.m.Y'],
                            'label' => 'Дата начала',
                        ],
                        [
                            'attribute' => 'torg.completeDate',
                            'format' => ['date', 'php:d.m.Y'],
                            'label' => 'Дата окончания',
                        ],
                        [
                            'attribute' => 'price',
                            'format' => 'ntext',
                            'label' => 'Цена',
                            'value' => function ($model) {
                                return $model->price.' руб.';
                            }
                        ],
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'template' => ((UserAccess::forManager('lots', 'edit') || UserAccess::forAgent('lots', 'edit'))? '{update} ':'').'{link}'.((UserAccess::forManager('lots', 'delete') || UserAccess::forAgent('lots', 'delete'))? ' {delete}':''),
                            'buttons' => [
                                'link' => function ($url,$model) {
                                    return Html::a(
                                    '<span class="fa fa-eye text-success"></span>', 
                                    Yii::$app->params['frontLink'].'/'.$model->url, ['target' => '_blank']);
                                },
                                'delete' => function ($url,$model) {
                                    return Html::a(
                                    '<span class="fa fa-trash-o text-danger"></span>', 
                                    $url, ['aria-label' => 'Удалить', 'title' => 'Удалить', 'data-pjax'=>'1', 'data-confirm' => 'Вы уверены, что хотите удалить этот лот?', 'data-method' => 'post']);
                                },
                            ]
                        ],
                    ],
                ]); ?>
<?php LteBox::end()?>