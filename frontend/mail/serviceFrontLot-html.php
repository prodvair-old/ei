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
    <p>Подача заявки на участие по агентскому договору - <b><?=$params->agentPrice?> руб.</b></p>
    <br>
    <p>Если у Вас возникли вопросы 8-800-600-33-05<p>
    <br>
</p>