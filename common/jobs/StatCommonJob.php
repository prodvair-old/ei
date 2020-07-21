<?php

namespace common\jobs;

use Yii;
use yii\helpers\Json;
use yii\base\BaseObject;
use yii\db\Query;
use yii\web\NotFoundHttpException;

use common\models\db\User;
use common\models\db\Stat;
use common\components\IntCode;

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
        $torg = (new Query())
            ->select(['torg.id'])
            ->from('{{%torg}}');
        $lot = (new Query())
            ->select(['lot.id'])
            ->from('{{%lot}}');
        $document = (new Query())
            ->select(['document.id'])
            ->from('{{%document}}');
        $user = (new Query())
            ->select(['user.id'])
            ->from('{{%user}}');

        // add query if user is Agent or Arbitrator
        if ($user_id) {
            $lot
                ->innerJoin('{{%torg}}', 'lot.torg_id=torg.id');

            $user_model = User::findOne($user_id);

            // bankrupt property, arbitration manager
            if ($user_model->role == User::ROLE_ARBITRATOR && ($manager_id = $user_model->getManagerId())) {
                
                $lot->innerJoin('{{%torg_debtor}}', 'torg.id=torg_debtor.torg_id AND torg_debtor.manager_id=' . $manager_id)
                    ->addSelect('torg_debtor.case_id');
                
                $torg->innerJoin('{{%torg_debtor}}', 'torg.id=torg_debtor.torg_id AND torg_debtor.manager_id=' . $manager_id)
                    ->addSelect('torg_debtor.case_id');
            }
            
            // pledge (zalog) property, ordinary user
            if ($user_model->role == User::ROLE_AGENT) {
                $lot->innerJoin('{{%torg_pledge}}', 'torg.id=torg_pledge.torg_id AND torg_pledge.user_id=' . $user_model->id);
                $torg->innerJoin('{{%torg_pledge}}', 'torg.id=torg_pledge.torg_id AND torg_pledge.user_id=' . $user_model->id);
            }

            
            $document
                ->where(['model' => IntCode::LOT, 'document.parent_id' => $lot->select('lot.id')])
                ->orWhere(['model' => IntCode::CASEFILE, 'document.parent_id' => $lot->select('case_id')])
                ->orWhere(['model' => IntCode::TORG, 'document.parent_id' => $torg->select('torg.id')])
                ->orWhere(['model' => IntCode::CASEFILE, 'document.parent_id' => $torg->select('case_id')]);
        }

        $vars['auction']['value'] = $torg->count();
        $vars['lot']['value'] = $lot->count();
        $vars['document']['value'] = $document->count();
        $vars['user']['value'] = $user_id ? -1 : $user->count();

        return $vars;
    }
}
