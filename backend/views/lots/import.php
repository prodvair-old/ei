<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\grid\GridView;
use insolita\wgadminlte\LteBox;
use insolita\wgadminlte\LteConst;
use yii\data\ActiveDataProvider;

use backend\models\UserAccess;

$this->params['h1'] = 'Импортирование лотов';
$this->title = 'Импортирование лотов';
?>

<h4>Как загрузить лоты:</h4>
<ul class="list-icon-absolute what-included-list mb-30">
    <li>
    <span class="icon-font"><i class="elegent-icon-check_alt2 text-primary"></i> </span>
    Скачайте <a href="<?= Url::to('files/Формат_добавления_лотов_в_залоговое_иммущество_ei.ru.xlsx') ?>" target="_blank" download>шаблон excel</a> файла;
    </li>
    <li>
    <span class="icon-font"><i class="elegent-icon-check_alt2 text-primary"></i> </span>
    Заполните файл в соответствии с <a href="<?= Url::to('files/Формат_добавления_лотов_в_залоговое_иммущество_ei.ru.xlsx') ?>" target="_blank" download>требованиями</a>;
    </li>
    <li>
    <span class="icon-font"><i class="elegent-icon-check_alt2 text-primary"></i> </span>
    Загрузите заполненный вашими данными файл в соответствующую форму;
    </li>
    <li>
    <span class="icon-font"><i class="elegent-icon-check_alt2 text-primary"></i> </span>
    Лоты из файла появятся в профиле со статусом “Не опубликовано”;
    </li>
    <li>
    <span class="icon-font"><i class="elegent-icon-check_alt2 text-primary"></i> </span>
    Добавьте каждому лоту вручную фотографии (возможно выбрать несколько), добавьте <br>категорию и подкатегорию (возможно выбрать несколько);
    </li>
    <li>
    <span class="icon-font"><i class="elegent-icon-check_alt2 text-primary"></i> </span>
    Нажмите кнопку “Опубликовать”.
    </li>
</ul>

<hr>

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <div class="custom-file">
        <?= $form->field($modelImport, 'fileImport',['template' => '<div class="custom-file">{label}{hint}{input}{error}</div>'])->fileInput(['class' => 'custom-file-input'])->label('Загрузить файл',['class'=>'custom-file-label']) ?>
        
        <?= Html::submitButton('Импортировать лоты', ['class' => 'btn btn-primary']); ?>
    </div>


    <?php ActiveForm::end(); ?>

<?
if ($lots[0] != null) {
    $dataProvider = new ActiveDataProvider([
        'query' => $lots,
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
                            'attribute' => 'price',
                            'format' => 'ntext',
                            'label' => 'Цена',
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
<? } ?>