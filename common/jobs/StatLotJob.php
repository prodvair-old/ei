<?php

namespace common\jobs;

use Yii;
use yii\helpers\Json;
use yii\base\BaseObject;
use yii\db\Query;
use yii\web\NotFoundHttpException;

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
        if (!($model = Stat::findOne(['sid' => $this->sid])))
            throw new NotFoundHttpException(Yii::t('app', 'The requested model "{sid}" does not exist.', ['sid' => $this->sid]));
        $model->defs = Json::encode($this->updateValues(Json::decode($model->defs)));
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
        $vars['trace']['value'] = (new Query())
            ->select(['count(*) AS trace_count'])
            ->distinct('lot_id')
            ->from('{{%lot_trace}}')
            ->scalar();
        $vars['order']['value'] = (new Query())
            ->select(['count(*) AS order_count'])
            ->from('{{%order}}')
            ->scalar();
        $vars['wish']['value'] = (new Query())
            ->select(['count(*) AS wish_count'])
            ->from('{{%wish_list}}')
            ->scalar();
        return $vars;
    }
}
