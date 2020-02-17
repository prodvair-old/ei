<?php
namespace backend\models;

use Yii;
use \yii\base\Module;
use common\models\Query\HistoryAdmin;

// Добавление итории по админке

class HistoryAdd extends Module
{
    public function singIn($status, $message, $messageJson = null, $user = null)
    {
        $history = new HistoryAdmin();

        if ($user) {
            $history->userId        = $user->id;
            $history->userRole      = $user->role;
        } else {
            $history->userId        = Yii::$app->user->id;
            $history->userRole      = Yii::$app->user->identity->role;
        }
        
        $history->typeId        = 1;
        $history->statusId      = $status;
        $history->message       = $message;
        $history->messageJson   = $messageJson;

        return $history->save();
    }
    public function singOut($status, $message, $messageJson = null)
    {
        $history = new HistoryAdmin();

        $history->userId        = Yii::$app->user->id;
        $history->userRole      = Yii::$app->user->identity->role;
        $history->typeId        = 2;
        $history->statusId      = $status;
        $history->message       = $message;
        $history->messageJson   = $messageJson;

        return $history->save();
    }
}