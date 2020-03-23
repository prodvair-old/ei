<?php

/* @var $this yii\web\View */
/* @var $user common\models\User */
/* @var $lot common\models\Query\Lot\Lots */

$lotLink = Yii::$app->urlManager->createAbsoluteUrl(['lot/view', 'id' => $lot_id]);
?>
Добрый день, <?= $user->getFullName() ?>,

К лоту <?= $lot->title ?>

По адресу
 
<?= $lotLink ?>

добавлены фотографии.

