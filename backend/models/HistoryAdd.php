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

    public function edit($status, $page, $message, $messageJson = null, $user = null)
    {
        $history = new HistoryAdmin();

        $history->userId        = Yii::$app->user->id;
        $history->userRole      = Yii::$app->user->identity->role;
        $history->typeId        = 3;
        $history->statusId      = $status;
        $history->message       = $message;
        $history->messageJson   = $messageJson;
        $history->page          = $page;

        return $history->save();
    }
    public function add($status, $page, $message, $messageJson = null, $user = null)
    {
        $history = new HistoryAdmin();

        $history->userId        = Yii::$app->user->id;
        $history->userRole      = Yii::$app->user->identity->role;
        $history->typeId        = 4;
        $history->statusId      = $status;
        $history->message       = $message;
        $history->messageJson   = $messageJson;
        $history->page          = $page;

        return $history->save();
    }
    public function remove($status, $page, $message, $messageJson = null, $user = null)
    {
        $history = new HistoryAdmin();

        $history->userId        = Yii::$app->user->id;
        $history->userRole      = Yii::$app->user->identity->role;
        $history->typeId        = 5;
        $history->statusId      = $status;
        $history->message       = $message;
        $history->messageJson   = $messageJson;
        $history->page          = $page;

        return $history->save();
    }
    public function published($status, $page, $message, $messageJson = null, $user = null)
    {
        $history = new HistoryAdmin();

        $history->userId        = Yii::$app->user->id;
        $history->userRole      = Yii::$app->user->identity->role;
        $history->typeId        = 6;
        $history->statusId      = $status;
        $history->message       = $message;
        $history->messageJson   = $messageJson;
        $history->page          = $page;

        return $history->save();
    }
    public function unPublished($status, $page, $message, $messageJson = null, $user = null)
    {
        $history = new HistoryAdmin();

        $history->userId        = Yii::$app->user->id;
        $history->userRole      = Yii::$app->user->identity->role;
        $history->typeId        = 7;
        $history->statusId      = $status;
        $history->message       = $message;
        $history->messageJson   = $messageJson;
        $history->page          = $page;

        return $history->save();
    }
    public function export($status, $page, $message, $messageJson = null, $user = null)
    {
        $history = new HistoryAdmin();

        $history->userId        = Yii::$app->user->id;
        $history->userRole      = Yii::$app->user->identity->role;
        $history->typeId        = 8;
        $history->statusId      = $status;
        $history->message       = $message;
        $history->messageJson   = $messageJson;
        $history->page          = $page;

        return $history->save();
    }
    public function import($status, $page, $message, $messageJson = null, $user = null)
    {
        $history = new HistoryAdmin();

        $history->userId        = Yii::$app->user->id;
        $history->userRole      = Yii::$app->user->identity->role;
        $history->typeId        = 9;
        $history->statusId      = $status;
        $history->message       = $message;
        $history->messageJson   = $messageJson;
        $history->page          = $page;

        return $history->save();
    }
}