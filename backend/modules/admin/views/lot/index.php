<?php
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use common\models\db\Category;
use backend\modules\admin\assets\Select2Asset;
use backend\modules\admin\assets\LoadMoreAsset;

$columns = require __DIR__ . '/_columns.php';

$this->title = Yii::t('app', 'Lots');
$this->params['breadcrumbs'][] = $this->title;

$url = Url::to(['lot/more']);

$this->registerJs('var load_more_url="' . $url . '";', yii\web\View::POS_HEAD);

LoadMoreAsset::register($this);

Select2Asset::register($this);

$lot_search = Yii::$app->request->get('LotSearch');
$selected = isset($lot_search['category_id']) ? [$lot_search['category_id']] : [];
$data = Category::jsonItems($selected);

$script = <<<JS
$(document).ready(function() { $('#lot-category_id').select2(
    {data: $data}
); });
JS;
$this->registerJS($script);
?>

<div class='row'>
    <div class='col-lg-12'>
        <div class='box model-index table-responsive'>
            <div class='box-header'>
                <?= Html::submitButton(Yii::t('app', 'Find'), ['class' => 'btn btn-primary search-submit']) ?>
            </div>

            <div class='box-body'>
                <?= GridView::widget([
                    'id' => 'model-grid',
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'filterOnFocusOut' => false,
                    'layout' => "{items}\n{summary}",
                    'options' => ['class' => false],
                    'columns' => $columns,
                ]); ?>
            </div>

            <div class='box-footer'>
                <p class='model-spinner' style='display: none;'>
                    <i class="fa fa-spinner fa-spin fa-fw"></i>
                </p>

                <?= Html::submitButton(Yii::t('app', 'More'), [
                    'class' => 'btn btn-primary load-more',
                    'data-offset' => $dataProvider->getCount(),
                ]) ?>
            </div>
        </div>
    </div>
</div>
