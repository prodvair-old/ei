<?php
namespace console\rbac;

use yii\rbac\Rule;

/**
 * Checks if report.user_id correspond to current user.
 */
class OwnReportRule extends Rule
{
    public $name = 'ownReport';

    /**
     * @param string|integer $user_id the user ID.
     * @param Item $item the role or permission that this rule is associated with
     * @param array $params parameters passed to ManagerInterface::checkAccess().
     * @return boolean a value indicating whether the rule permits the role or permission it is associated with.
     */
    public function execute($user_id, $item, $params)
    {
        return isset($params['model']) && !(strpos(get_class($params['model']), 'Report') === false)
            ? $params['model']->user_id == $user_id
            : false;
    }
}
