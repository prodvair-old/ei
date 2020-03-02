<?php

use yii\helpers\Html;
use yii\web\View;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use insolita\wgadminlte\CollapseBox;
use insolita\wgadminlte\LteConst;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use insolita\wgadminlte\LteBox;
use yii\data\ActiveDataProvider;

use backend\models\UserAccess;
use backend\models\AddField;

use common\models\User;

$this->params['h1'] = 'Редактирование организации №'.$model->id;
$this->title = 'Редактирование организации - '.$model->title;

$dataProvider = new ActiveDataProvider([
    'query' => User::find()->where(['ownerId' => $model->id, 'role' => 'agent'])->orderBy('created_at ASC'),
    'Pagination' => [
        'pageSize' => 10
    ]
]);

$template = [
    'color-1' => [
        'name' => 'Цвет №1',
        'value' => ''
    ],
    'color-2' => [
        'name' => 'Цвет №2',
        'value' => ''
    ],
    'color-3' => [
        'name' => 'Цвет №3',
        'value' => ''
    ],
    'color-4' => [
        'name' => 'Цвет №4',
        'value' => ''
    ],
    'color-5' => [
        'name' => 'Цвет №5',
        'value' => ''
    ],
    'color-6' => [
        'name' => 'Цвет №6',
        'value' => ''
    ],
];

foreach ($model->template as $key => $value) {
    if (strpos($key, 'color') !== false) {
        $template[$key]['value'] = $value;
    }
}

?>
<?php $form = ActiveForm::begin(['id' => 'add-owner-field']); ?>
    <?php Modal::begin([
        'header' => 'Новое поле для организации',
        'options' => ['id' => 'modal-owner'],
        'footer' => Html::submitButton('Добавить', ['class' => 'btn btn-primary', 'data' => ['toggle' => 'modal', 'target' => '#modal-owner']])
    ]); ?>
        <?= $form->field(new AddField(), 'name')->textInput(['id' => 'add-owner-field-input']) ?>
    <?php Modal::end(); ?>
<?php ActiveForm::end(); ?>

