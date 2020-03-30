<?php

namespace common\jobs;

use Yii;
use yii\base\BaseObject;
use yii\web\NotFoundHttpException;

use common\models\User;
use common\models\Query\Settings;
use common\models\Query\Lot\Lots;

/**
 * Класс для отправки одного email подписчику о событиях связанных с лотами
 * 
 * Юзер может быть подписан на несколько лотов, 
 * по каждому из которых может быть несколько событий.
 * 
 * @see \common\models\Query\Lot\Lots
 */
class SendNotificationJob extends BaseObject implements \yii\queue\JobInterface
{
    // название параметра в \common\models\Query\Settings, в котором может быть определено назначение письма
    const PARAM_SUBJECT = 'notification_subject';
    
    /** @var integer $user_id */
    public $user_id;
    /** @var array $lots with events [lot_id => ['new-picture', 'price-reduction']] */
    public $lots;

    /**
     * Отправить сообщение подписчику
     */
    public function execute($queue)
    {
        // найти текст subject или использовать значение по умолчанию
        $subject = ($param = Settings::findOne(['param' => self::PARAM_SUBJECT]))
            ? $param->value
            : (Yii::$app->name . ': изменения в лотах.');

        // найти Пользователя
        if (!($user = User::findOne(['id' => $this->user_id])))
            throw new NotFoundHttpException('Пользователь с Id - ' . $this->user_id . ' не существует.');
        
        // найти Лоты
        $models = Lots::find()->where(['in', 'id', array_keys($this->lots)])->all();

        // отправить сообщение, если лоты в избранном и найдены
        if ($this->lots && $models)
            Yii::$app->mailer_support->compose(['html' => 'notification-html'], [
                'user'   => $user, 
                'models' => $models, 
                'lots'   => $this->lots, 
            ])
                ->setFrom([Yii::$app->params['email']['support'] => (Yii::$app->name . ' robot')])
                ->setTo($user->info['contacts']['emails'][0])
                ->setSubject($subject)
                ->send();
    }
}
