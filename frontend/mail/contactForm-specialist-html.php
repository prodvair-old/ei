<?php
use yii\helpers\Url;

?>
<p>
    <h3><a href="<?=Url::to(['services/specialist'])?>">Страница консультация специалиста</a></h3>
    <h4>Запрос на услуги агента по лоту № <a href="<?=Yii::$app->request->hostInfo.'/'.$params->lot->lotUrl?>"><?= Html::encode($params->lotId) ?></a>, <?=$name?></h4>
    <p>
        Пользователь: <b><?=($user->info['firstname'] || $user->info['lastname'])? $user->info['firstname'].' '.$user->info['lastname'] : $user->info['contacts']['emails'][0]?></b>
        <br>E-mail: <b><?=$user->info['contacts']['emails'][0]?></b>
        <br>Номер телефона: <b><?=$user->info['contacts']['phones'][0]?></b>
    </p>

    <ul>
        <li>Подадим заявку на торги, которую не отклонят</li>
        <li>Участие в торгах без аккредитации на ЭТП</li>
        <li>Не нужно покупать ЭЦП, мы используем свою</li>
    </ul>
    <p>Стоимость участия в торгах: <b><?=$params->agentPrice?> руб.</b>!</p>
</p>