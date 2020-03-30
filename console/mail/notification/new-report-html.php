<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $message yii\swiftmailer\Message */
/* @var $model common\models\Query\Lot\Lots */
?>
<?php if (isset($model->reports) && count($model->reports) > 0): ?>
    <p>К лоту добавлен отчет.</p>
<?php endif; ?>
