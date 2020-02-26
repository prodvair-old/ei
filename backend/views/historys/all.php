<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\grid\GridView;
use insolita\wgadminlte\LteBox;
use insolita\wgadminlte\LteConst;
use yii\data\ActiveDataProvider;

use backend\models\UserAccess;

use common\models\Query\HistoryAdmin;

$this->params['h1'] = 'Журнал панеля управления';
$this->title = 'История лог данных панеля управления';

$hisroys = HistoryAdmin::find()->joinWith(['user']);

if (!UserAccess::forSuperAdmin()) {
    $hisroys->where(['!=', 'userRole', 'superAdmin']);
}

$dataProvider = new ActiveDataProvider([
    'query' => $hisroys,
    'Pagination' => [
        'pageSize' => 30
    ]
]);
?>
 <?php LteBox::begin(['type'=>LteConst::TYPE_DEFAULT]);?>
        <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => [
                        [
                            'attribute' => 'id',
                            'format' => 'ntext',
                            'label' => 'ID',
                        ],
                        [
                            'attribute' => 'user.username',
                            'format' => 'ntext',
                            'label' => 'Имя пользователя',
                        ],
                        [
                            'attribute' => 'userRole',
                            'format' => 'ntext',
                            'label' => 'Роль',
                        ],
                        [
                            'attribute' => 'type',
                            'format' => 'ntext',
                            'label' => 'Тип',
                        ],
                        [
                            'attribute' => 'status',
                            'format' => 'ntext',
                            'label' => 'Статус',
                        ],
                        [
                            'attribute' => 'createdAt',
                            'format' => ['date', 'php:d.m.Y H:i:s'],
                            'label' => 'Дата и время',
                        ],
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{view}',
                        ],
                    ],
                ]); ?>
<?php LteBox::end()?>