<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use insolita\wgadminlte\CollapseBox;
use insolita\wgadminlte\LteConst;
use kartik\select2\Select2;
use yii\web\JsExpression;

use common\models\Query\Lot\Owners;

use backend\models\UserAccess;

$this->params['h1'] = 'Редактирование пользователя - '.$model->getFullName();
$this->title = 'Редактирование пользователя - '.$model->username;

$role = [
    'user'      => 'Пользователь',
    'agent'     => 'Агент',
    'arbitr'    => 'Арбитражный управляющий',
    'sro'       => 'СРО',
    'etp'       => 'Торговая площадка',
    'manager'   => 'Менеджер',
    'admin'     => 'Администратор',
];

if (UserAccess::forSuperAdmin()) {
    $role['superAdmin'] = 'Главный администратор';
}

$accessRu = [
    'add'       => "Добавлять",
    'status'    => "Просмотр",
    'import'    => "Импортировать",
    'export'    => "Экспортировать",
    'edit'      => "Редактировать",
    'delete'    => "Удалять",
    'debug'     => "De-Buger",
    'lots'      => 'Лоты',
    'find'      => 'Поиск',
    'arrest'    => "Арестованное имущество",
    'torgs'     => 'Торги',
    'users'     => 'Пользователи',
    'owners'    => 'Организации',
];

$access = [
    "lots" => [
        "add" => false,
        "edit" => false,
        "delete" => false,
        "import" => false,
        "status" => false
    ],
    "find"  => [
        "arrest" => false
    ],
    "torgs" => [
        "add" => false,
        "edit" => false,
        "delete" => false,
        "status" => false
    ],
    "users" => [
        "add" => false,
        "edit" => false,
        "delete" => false,
        "status" => false
    ],
    "owners" => [
        "add" => false,
        "edit" => false,
        "delete" => false,
        "status" => false
    ],
    "debug" => false
];

?>
<?php $form = ActiveForm::begin(); ?>
    <?php CollapseBox::begin([
             'type'=>LteConst::TYPE_SUCCESS,
             'collapseRemember' => true,
             'collapseDefault' => false,
             'isSolid'=>true,
             'boxTools'=>Html::a('Назад', Url::to(['users/index']),['class' => 'btn']).' '.((UserAccess::forSuperAdmin('users', 'delete'))? Html::a('Удалить', '/users/delete?id='.$model->id,['class' => 'btn btn-danger', 'title' => 'Удалить', 'aria-label' => 'Удалить', 'data-pjax' => 0, 'data-confirm' => 'Вы уверены, что хотите Удалить этого пользователя?', 'data-method' => 'post']) : ''),
             'tooltip'=>'Данные полльзователя',
             'title'=>'Пользователь',
        ])?>
        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
        </div>

        <!-- <?= $form->field($model, 'username') ?> -->
        <?= $form->field($model, 'role')->dropDownList($role) ?>
        <!-- <?= $form->field($model, 'created_at') ?>
        <?= $form->field($model, 'updated_at') ?> -->
        <!-- <div class="row"> -->
        <hr>

        <div class="row">

            <? foreach ($access as $key => $value) { ?>
                <? if (is_array($value)) { ?>
                    <div class="col-md-3 col-lg-3">
                        <h4><?=$accessRu[$key]?></h4>
                        <div class="row">
                            <? foreach ($value as $name => $item) { ?>
                                <div class="col-md-12">
                                    <?= $form->field($model, "access[$key][$name]")->checkbox(['checked'=>(($model->access[$key][$name])? $model->access[$key][$name] : $item)])->label($accessRu[$name]);?>
                                </div>
                            <? } ?>
                        </div>
                    </div>
                <? } else { ?>
                    <div class="col-md-3 col-lg-2">
                        <?= $form->field($model, "access[$key]")->checkbox(['checked'=>(($model->access[$key])? $model->access[$key] : $value)])->label($accessRu[$key]);?>
                    </div>
                <? } ?>
            <? } ?>

        </div>
        <hr>

        <!-- </div> -->
        <?= $form->field($model, 'status')->dropDownList([
                    1 => 'Активен',
                    0 => 'Заблокирован',
                ],[
                    'prompt' => 'Не подтверждён',
            ]) ?>
        <?= $form->field($model, 'ownerId')->widget(Select2::classname(), [
                'data' => ArrayHelper::map(Owners::find()->where(['id'=>$model->ownerId])->all(), 'id', 'title'),
                'options' => ['placeholder' => 'Найти владельца торга'],
                'pluginOptions' => [
                    'allowClear' => true,
                    'language' => [
                        'errorLoading' => new JsExpression("function () { return 'Ошибка поиск результатов ...'; }"),
                    ],
                    'ajax' => [
                        'url' => Url::to(['owners/list']),
                        'dataType' => 'json',
                        'data' => new JsExpression('function(params) { return {q:params.term}; }')
                    ],
                    'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                    'templateResult' => new JsExpression('function(data) { return data.text; }'),
                    'templateSelection' => new JsExpression('function (data) { return data.text; }'),
                ],
            ]) ?>
    
        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php CollapseBox::end()?>
<?php ActiveForm::end(); ?>
