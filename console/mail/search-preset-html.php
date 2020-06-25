<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $message yii\swiftmailer\Message */
/* @var $user common\models\User */
/* @var $lots common\models\Query\Lot\Lots */

$link = Yii::$app->params['frontLink'] . '/profile/search-preset?token=' . $user->password_reset_token;
?>
<div class='notification'>
    <p>Добрый день, <?= Html::encode($user->getFullName()) ?>,</p>

    <p>По вашему запросу "<?=$searchQuery->defs?>" добавлены новые лоты. Количество: <?=$count?></p>

    <ul style="list-style-type: none; padding: 0 20px;">
        <?php foreach ($lots as $lot): ?>
            <li style="border: 1px solid #e5e5e5; padding: 20px; margin: 20px 0;">
                <h3 style="margin-top: 0px">Лот<br><small><?= $lot->title ?></small></h3>
                <p style="margin-bottom: 0px">Для просмотра лота перейдите по <?= Html::a('ссылке', Yii::$app->params['frontLink'] .'/all/lot-list/' . $lot->id) ?>.</p>
            </li>
        <?php endforeach; ?>
    </ul>
    <hr>
    <p><small>
        Вы также можете полностью <?= Html::a('посмотреть', $link) ?> список сохранённых поисков.
    </small></p>
</div>
