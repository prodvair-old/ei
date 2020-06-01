<?php

/* @var $this yii\web\View */
/* @var $model common\models\db\Lot */
/* @var $place common\models\db\Place */

$this->title = Yii::t('app', 'Update');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Lots'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->shortTitle, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', [
    'model' => $model,
    'place' => $place,
]) ?>
