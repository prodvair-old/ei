<?php

/* @var $this yii\web\View */
/* @var $model common\models\db\Lot */
/* @var $form yii\widgets\ActiveForm */

use yii\helpers\Url;
use yii\helpers\Html;

use vova07\imperavi\Widget;
use common\components\Property;
use sergmoro1\lookup\models\Lookup;

?>

<?= $form->field($model, 'title')->textArea(['maxlength' => true]) ?>

<div class='row'>
    <div class='col-sm-4'>
        <?= $form->field($model, 'start_price')->textInput(['type' => 'number']) ?>
    </div>
    <div class='col-sm-4'>
        <?= $form->field($model, 'deposit')->textInput(['type' => 'number']) ?>
    </div>
    <div class='col-sm-4'>
        <?= $form->field($model, 'deposit_measure')->dropdownList(Lookup::items(Property::SUM_MEASURE, true), [
            'prompt' => Yii::t('app', 'Select'),
        ]); ?>
    </div>
</div>
<div class='row'>
    <div class='col-sm-4 col-md-offset-4'>
        <?= $form->field($model, 'step')->textInput(['type' => 'number']) ?>
    </div>
    <div class='col-sm-4'>
        <?= $form->field($model, 'step_measure')->dropdownList(Lookup::items(Property::SUM_MEASURE, true), [
            'prompt' => Yii::t('app', 'Select'),
        ]); ?>
    </div>
</div>

<?= $form->field($model, 'description')->widget(Widget::className(), [
    'settings' => [
        'lang' => 'ru',
        'minHeight' => 200,
        'replaceDivs' => false,
        'fileUpload' => Url::to(['site/file-upload']),            
        'imageUpload' => Url::to(['site/image-upload']),
        'fileDelete' => Url::to(['site/delete-file']),
        'imageDelete' => Url::to(['site/delete-file']),
        'fileManagerJson' => Url::to(['site/get-files']),        
        'imageManagerJson' => Url::to(['site/get-images']),        
        'plugins' => [
            'video',
            'table',
            'fullscreen',
        ],
    ],
    'plugins' => [
        'filemanager' => 'vova07\imperavi\bundles\FileManagerAsset',              
        'imagemanager' => 'vova07\imperavi\bundles\ImageManagerAsset',              
    ],
]); ?>

<div class='form-group'>
    <?= Html::submitButton(Yii::t('app', 'Save'), [
        'class' => 'btn btn-success',
    ]) ?>
</div>
