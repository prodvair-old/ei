<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $message yii\swiftmailer\Message */
/* @var $user common\models\User */
/* @var $lots common\models\Query\Lot\Lots */
$path = ($searchQuery->getQueryParser(true))['path'];
$url = Yii::$app->params['frontLink'] . $path['path'].'/';
$link = Yii::$app->params['frontLink'] . '/profile/search-preset?token=' . $user->password_reset_token;
?>
<div class='notification'>
    <p>Добрый день, <?= Html::encode($user->getFullName()) ?>,</p>

    <p>По вашему запросу "<?= Html::a($searchQuery->defs, $searchQuery->url) ?>" добавлены новые лоты.</p>
    <hr>
    <p><h4><small>Количество лотов:</small> <?=$count?></h4></p>
    <ul style="list-style-type: none; padding: 0 20px;">
        <?php foreach ($lots as $lot): ?>
            <li style="border: 1px solid #e5e5e5; padding: 20px; margin: 20px 0;">
                <?= Html::a('<h3 style="margin: 0px">'. $lot->title .'</h3>', $url.$lot->id) ?>
                <p style="margin: 0px; font-size: 1.2rem">Текущая стоимость: <b><?= Yii::$app->formatter->asCurrency($lot->start_price) ?></b></p>
            </li>
        <?php endforeach; ?>
    </ul>
    <hr>
    <p><small>
        Вы также можете полностью <?= Html::a('посмотреть', $link) ?> список сохранённых поисков.
    </small></p>
</div>
