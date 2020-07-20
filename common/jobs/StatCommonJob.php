<?php

namespace common\jobs;

use Yii;
use yii\helpers\Json;
use yii\base\BaseObject;
use yii\db\Query;
use yii\web\NotFoundHttpException;

use common\models\db\Stat;

/**
 * Calculate common stat values.
 */
class StatCommonJob extends BaseObject implements \yii\queue\JobInterface
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
        $vars['auction']['value'] = (new Query())
            ->select(['count(*) AS auction_count'])
            ->from('{{%torg}}')
            ->scalar();
        $vars['lot']['value'] = (new Query())
            ->select(['count(*) AS lot_count'])
            ->from('{{%lot}}')
            ->scalar();
        $vars['document']['value'] = (new Query())
            ->select(['count(*) AS document_count'])
            ->from('{{%document}}')
            ->scalar();
        $vars['user']['value'] = (new Query())
            ->select(['count(*) AS user_count'])
            ->from('{{%user}}')
            ->scalar();
        return $vars;
    }
}
