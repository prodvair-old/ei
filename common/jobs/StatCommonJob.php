<?php

namespace common\jobs;

use yii\db\Query;

use common\models\db\User;
use common\components\IntCode;

/**
 * Calculate common stat values.
 */
class StatCommonJob extends StatJob implements \common\interfaces\StatInterface
{
    /**
     * @inheritdoc
     */
    public static function updateValues($vars, $user_id = false)
    {
        // make common queries if user is an Admin or Manager
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
            if ($is_arbitrator = ($user_model->role == User::ROLE_ARBITRATOR && ($manager_id = $user_model->getManagerId()))) {
                
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
                ->orWhere(['model' => IntCode::TORG, 'document.parent_id' => $torg->select('torg.id')]);

            if ($is_arbitrator) {
                $document
                    ->orWhere(['model' => IntCode::CASEFILE, 'document.parent_id' => $lot->select('case_id')])
                    ->orWhere(['model' => IntCode::CASEFILE, 'document.parent_id' => $torg->select('case_id')]);
            }
        }

        $vars['auction']['value'] = $torg->count();
        $vars['lot']['value'] = $lot->count();
        $vars['document']['value'] = $document->count();
        $vars['user']['value'] = $user_id ? -1 : $user->count();

        return $vars;
    }
}
