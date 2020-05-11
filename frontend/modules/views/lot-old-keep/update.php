<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\modules\models\LotsOld */

$this->title = 'Update Lots Old: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Lots Olds', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id, 'torgId' => $model->torgId, 'msgId' => $model->msgId]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="lots-old-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
