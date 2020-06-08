<?php 
    use yii\helpers\Html;
?>
<div class="col-lg-12">
    <div class="form-group">
        <?= Html::label($name, "info[$name]", ['class' => 'control-label']) ?>
        <?= Html::activeInput('text', $model, "info[$name]", ['class' => 'form-control']) ?>
        <div class="help-block"></div>
    </div>
</div>
<!-- <?//$form->field($modelLot, "info[$key][$name]")->textInput(['value'=>$value])->label($name) ?> -->