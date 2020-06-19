<?php
namespace console\rbac;

use Yii;
use yii\rbac\Rule;
use common\models\db\User;
use common\models\db\Arbitrator;

/**
 * Admin and Manager can work with lots (Lot), but 
 * Agent can only lots (Lot) that created itself and 
 * Arbitrator only lots that was assigned for him.
 */
class OwnLotRule extends Rule
{
    public $name = 'ownLot';

    /**
     * @param string|integer $user the user ID.
     * @param Item $item the role or permission that this rule is associated with
     * @param array $params parameters passed to ManagerInterface::checkAccess().
     * @return boolean a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($user_id, $item, $params)
    {
        if (isset($params['model']) && !(strpos(get_class($params['model']), 'Lot') === false)) {
            if (Yii::$app->user->identity->role == User::ROLE_AGENT)
                return $params['model']->torg->pledge->user_id == $user_id;
            if (Yii::$app->user->identity->role == User::ROLE_ARBITRATOR)
                return $params['model']->torg->debtor->manager_id == Arbitrator::getManagerIdBy($user_id);
        } else
            return false;
    }
}
