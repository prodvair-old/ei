<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\modules\models\LotsOld */

$this->title = 'Create Lots Old';
$this->params['breadcrumbs'][] = ['label' => 'Lots Olds', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lots-old-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
