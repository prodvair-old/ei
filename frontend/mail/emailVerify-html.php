<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['site/verify-email', 'token' => $user->verification_token]);
?>
<p>
    <h4>Подтверждение регистрации</h4>
    <br>
    <p>Мы рады привествтовать вас на нашем сайт ei.ru</p>
    <p>
        Пройдите по этой ссылка для подтверждения регистрации<br>
        <?= Html::a(Html::encode($verifyLink), $verifyLink)?>
    </p>
    <p>Ссылка действует 24 часа.</p>
    <br>
    <p>
        Логин: <?= Html::encode($user->username) ?><br>
        Пароль: <?= Html::encode($password) ?>
    </p>
    <br>
    <p>© <?=Yii::$app->name?></p>
</p>