<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use insolita\wgadminlte\LteBox;
use insolita\wgadminlte\CollapseBox;
use insolita\wgadminlte\LteConst;
use kartik\select2\Select2;

use backend\models\UserAccess;

/* @var $this yii\web\View */
/* @var $model backend\models\Editors\LotEditor */
/* @var $form ActiveForm */

$this->params['h1'] = 'Лог №'.$history->id;
$this->title = 'Лог - '.$history->user->username;

?>

<? if (UserAccess::forAdmin()) { ?>
<?php CollapseBox::begin([
            'title' => 'Пользователь', 
            'type'=>LteConst::TYPE_DEFAULT
        ])?>
    <h3><?=$history->user->info['firstname'].' '.$history->user->info['lastname']?></h3>
    <ul>
        <li>Роль: <b><?=UserAccess::getRole($history->user->role)?></b></li>
        <li>E-Mail: <b><?=$history->user->info['contacts']['emails'][0]?></b></li>
        <li>Номер телефона: <b><?=$history->user->info['contacts']['phones'][0]?></b></li>
    </ul>
<?php CollapseBox::end()?>
<? } ?>

<?php LteBox::begin(['title' => 'Информация', 'type'=>(($history->statusId == 1)? LteConst::TYPE_SUCCESS : LteConst::TYPE_DANGER )])?>
    <p>
        Роль пользователя: <b><?=UserAccess::getRole($history->userRole)?></b><br>
        Статус: <b><?=$history->status?></b><br>
        Страница: <b><?=$history->page?></b><br>
        Тип сообщения: <b><?=$history->type?></b><br>
        <hr>
        Сообщение:
        <p><?=$history->message?></p>
        <hr>
        Сообщение JSON: 
        <pre><?=json_encode($history->messageJson,JSON_UNESCAPED_UNICODE)?></pre>
    </p>
<?php LteBox::end()?>
