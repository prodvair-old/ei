<?php

namespace backend\jobs;

use yii\base\BaseObject;
use yii\web\NotFoundHttpException;

/**
 * Класс для отправки email подписчикам лота.
 */
class SendNotificationJob extends BaseObject implements \yii\queue\JobInterface
{
    /* @var integer $user_id */
    public $user_id;
    /* @var integer $lot_id */
    public $lot_id;
    /* @var string $event */
    public $event;

    /**
     * Отправить соответствующее сообщение подписчику
     */
    public function execute($queue)
    {
        //  найти шаблон email по $event
        if (!($param = Settings::findOne(['param' => $event])))
            throw new NotFoundHttpException('Параметр - ' . $event . ' не найден.');
        
        $subject = $param->value;
        
        // найти Пользователя, для определения email
        if (!($user = User::findOne(['id' => $this->user_id])))
            throw new NotFoundHttpException('Пользователь с Id - ' . $this->user_id . ' не существует.');
        
        Yii::$app->mailer->compose(['html' => 'event-' . $event . '-html', 'text' => 'event-' . $event . '-text'], ['user' => $user])
            ->setFrom([Yii::$app->params['email']['support'] => (Yii::$app->name . ' robot')])
            ->setTo($user->email)
            ->setSubject($subject)
            ->send();
        }
}
