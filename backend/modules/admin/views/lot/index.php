<?php
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;

$columns = require __DIR__ . '/lot_columns.php';

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

$this->registerJs($script, yii\web\View::POS_READY);
?>

<p>
    <?= Html::a(Yii::$app->params['icons']['plus'] . ' ' . Yii::t('app', 'Add'), ['create'], ['class' => 'btn btn-success']) ?>

    <?= Html::submitButton(Yii::t('app', 'Find'), ['class' => 'btn btn-primary search-submit']) ?>
</p>

<div class='row'>
    <div class='col-lg-12'>
        <div class='box box-primary lot-index table-responsive'>
            <div class='box-body'>

                <?= GridView::widget([
                    'id' => 'lot-grid',
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'filterOnFocusOut' => false,
                    'layout' => "{items}\n{summary}",
                    'options' => ['class' => false],
                    'columns' => $columns,
                ]); ?>

            </div>
        </div>
    </div>
</div>

<p class='lot-spinner' style='display: none;'>
    <i class="fa fa-spinner fa-spin fa-fw"></i>
</p>

<?= Html::submitButton(Yii::t('app', 'More'), [
    'class' => 'btn btn-primary load-more',
    'data-offset' => $dataProvider->getCount(),
]) ?>
