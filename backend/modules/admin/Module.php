<?php

namespace backend\modules\admin;

use Yii;

/**
 * Admin module definition class
 * Личный кабинет для зарегистрированных пользователей.
 */
class Module extends \yii\base\Module
{
    /**
     * @var string
     * @inheritdoc
     */
    public $controllerNamespace = 'backend\modules\admin\controllers';
    public $layout = '@backend/modules/admin/views/layouts/main.php';
}
