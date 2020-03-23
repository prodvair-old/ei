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
    /* @var string $view */
    public $view;

    /**
     * Отправить соответствующее сообщение подписчику
     */
    public function execute($queue)
    {
        //  найти текст email subject по $view
        if (!($param = Settings::findOne(['param' => $view])))
            throw new NotFoundHttpException('Параметр - ' . $view . ' не найден.');
        
        $subject = $param->value;
        
        // найти Пользователя, для определения email
        if (!($user = User::findOne(['id' => $this->user_id])))
            throw new NotFoundHttpException('Пользователь с Id - ' . $this->user_id . ' не существует.');
        
        // найти Лот
        if (!($lot = Lots::find()->where(['id' => $this->lot_id])->select(['id', 'title'])->one()))
            throw new NotFoundHttpException('Лот с Id - ' . $this->lot_id . ' не существует.');

        // отправить сообщение
        Yii::$app->mailer->compose(['html' => ($view . '-html'), 'text' => ($view . '-text')], ['user' => $user, 'lot' => $lot])
            ->setFrom([Yii::$app->params['email']['support'] => (Yii::$app->name . ' robot')])
            ->setTo($user->email)
            ->setSubject($subject)
            ->send();
        }
}
