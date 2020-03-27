<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */
/* @var $models common\models\Query\Lot\Lots */
/* @var $lots array [lot_id => ['new-picture', 'price-reduction']] */
/* @var $links array [lot_id => link] */

?>
<div class='notification'>
    <p>Добрый день, <?= Html::encode($user->getFullName()) ?>,</p>

    <?php foreach ($models as $model): ?>
        <?php foreach ($lots[$model->id] as $i => $event): ?>
            <?= $this->render("notification/$event-html", ['model' => $model, 'message' => $message]) ?>
        <?php endforeach; ?>
        <p>Для просмотра лота перейдите по <?= Html::a('ссылке', $links['view']) ?>.</p>
        <hr>
        <p><small>
            <?= Html::a('Отписаться', $links['unsubscribe']) ?> от уведомлений по данному лоту.
        </small></p>
    <?php endforeach; ?>
    <p><small>
        Вы также можете полностью <?= Html::a('очистить', $links['unsubscribeAll']) ?> список избранных лотов.
    </small></p>
</div>
