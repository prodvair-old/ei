<?php

/* @var $this yii\web\View */

$this->title = Yii::t('app', 'Create') . ' ' . Yii::t('app', 'tariff');;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tariffs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', [
    'model' => $model,
]) ?>
