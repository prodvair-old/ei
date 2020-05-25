<?php
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;

use common\components\Property;
use common\models\db\Category;
use sergmoro1\lookup\models\Lookup;

$this->title = Yii::t('app', 'Lots');
$this->params['breadcrumbs'][] = $this->title;

$url = Url::to(['lot/more']);

$script = <<< JS
$('body').on('click', '.btn.search-submit', function(){ $("#lot-grid").yiiGridView("applyFilter"); });
$('body').on('click', '.btn.load-more',  function(){
    var that = $(this);
    var offset = that.attr('data-offset');
    var lot_index = $('.lot-index');
    var lot_grid = lot_index.find('#lot-grid');
    var filter = lot_grid.find('.filters');
    var spinner = $('.lot-spinner');
    
    spinner.show();

    $.ajax({
        url: '$url',
        data: (filter.find('.form-control').serialize() + '&offset=' + offset),
        success: function(response) {
            if (response.count) {
                lot_grid.append(response.content);
                that.attr('data-offset', offset + response.count);
            } else
                that.attr('disabled','disabled');
            spinner.hide();
        }
    });
});
JS;

$this->registerJs($script, $position = yii\web\View::POS_READY);
?>

<p>
	<?= Html::a(Yii::$app->params['icons']['plus'] . ' ' . Yii::t('app', 'Add'), ['create'], ['class' => 'btn btn-success']) ?>

    <?= Html::submitButton(Yii::t('app', 'Find'), ['class' => 'btn btn-primary search-submit']) ?>
</p>

<div class='lot-index table-responsive'>

    <?= GridView::widget([
        'id' => 'lot-grid',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'filterOnFocusOut' => false,
        'layout' => "{items}\n{summary}",
        'options' => ['class' => false],
        'columns' => [
            [
                'attribute' => 'id',
                'options' => ['style' => 'width:5%;'],
            ],
            [
                'attribute' => 'title',
                'options' => ['style' => 'width:25%;'],
            ],
            [
                'attribute' => 'status',
                'filter' => Lookup::items(Property::LOT_STATUS, true),
                'value' => function($data) {
                    return Lookup::item(Property::LOT_STATUS, $data->status, true);
                },
                'options' => ['style' => 'width:10%;'],
            ],
            [
                'attribute' => 'reason',
                'filter' => Lookup::items(Property::LOT_REASON, true),
                'value' => function($data) {
                    return Lookup::item(Property::LOT_REASON, $data->reason, true);
                },
                'options' => ['style' => 'width:10%;'],
            ],
            [
                'attribute' => 'property',
                'filter' => Lookup::items(Property::TORG_PROPERTY, true),
                'value' => function($data) {
                    return Lookup::item(Property::TORG_PROPERTY, $data->torg->property, true);
                },
                'options' => ['style' => 'width:10%;'],
            ],

            [
                'attribute' => 'category_id',
                'filter' => Category::items(),
                'value' => function($data) {
                    $c = count($data->categories);
                    return $c > 0 ? ($data->categories[0]->name . ($c > 1 ? " (+$c)" : '')) : '-';
                },
                'options' => ['style' => 'width:15%;'],
            ],
            [
                'attribute' => 'start_price',
                'options' => ['style' => 'width:10%;'],
            ],
            [
                'attribute' => 'end_at',
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

<p class='lot-spinner' style='display: none;'>
    <i class="fa fa-spinner fa-spin fa-fw"></i>
</p>

<?= Html::submitButton(Yii::t('app', 'More'), [
    'class' => 'btn btn-primary load-more',
    'style' => 'margin-top:20px',
    'data-offset' => $dataProvider->getCount(),
]) ?>
