<?php
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;

$this->title = Yii::t('app', 'Tariffs');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class='row'>
    <div class='col-lg-12'>
        <div class='box model-index table-responsive'>
            <div class='box-header'>
                <?= Html::a(Yii::$app->params['icons']['plus'] . ' ' . Yii::t('app', 'Add'), ['create'], ['class' => 'btn btn-success']) ?>
            </div>

            <div class='box-body'>
                <?= GridView::widget([
                    'id' => 'model-grid',
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'layout' => "{items}\n{summary}\n{pager}",
                    'options' => ['class' => false],
                    'columns' => [
                        [
                            'attribute' => 'id',
                            'options' => ['style' => 'width:6%'],
                        ],
                        'name',
                        'description',
                        [
                            'attribute' => 'fee',
                            'value' => function($data) {
                                return number_format($data->fee, 0, '', ' ');
                            },
                        ],

                        [
                            'class' => 'yii\grid\ActionColumn',
                            'template' => ('{update}{delete}'), 
                            'options' => ['style' => 'width:6%'],
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>
