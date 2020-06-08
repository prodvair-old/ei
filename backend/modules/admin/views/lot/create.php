<?php

/* @var $this yii\web\View */
/* @var $model common\models\db\Lot */
/* @var $place common\models\db\Place */

$this->title = Yii::t('app', 'Create') . ' ' . Yii::t('app', 'lot');;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Lots'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', [
    'model' => $model,
    'torg'  => $torg,
    'place' => $place,
]) ?>
