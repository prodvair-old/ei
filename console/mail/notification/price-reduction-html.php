<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Query\Lot\Lots */
?>
<?php if (isset($model->price)) > 0): ?>
    <p>По лоту изменилась цена. Предыдущая цена - <?= $model->getOldPrice() ?>, текущая цена - <?= $model->getPrice() ?>.</p>
<?php endif; ?>