<?php CollapseBox::begin([
            'type'=>LteConst::TYPE_SUCCESS,
            'collapseRemember' => true,
            'collapseDefault' => false,
            'isSolid'=>true,
            'boxTools'=>Html::a('Назад', Url::to(['owners/index']),['class' => 'btn']).' '.Html::a('Удалить', '/owners/delete?id='.$model->id,['class' => 'btn btn-danger', 'title' => 'Удалить', 'aria-label' => 'Удалить', 'data-pjax' => 0, 'data-confirm' => 'Вы уверены, что хотите Удалить эту организацию?', 'data-method' => 'post']).' '.Html::a('Страница', Yii::$app->params['frontLink'].'/'.$model->linkEi,['class' => 'btn', 'target' => '_blank']),
            'tooltip'=>'Данные организации',
            'title'=>'Информация организации',
    ])?>
    <?php $form = ActiveForm::begin(); ?>
        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="row">
                    <div class="col-lg-4">
                        <?=Html::a('<img src="'.Yii::$app->params['frontLink'].$model->logo.'" style="max-width: 100%" alt="">', Url::to(['owners/logo-del', 'ownerId' => $model->id]),['class' => 'btn', 'title' => 'Удалить', 'aria-label' => 'Удалить', 'data-pjax' => 1, 'data-confirm' => 'Вы уверены, что хотите Удалить Логотип у этой организации?', 'data-method' => 'post'])?>
                    </div>
                </div>
                <?= $form->field($model, 'upload')->fileInput(['multiple' => false, 'accept' => 'image/jpeg,image/png,image/jpg']) ?>
            </div>
            <div class="col-lg-6">
                <div class="row">
                    <div class="col-lg-4">
                        <?=Html::a('<img src="'.Yii::$app->params['frontLink'].$model->template['bg'].'" style="max-width: 100%" alt="">', Url::to(['owners/bg-del', 'ownerId' => $model->id]),['class' => 'btn', 'title' => 'Удалить', 'aria-label' => 'Удалить', 'data-pjax' => 1, 'data-confirm' => 'Вы уверены, что хотите Удалить Фон у этой организации?', 'data-method' => 'post'])?>
                    </div>
                </div>
                <?= $form->field($model, 'template[bg]')->hiddenInput(['value'=>$model->template['bg']])->label(false) ?>
                <?= $form->field($model, 'bg')->fileInput(['multiple' => false, 'accept' => 'image/jpeg,image/png,image/jpg'])->label('Фон') ?>
            </div>
        </div>

        <hr>

        <div class="row">
            <div class="col-lg-12">
                <?= $form->field($model, 'title') ?>
            </div>
            <div class="col-lg-12">
                <?= $form->field($model, 'description')->textarea() ?>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-lg-4">
                <?= $form->field($model, 'email') ?>
            </div>
            <div class="col-lg-4">
                <?= $form->field($model, 'phone') ?>
            </div>
            <div class="col-lg-4">
                <?= $form->field($model, 'inn') ?>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-lg-6">
                <?= $form->field($model, 'url') ?>
            </div>
            <div class="col-lg-6">
                <?= $form->field($model, 'linkEi') ?>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-lg-12">
                <?= $form->field($model, 'address') ?>
            </div>
        </div>

        <hr>

        <div class="row">
            <? foreach ($template as $key => $info): ?>
                <div class="col-lg-4 col-md-6">
                    <?= $form->field($model, "template[$key]")->textInput(['value'=>$info['value'], 'style' => 'color: '.$info['value']])->label($info['name']) ?>
                </div>
            <? endforeach ?>
        </div>

        <hr>

        <div class="row">
            <? foreach ($model->info as $key => $info): ?>
                <?if (is_array($info)) { ?>
                    <div class="col-lg-12">
                        <hr>

                        <h4><?= $key ?></h4>

                        <div class="row">
                            <? foreach ($info as $name => $value): ?>
                                <div class="col-lg-6">
                                    <?= $form->field($model, "info[$key][$name]")->textInput(['value'=>$value])->label($name) ?>
                                </div>
                            <? endforeach ?>
                        </div>

                        <hr>
                    </div>

                <? } else { ?>
                    <div class="col-lg-12">
                        <?= $form->field($model, "info[$key]")->textInput(['value'=>$info])->label($key) ?>
                    </div>
                <? } ?>
            <? endforeach ?>
        </div>

        <div id="ajax-content-owner" class="row"></div>

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Добавить поле', 'javascript:void(0);', ['class' => 'btn btn-success', 'data' => ['toggle' => 'modal', 'target' => '#modal-owner']]) ?>
        </div>

    <?php ActiveForm::end(); ?>
<?php CollapseBox::end()?>

<?php CollapseBox::begin([
             'type'=>LteConst::TYPE_DEFAULT,
             'isSolid'=>true,
             'tooltip'=>'Агенты',
             'title'=>'Список агентов',
        ])?>
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
                            'value' => function ($user) {
                                return $user->info['lastname'].' '.$user->info['firstname'].' '.$user->info['middlename'];
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
                            'value' => function ($user) {
                                if (!empty($user->email_hash)) {
                                    return 'Не подтверждён';
                                }
                                return ($user->status)? 'Активен' : 'Заблокирован';
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
                                'update' => function ($url,$user) {
                                    return Html::a(
                                    '<span class="glyphicon glyphicon-pencil"></span>', 
                                    Url::to(['users/update', 'id' => $user->id]));
                                },
                                'delete' => function ($url,$user) {
                                    return Html::a(
                                    '<span class="fa fa-trash-o text-danger"></span>', 
                                    $url, ['aria-label' => 'Удалить', 'title' => 'Удалить', 'data-pjax'=>'1', 'data-confirm' => 'Вы уверены, что хотите удалить этого пользователя?', 'data-method' => 'post']);
                                },
                            ]
                        ],
                    ],
                ]); ?>
<?php LteBox::end()?>
        </div>
<?php CollapseBox::end()?>


<?php
$script = <<< JS
jQuery('#add-owner-field').on('submit',function(e){
    e.preventDefault();

    jQuery.post('/add-field-owner?name=' + jQuery('#add-owner-field-input').val(), function(data){
        jQuery('#ajax-content-owner').append(data);
    });

    return false;
});
JS;
$this->registerJs($script, View::POS_END);
?>