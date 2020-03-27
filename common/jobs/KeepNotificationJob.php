<?php

namespace common\jobs;

use Yii;
use yii\base\BaseObject;

/**
 * Класс для сохранения события изменения лота в общем списке
 * 
 * Чтобы юзер не получал извещение при каждом изменении лота, события сохраняются в общем списке.
 * В определенный момент список анализируется, по каждому юзеру выбираются события связанные только с ним и 
 * формируются новые задания. 
 */
class KeepNotificationJob extends BaseObject implements \yii\queue\JobInterface
{
    /* @var integer $user_id */
    public $user_id;
    /* @var integer $lot_id */
    public $lot_id;
    /* @var string $event */
    public $event;

    /**
     * Сохранить событие
     */
    public function execute($queue)
    {
        $file = fopen($queue->path . '/data.csv', 'a');
        fwrite($file, "{$this->user_id}, {$this->lot_id}, {$this->event}\n");
        fclose($file);
    }
}
