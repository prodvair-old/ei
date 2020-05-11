<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model frontend\modules\models\LotsOld */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Lots Olds', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="lots-old-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id, 'torgId' => $model->torgId, 'msgId' => $model->msgId], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id, 'torgId' => $model->torgId, 'msgId' => $model->msgId], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'torgId',
            'msgId:ntext',
            'lotNumber',
            'createdAt',
            'updatedAt',
            'title:ntext',
            'description:ntext',
            'startPrice',
            'step',
            'stepType',
            'stepTypeId',
            'deposit',
            'depositType',
            'depositTypeId',
            'status',
            'info',
            'images',
            'published:boolean',
            'regionId',
            'city:ntext',
            'district:ntext',
            'oldId',
            'bankId',
            'address:ntext',
            'archive',
        ],
    ]) ?>

</div>
