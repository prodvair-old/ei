<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $message yii\swiftmailer\Message */
/* @var $user common\models\User */
/* @var $models common\models\Query\Lot\Lots */
/* @var $lots array [lot_id => ['new-picture', 'price-reduction']] */
/* @var $links array [lot_id => link] */

$unsubscribeAllLink = Yii::$app->urlManager->createAbsoluteUrl(['/lot/unsubscribe-all', 'user_id' => $user->id]);
?>
<div class='notification'>
    <p>Добрый день, <?= Html::encode($user->getFullName()) ?>,</p>

    <?php foreach ($models as $model): ?>
        <h3>Лот<br><small><?= $model->title ?></small></h3>
        <?php foreach ($lots[$model->id] as $event): ?>
            <?= $this->render("notification/$event-html", ['model' => $model, 'message' => $message]) ?>
        <?php endforeach; ?>
        <p>Для просмотра лота перейдите по <?= Html::a('ссылке', 
            Yii::$app->urlManager->createAbsoluteUrl(['/lot/view', 'id' => $model->id])) ?>.</p>
        <hr>
        <p><small>
            <?= Html::a('Отписаться', 
                Yii::$app->urlManager->createAbsoluteUrl(['/lot/unsubscribe', 'user_id' => $user->id, 'lot_id' => $model->id])) ?> 
                от уведомлений по данному лоту.
        </small></p>
    <?php endforeach; ?>
    <p><small>
        Вы также можете полностью <?= Html::a('очистить', $unsubscribeAllLink) ?> список избранных лотов.
    </small></p>
</div>
