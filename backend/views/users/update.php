<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use insolita\wgadminlte\CollapseBox;
use insolita\wgadminlte\LteConst;
use kartik\select2\Select2;

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

$access = [
    'add'       => "Добавлять",
    'edit'      => "Редактировать",
    'delete'    => "Удалять",
];

?>
<?php $form = ActiveForm::begin(); ?>
    <?php CollapseBox::begin([
             'type'=>LteConst::TYPE_SUCCESS,
             'collapseRemember' => true,
             'collapseDefault' => false,
             'isSolid'=>true,
             'boxTools'=>Html::a('Назад', Url::to(['users/index']),['class' => 'btn']).' '.((UserAccess::forSuperAdmin('users', 'delete'))? Html::a('Удалить', '/users/delete?id='.$model->id,['class' => 'btn btn-danger', 'title' => 'Удалить', 'aria-label' => 'Удалить', 'data-pjax' => 0, 'data-confirm' => 'Вы уверены, что хотите Удалить этоого пользователя?', 'data-method' => 'post']) : ''),
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

        <div class="col-md-3 col-lg-2">
            <?= $form->field($model, 'lotAccess')->checkboxList($access); ?>
        </div>
        <div class="col-md-3 col-lg-2">
            <?= $form->field($model, 'torgAccess')->checkboxList($access); ?>
        </div>
        <div class="col-md-3 col-lg-2">
            <?= $form->field($model, 'etpAccess')->checkboxList($access); ?>
        </div>
        <div class="col-md-3 col-lg-2">
            <?= $form->field($model, 'sroAccess')->checkboxList($access); ?>
        </div>
        <div class="col-md-3 col-lg-2">
            <?= $form->field($model, 'arbitrAccess')->checkboxList($access); ?>
        </div>
        <div class="col-md-3 col-lg-2">
            <?= $form->field($model, 'bankruptAccess')->checkboxList($access); ?>
        </div>
        <div class="col-md-3 col-lg-2">
            <?= $form->field($model, 'organizationAccess')->checkboxList($access); ?>
        </div>
        <div class="col-md-3 col-lg-2">
            <?= $form->field($model, 'usersAccess')->checkboxList($access); ?>
        </div>
        
        <hr>

        <!-- </div> -->
        <?= $form->field($model, 'status')->dropDownList([
                    1 => 'Активен',
                    0 => 'Заблокирован',
                ],[
                    'prompt' => 'Не подтверждён',
            ]) ?>
        <?= $form->field($model, 'ownerId') ?>
    
        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php CollapseBox::end()?>
<?php ActiveForm::end(); ?>
