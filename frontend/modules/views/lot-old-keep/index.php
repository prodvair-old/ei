<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel frontend\modules\models\LotsOldSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Lots Olds';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lots-old-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Lots Old', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'torgId',
            'msgId:ntext',
            'lotNumber',
//            'createdAt',
//            'updatedAt',
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
            //'info',
            //'images',
            //'published:boolean',
            //'regionId',
            //'city:ntext',
            //'district:ntext',
            //'oldId',
            //'bankId',
            //'address:ntext',
            //'archive',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
