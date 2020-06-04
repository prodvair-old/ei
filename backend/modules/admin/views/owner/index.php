<?php
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;

use common\components\Property;
use sergmoro1\lookup\models\Lookup;

$this->title = Yii::t('app', 'Owners');
$this->params['breadcrumbs'][] = $this->title;

$script = <<<JS
$('body').on('click', '.btn.search-submit', function(){ $("#model-grid").yiiGridView("applyFilter"); });
JS;

$this->registerJs($script);
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
                    'layout' => "{items}\n{summary}\n{pager}",
                    'options' => ['class' => false],
                    'columns' => [
                        [
                            'attribute' => 'id',
                            'options' => ['style' => 'width:6%'],
                        ],
                        'title',
                        'email',
                        'phone',
                        'website',
                        [
                            'attribute' => 'status',
                            'value' => function($data) {
                                return Lookup::item(Property::ORGANIZATION_STATUS, $data['status'], true);
                            },
                        ],

                        [
                            'class' => 'yii\grid\ActionColumn',
                            'template' => ('{view}{update}{delete}'), 
                            'options' => ['style' => 'width:6%'],
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>
