<?php
namespace console\rbac;

use Yii;
use yii\rbac\Rule;
use common\models\db\User;
use common\models\db\Arbitrator;

/**
 * Admin and Manager can work with orders (Order), but 
 * Agent can only orders (Order) that created itself and 
 * Arbitrator only orders that was assigned for him and
 * User can only orders that created themselves.
 */
class OwnOrderRule extends Rule
{
    public $name = 'ownOrder';

    /**
     * @param string|integer $user the user ID.
     * @param Item $item the role or permission that this rule is associated with
     * @param array $params parameters passed to ManagerInterface::checkAccess().
     * @return boolean a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($user_id, $item, $params)
    {
        if (isset($params['model']) && !(strpos(get_class($params['model']), 'Order') === false)) {
            if (Yii::$app->user->identity->role == User::ROLE_AGENT)
                return $params['model']->lot->torg->pledge->user_id == $user_id;
            if (Yii::$app->user->identity->role == User::ROLE_ARBITRATOR)
                return $params['model']->lot->torg->debtor->manager_id == Arbitrator::getManagerIdBy($user_id);
            if (Yii::$app->user->identity->role == User::ROLE_USER)
                return $params['model']->user_id == $user_id;
        } else
            return false;
    }
}
