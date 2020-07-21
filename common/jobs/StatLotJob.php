<?php

namespace common\jobs;

use Yii;
use yii\helpers\Json;
use yii\base\BaseObject;
use yii\db\Query;
use yii\web\NotFoundHttpException;

use common\models\db\Lot;
use common\models\db\User;
use common\models\db\Stat;

/**
 * Calculate lot stat values.
 */
class StatLotJob extends BaseObject implements \yii\queue\JobInterface
{
    /** @var string $sid */
    public $sid;
    /** @var integer $user_id */
    public $user_id;

    /**
     * Calculate and save new Stat values.
     */
    public function execute($queue)
    {
        $sid = $this->user_id ? $this->sid . '_' . $this->user_id : $this->sid;
        if (!($model = Stat::findOne(['sid' => $sid])))
            throw new NotFoundHttpException(Yii::t('app', 'The requested model "{sid}" does not exist.', ['sid' => $this->sid]));
        $model->defs = Json::encode($this->updateValues(Json::decode($model->defs), $this->user_id));
        $model->updated_at = time();
        $model->save(false);
    }
    
    /**
     * Update Stat values.
     * 
     * @param array   $vars
     * @param integer $user_id
     * @return array
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
            
            $trace->where(['in', 'lot_trace.lot_id', $query]);
            $order->where(['in', 'order.lot_id', $query]);
            $wish->where(['in', 'wish_list.lot_id', $query]);
        }
        
        $vars['trace']['value'] = $trace->scalar();
        $vars['order']['value'] = $order->scalar();
        $vars['wish']['value']  = $wish->scalar();

        return $vars;
    }
}
