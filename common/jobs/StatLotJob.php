<?php

namespace common\jobs;

use yii\db\Query;

use common\models\db\User;
use common\models\db\Lot;
use common\components\IntCode;

/**
 * Calculate lot stat values.
 */
class StatLotJob extends StatJob implements \common\interfaces\StatInterface
{
    /**
     * @inheritdoc
     */
    public static function updateValues($vars, $user_id = false)
    {
        // make common queries if user is an Admin or Manager
        $trace = (new Query())
            ->select(['count(*) AS trace_count'])
            ->distinct('lot_id')
            ->from('{{%lot_trace}}');
        $order = (new Query())
            ->select(['count(*) AS order_count'])
            ->from('{{%order}}');
        $wish = (new Query())
            ->select(['count(*) AS wish_count'])
            ->from('{{%wish_list}}');

        // add query if user is Agent or Arbitrator
        if ($user_id) {
            $query = Lot::find()
                ->select('lot.id')
                ->distinct(false)
                ->innerJoin('{{%torg}}', 'lot.torg_id=torg.id');

            $user = User::findOne($user_id);

            // bankrupt property, arbitration manager
            if ($user->role == User::ROLE_ARBITRATOR && ($manager_id = $user->getManagerId()))
                $query->innerJoin('{{%torg_debtor}}', 'torg.id=torg_debtor.torg_id AND torg_debtor.manager_id=' . $manager_id);

            // pledge (zalog) property, ordinary user
            if ($user->role == User::ROLE_AGENT)
                $query->innerJoin('{{%torg_pledge}}', 'torg.id=torg_pledge.torg_id AND torg_pledge.user_id=' . $user->id);
            
            $trace->where(['lot_trace.lot_id' => $query]);
            $order->where(['order.lot_id' => $query]);
            $wish->where(['wish_list.lot_id' => $query]);
        }
        
        $vars['trace']['value'] = $trace->scalar();
        $vars['order']['value'] = $order->scalar();
        $vars['wish']['value']  = $wish->scalar();

        return $vars;
    }
}
