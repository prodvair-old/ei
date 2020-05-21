<?php
$params = array_merge(
    require __DIR__ . '/../../../../common/config/params.php',
    require __DIR__ . '/../../../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-admin-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\modules\admin\controllers',
    'bootstrap' => ['log'],
    'language' => 'ru-RU',
    'name' => 'Панель управления - Единый информатор',
    'sourceLanguage' => 'ru-RU',
    'modules' => [
        'uploader' => ['class' => 'sergmoro1\uploader\Module']
    ],
    'components' => [
        'authManager' => [
            'class' => 'yii\rbac\PhpManager',
            'defaultRoles' => ['user', 'agent', 'manager', 'admin'],
            'itemFile' => __DIR__ . '/../../console/rbac/items.php',
            'ruleFile' => __DIR__ . '/../../console/rbac/rules.php',
        ],
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                ''          => 'site/index',
                '/login'    => 'site/login',
                '/logout'   => 'site/logout',

                '/add-field-<type>' => 'site/add-field',

                '/historys' => 'historys/index',
                '/historys-all' => 'historys/all',

                '/<controller:\w+>' => '<controller>/index',
            ],
        ],
    ],
    'params' => $params,
];
