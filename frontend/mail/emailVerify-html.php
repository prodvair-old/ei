<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['site/verify-email', 'token' => $user->verification_token]);
?>
<p>
    <h4>Подтверждение регистрации</h4>
    <br>
    <p>Мы рады приветствовать Вас на нашем сайт ei.ru</p>
    <p>
        Пройдите по этой ссылка для подтверждения регистрации<br>
        <?= Html::a('Подтвердить', $verifyLink)?>
    </p>
    <p>Ссылка действует 24 часа.</p>
    <br>
    <p>
        Логин: <?= Html::encode($user->username) ?><br>
        Пароль: <?= Html::encode($password) ?>
    </p>
</p>