<?php 
	use yii\widgets\ActiveForm;
	use yii\helpers\Html;
?>
<div class="mt-100"></div>
<div class="d-flex justify-content-center">
    <div>
        <h1>Import Файла</h1>

        <?php $form = ActiveForm::begin(['options'=>['enctype'=>'multipart/form-data']]);?>

            <?= $form->field($modelImport,'fileImport')->fileInput() ?>
            <?= Html::submitButton('Import',['class'=>'btn btn-primary']);?>

        <?php ActiveForm::end();?>
    </div>
</div>
