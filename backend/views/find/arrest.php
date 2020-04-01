<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\grid\GridView;
use insolita\wgadminlte\LteBox;
use insolita\wgadminlte\LteConst;
use yii\data\ActiveDataProvider;

use backend\models\UserAccess;

$this->params['h1'] = 'Расширенный поиск имущества';
$this->title = 'Расширенный поиск имущества';
?>

<h4>Как это работает?</h4>
<p>
    Скачайте и заполните <a href="<?= Url::to('@web/files/example.xlsx') ?>" target="_blank" download>файл примера</a> своими данными. Загрузите в соответствующую форму.
    <br>После загрузки файла, система начнет поиск по заданным параметрам
</p>

<hr>

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <div class="custom-file">
        <?= $form->field($modelImport, 'fileImport')->textarea()->label('Скопированные данные таблицы') ?>
        <? // $form->field($modelImport, 'fileImport',['template' => '<div class="custom-file">{label}{hint}{input}{error}</div>'])->fileInput(['class' => 'custom-file-input', 'accept' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel'])->label('Загрузить файл',['class'=>'custom-file-label']) ?>
        
        <?= Html::submitButton('Поиск', ['class' => 'btn btn-primary']); ?>
    </div>

<?php ActiveForm::end(); ?>
