<?php
namespace console\rbac;
 
use Yii;
use yii\rbac\Rule;
use common\models\db\User;
 
class UserRoleRule extends Rule
{
    public $name = 'userRole';
 
    public function execute($user_id, $item, $params)
    {
        if (!Yii::$app->user->isGuest) {
            $role = Yii::$app->user->identity->role;
            if ($item->name === 'admin') {
                return $role == User::ROLE_ADMIN;
            } elseif ($item->name === 'agent') {
                 return $role == User::ROLE_ADMIN || $ROLE == User::ROLE_AGENT;
            } elseif ($item->name === 'manager') {
                 return $role == User::ROLE_ADMIN || $ROLE == User::ROLE_MANAGER;
            } elseif ($item->name === 'arbitrator') {
                 return $role == User::ROLE_ADMIN || $ROLE == User::ROLE_AGENT || User::ROLE_ARBITRATOR;
            } elseif ($item->name === 'user') {
                 return $role == User::ROLE_ADMIN || $ROLE == User::ROLE_MANAGER || $ROLE == User::ROLE_AGENT || User::ROLE_ARBITRATOR || User::ROLE_USER;
            }
        }
        return false;
    }
}
