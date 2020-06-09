<?php

/* @var $this yii\web\View */
/* @var $place common\models\db\Report */
/* @var $model common\models\db\Lot */

$this->title = Yii::t('app', 'Update') . ' ' . Yii::t('app', 'report');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Reports'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', [
    'model' => $model,
    'lot'   => $lot,
]) ?>
