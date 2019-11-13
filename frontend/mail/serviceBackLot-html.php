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
    <br>
    <p>ЭЦП <b><?=($params->ecp)? 'есть' : 'нету'?></b></p>
    <p>Список услуг:</p>
    <ul>
        <li>Услуга агента по тограм — 0 руб.: <b><?=($params->serviceAgent)? 'Выбрано' : 'Не выбрано'?></b></li>
        <li>Консультация и сопровождение в получении ЭЦП — <?=$params->servicePrice?> руб.: <b><?=($params->serviceKonsultEcp)? 'Выбрано' : 'Не выбрано'?></b></li>
        <li>Регистрация на ЭТП — 2500 руб.: <b><?=($params->serviceRegEcp)? 'Выбрано' : 'Не выбрано'?></b></li>
        <li>Подача заявки на участие в торгах в нерабочее время (с 18:00 до 23:00) — 5000 руб.: <b><?=($params->serviceSendZ)? 'Выбрано' : 'Не выбрано'?></b></li>
        <li>Участие в торгах вторым и каждым последующим участником (покупателем) — 5000 руб.: <b><?=($params->serviceTorg)? 'Выбрано' : 'Не выбрано'?></b></li>
        <li>Подача заявки за 30 минут до окончания приема заявок — 7000 руб.: <b><?=($params->serviceSendLastZ)? 'Выбрано' : 'Не выбрано'?></b></li>
    </ul>
    <p>ИТОГО <b><?=$params->servicePrice?> руб.</b></p>
    <br>
</p>