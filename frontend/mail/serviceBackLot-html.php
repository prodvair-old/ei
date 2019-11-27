<?php
use yii\helpers\Html;
use yii\helpers\Url;

use common\models\Query\Bankrupt\LotsBankrupt;
use common\models\Query\Arrest\LotsArrest;

switch ($params->lotType) {
    case 'arrest':
            $name = 'Арестованное имущество';
        break;
    case 'bankrupt':
            $name = 'Банкротное имущество';
        break;
}

$verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['site/verify-email', 'token' => $user->verification_token]);
?>
<p>
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