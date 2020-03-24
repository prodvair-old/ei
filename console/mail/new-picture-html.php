<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */
/* @var $lot common\models\Query\Lot\Lots */

$lotLink = Yii::$app->urlManager->createAbsoluteUrl(['lot/view', 'id' => $lot->id]);
?>
<div class="password-reset">
    <p>Добрый день, <?= Html::encode($user->getFullName()) ?>,</p>

    <p>К лоту <?= Html::a(Html::encode($lot->title), $lotLink) ?> добавлены фотографии.</p>
</div>
