<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $message yii\swiftmailer\Message */
/* @var $model common\models\Query\Lot\Lots */
?>
<?php if (isset($model->price)): ?>
    <p>По лоту изменилась цена. Предыдущая цена - <?= $model->getOldPrice() ?>, текущая цена - <?= $model->getPrice() ?>.</p>
<?php endif; ?>
