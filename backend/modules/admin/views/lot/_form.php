<?php

/* @var $this yii\web\View */
/* @var $model common\models\db\Lot */
/* @var $form yii\widgets\ActiveForm */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

use backend\modules\admin\assets\Select2Asset;
use vova07\imperavi\Widget;
use common\components\Property;
use sergmoro1\lookup\models\Lookup;
use common\models\db\Category;
use sergmoro1\uploader\widgets\Uploader;
//use common\widgets\Document;

Select2Asset::register($this);

$script = <<<JS
$(document).ready(function() { $('#lot-new_categories').select2(); });
JS;
$this->registerJS($script);
?>

<?php $form = ActiveForm::begin(); ?>

<div class='row'>
    <div class='col-lg-8'>

        <div class='form-group'>
            <?= Html::submitButton(Yii::t('app', 'Save'), [
                'class' => 'btn btn-success',
            ]) ?>
        </div>

        <?= Uploader::widget([
            'model'         => $model,
            'draggable'     => true,
            'appendixView'  => '/lot/appendix.php',
        ]) ?>
        <br>
        
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
    </div>

    <div class='lot-status col-lg-4'>

        <?= $form->field($model, 'status')->dropdownList(Lookup::items(Property::LOT_STATUS, true)) ?>
        <?= $form->field($model, 'reason')->dropdownList(Lookup::items(Property::LOT_REASON, true)) ?>

        <?= $form->field($model, 'new_categories')->dropDownList(Category::items(), [
                'multiple'=>'multiple',
            ]             
        ); ?>
        
        <?= '';//Document::widget(['model' => $model, 'viewPath' => '@backend/modules/admin/widgets/views']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>
