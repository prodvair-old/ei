<?php
namespace console\rbac;

use Yii;
use yii\rbac\Rule;
use common\models\User;

/**
 * Checks if comment.post_id matches for Posts Ids of current User
 */
class OwnCommentRule extends Rule
{
    public $name = 'ownComment';

    /**
     * @param string|integer $user_id the user ID.
     * @param Item $item the role or permission that this rule is associated with
     * @param array $params parameters passed to ManagerInterface::checkAccess().
     * @return boolean a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($user_id, $item, $params)
    {
        // admin can reply for all comments 
        if(!isset($params['comment']) || Yii::$app->user->identity->group == User::GROUP_ADMIN)
            return true;
        return $params['comment']->model == 1    
            ? in_array($params['comment']->parent_id, $params['comment']->getUserPosts($user_id))
            : true;
    }
}
