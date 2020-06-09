<?php

/* @var $this yii\web\View */
/* @var $model common\models\db\Report */
/* @var $place common\models\db\Lot */

$this->title = Yii::t('app', 'Create') . ' ' . Yii::t('app', 'report');;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Reports'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', [
    'model' => $model,
    'lot'   => $lot,
]) ?>
