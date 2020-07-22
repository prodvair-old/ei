<?php

namespace common\jobs;

use Yii;
use yii\helpers\Json;
use yii\base\BaseObject;
use yii\web\NotFoundHttpException;

use common\models\db\Stat;

/**
 * Calculate stat values.
 */
class StatJob extends BaseObject implements \yii\queue\JobInterface
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
}
