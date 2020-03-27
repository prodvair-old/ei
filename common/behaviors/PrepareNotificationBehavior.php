<?php

namespace common\behaviors;

use yii\base\Behavior;
use yii\queue\cli\Queue;
use common\jobs\SendNotificationJob;

class PrepareNotificationBehavior extends Behavior
{
    public function events()
    {
        return [
            Queue::EVENT_WORKER_START => 'prepareNotification',
        ];
    }

    public function prepareNotification($event)
    {
        $queue = $event->sender;
        if (!file_exists($queue->path . '/data.csv'))
            return;
        $jobs = [];
        // раскладываем все события по юзерам, у юзеров по лотам
        // пример: user_id = 485, lot_id = 101 
        // $jobs[485][101] = ['new-picture', 'price-reduction'];
        foreach(file($queue->path . '/data.csv') as $line) {
            if (count($a = str_getcsv($line)) == 3) {
                list($user_id, $lot_id, $event_name) = $a;
                if (!isset($jobs['$user_id']))
                    $jobs[$user_id] = [];

                if (!isset($jobs[$user_id][$lot_id]))
                    $jobs[$user_id][$lot_id] = [];
                    
                if (!isset($jobs[$user_id][$lot_id][$event_name]))
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
