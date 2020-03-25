<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */
/* @var $lot common\models\Query\Lot\Lots */
/* @var $links array */
?>
<div class='notification'>
    <p>Добрый день, <?= Html::encode($user->getFullName()) ?>,</p>

    <p>К лоту <?= Html::a(Html::encode($lot->title), $links['view']) ?> добавлен отчет. 
        Для просмотра перейдите по <?= Html::a('ссылке', $links['view']) ?>.</p>
    
    <hr>
    <p><small>Вы получили это письмо, так-как данный лот у Вас в избранном. 
        Вы можете <?= Html::a('отписаться', $links['unsubscribe']) ?> от уведомлений по данному лоту или 
        полностью <?= Html::a('очистить', $links['unsubscribeAll']) ?> список избранных лотов.</small></p>
</div>
