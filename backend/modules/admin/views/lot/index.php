<?php
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;

use common\models\db\Category;
use sergmoro1\lookup\models\Lookup;

$this->title = Yii::t('app', 'Lots');
$this->params['breadcrumbs'][] = $this->title;
?>

<p>
	<?= Html::a(Yii::$app->params['icons']['plus'] . ' ' . Yii::t('app', 'Add'), ['create'], ['class' => 'btn btn-success']) ?>
</p>

<div class='lot-index table-responsive'>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout' => "{items}\n{summary}\n{pager}",
        'options' => ['class' => false],
        'columns' => [
            'id',
            'title',
            [
                'attribute' => 'status',
                'filter' => Lookup::items('LotStatus'),
                'value' => function($data) {
                    return Lookup::item('LotStatus', $data->status);
                }
            ],
            [
                'attribute' => 'reason',
                'filter' => Lookup::items('LotReason'),
                'value' => function($data) {
                    return Lookup::item('LotReason', $data->reason);
                }
            ],
            [
                'header' => 'property',
                'value' => function($data) {
                    return Lookup::item('TorgProperty', $data->torg->property);
                }
            ],
            'start_price',
            [
                'attribute' => 'category_id',
                'filter' => Category::items(),
                'value' => function($data) {
                    $a = [];
                    foreach ($data->categories as $category)
                        $a[] = $category->name;
                    return implode(', ', $a);
                }
            ],
            [
                'header' => 'end_at',
                'value' => function($data) {
                    return date('d.m.y', $data->torg->end_at);
                },
                'options' => ['style' => 'width:9%;'],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}{update}{delete}', 
                'options' => ['style' => 'width:6%;'],
            ],
        ],
    ]); ?>

</div>
