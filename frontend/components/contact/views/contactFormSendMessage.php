<?
use Yii;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

?>

<?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>

    <div class="contact-successful-messages"></div>

    <div class="contact-inner">

        <?= $form->field($model, 'name')->textInput(['class' => 'form-control'])->label('Имя'); ?>

        <?= $form->field($model, 'email')->textInput(['class' => 'form-control'])->label('Email'); ?>

        <?= $form->field($model, 'phone')->textInput(['class' => 'form-control'])->label('Номер телефона'); ?>

        <?= $form->field($model, 'message')->textarea(['class' => 'form-control', 'rows' => '7s'])->label('Сообщение'); ?>
        
        <div class="custom-control custom-checkbox pl-0">
            <?= $form->field($model, 'checkPolicy',[
                'template' => "{input}{label}"
                ])->checkbox(['checked' => true])->label('Я принимаю <a href="/policy" target="_blank">соглашения!</a>') ?>
        </div>

        <?= Html::submitButton('Отправить сообщение', ['class' => 'btn btn-primary btn-send btn-wide mt-15']) ?>        

    </div>

<?php ActiveForm::end(); ?>
