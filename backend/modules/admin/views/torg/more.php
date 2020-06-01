<?php
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\grid\GridView;

$columns = require __DIR__ . '/torg_columns.php';

?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'layout' => "{items}\n{summary}",
    'options' => ['tag' => false],
    'columns' => $columns,
]); ?>
