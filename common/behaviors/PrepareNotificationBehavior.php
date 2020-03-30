<?php

namespace common\behaviors;

use yii\base\Behavior;
use yii\queue\cli\Queue;
use common\jobs\SendNotificationJob;

/**
 * Класс для подготовки заданий отправки по одному email каждому подписчику
 * 
 * Ализируется общий список, сформированный на предыдущем этапе и
 * задания распределяются по подписчикам.
 * 
 * @see \common\jobs\KeepNotificationJob
 * @see \common\jobs\SendNotificationJob
 */

class PrepareNotificationBehavior extends Behavior
{
    public function events()
    {
        return [
            // вызвать prepareNotification после старта вокера
            Queue::EVENT_WORKER_START => 'prepareNotification',
        ];
    }

    public function prepareNotification($event)
    {
        $queue = $event->sender;
        if (!file_exists($queue->path . '/data.csv'))
            // первый проход, список еще не создан, просто пропустить данный этап
            return;
        $jobs = [];
        // разложить все события по юзерам, далее по лотам
        // пример: user_id = 485, lot_id = 101 
        // $jobs[485][101] = ['new-picture', 'price-reduction'];
        foreach(file($queue->path . '/data.csv') as $line) {
            if (count($a = str_getcsv($line)) == 3) {
                list($user_id, $lot_id, $event_name) = $a;
                if (!isset($jobs[$user_id = (int) $user_id]))
                    $jobs[$user_id] = [];

                if (!isset($jobs[$user_id][$lot_id = (int) $lot_id]))
                    $jobs[$user_id][$lot_id] = [];
                    
                if (!isset($jobs[$user_id][$lot_id][$event_name = trim($event_name)]))
                    $jobs[$user_id][$lot_id][] = $event_name;
            }
        };
        // сформировать задания по одному на каждого юзера
        foreach($jobs as $user_id => $lots) {
            $queue->push(new SendNotificationJob([
                'user_id' => $user_id,
                'lots'    => $lots,
            ]));
        }
        
        // удалить отработанный файл событий
        unlink($queue->path . '/data.csv');
    }
}
