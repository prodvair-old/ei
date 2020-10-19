<?php

namespace frontend\modules\components;

use common\models\db\Subscription;
use yii\base\Component;

/**
 * Class AccessManager
 * @package frontend\modules\components
 */
class AccessManager extends Component
{

    private $isSubscriber = null;

    public function isSubscriber($userId, $tariffId = 1) //TODO tariffId fix
    {
        if ($userId === null) {
            return false;
        }

        if ($this->isSubscriber !== null) {
            return $this->isSubscriber;
        }

        $sub = Subscription::findOne([
            'user_id'   => $userId,
            'tariff_id' => $tariffId,
            ['>', 'till_at', time()]
        ]);

        if ($sub) {
            if ($sub->invoice->paid === true) {
                return $this->isSubscriber = true;
            }
        }

        return $this->isSubscriber = false;
    }

}