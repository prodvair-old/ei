<?php
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Url;
use yii\helpers\Html;
use backend\modules\admin\assets\LoadMoreAsset;
use yii\grid\GridView;

$columns = require __DIR__ . '/torg_columns.php';

$this->title = Yii::t('app', 'Auctions');
$this->params['breadcrumbs'][] = $this->title;

$url = Url::to(['torg/more']);

$this->registerJs('var load_more_url="' . $url . '";', yii\web\View::POS_HEAD);

LoadMoreAsset::register($this);
?>

<div class='row'>
    <div class='col-lg-12'>
        <div class='box model-index table-responsive'>
            <div class='box-header'>
                <?= Html::a(Yii::$app->params['icons']['plus'] . ' ' . Yii::t('app', 'Add'), ['create'], ['class' => 'btn btn-success']) ?>

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
                    <i class='fa fa-spinner fa-spin fa-fw'></i>
                </p>

                <?= Html::submitButton(Yii::t('app', 'More'), [
                    'class' => 'btn btn-primary load-more',
                    'data-offset' => $dataProvider->getCount(),
                ]) ?>
            </div>
        </div>
    </div>
</div>
