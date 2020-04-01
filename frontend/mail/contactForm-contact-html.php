<?php
use Yii;
use yii\helpers\Url;

?>
<p>
    <h3>Форма обратной связи из страницы контактов</h3>
    <p>

        <br>Имя: <b><?=$param->name?></b>
        <br>E-mail: <b><?=$param->email?></b>
        <br>Номер телефона: <b><?=$param->phone?></b>
    </p>

    <h5>Сообщение</h5>
    <p><?=$param->message?></p>

    <? if (!Yii::$app->user->isGuest) { ?>
        <br><b>Пользователь авторизован</b>
    <? } ?>
</p>