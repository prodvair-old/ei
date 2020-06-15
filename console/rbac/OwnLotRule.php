<?php
namespace console\rbac;

use yii\rbac\Rule;
use common\models\User;

/**
 * Checks if comment.user_id matches current User Id
 */
class OwnAnswerRule extends Rule
{
    public $name = 'ownAnswer';

    /**
     * @param string|integer $user the user ID.
     * @param Item $item the role or permission that this rule is associated with
     * @param array $params parameters passed to ManagerInterface::checkAccess().
     * @return boolean a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($user_id, $item, $params)
    {
        // model = comment
        return isset($params['model']) && !(strpos(get_class($params['model']), 'Comment') === false)
            ? $params['model']->user_id == $user_id 
            : false;
    }
}
