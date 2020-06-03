<?php
return [
    'bootstrap' => [
        'queue',
    ],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'queue' => [
            'class' => \yii\queue\file\Queue::class,
            'path' => '@console/runtime/queue',
            'as prep' => \common\behaviors\PrepareNotificationBehavior::class,
        ],
        'lookup' => [
            'class' => 'zacksleo\yii2\lookup\models\Lookup',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.yandex.ru',
                'username' => 'einformator@yandex.ru',
                'password' => 'cdExsWzaQ423',
                'port' => 465,
                'encryption' => 'ssl',
            ],
        ],
        'mailer_agent' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.yandex.ru',
                'username' => 'agent@ei.ru',
                'password' => 'qazwsx123',
                'port' => 465,
                'encryption' => 'ssl',
            ],
        ],
        'mailer_arbitr' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.yandex.ru',
                'username' => 'arbitr@ei.ru',
                'password' => 'qazwsx123',
                'port' => 465,
                'encryption' => 'ssl',
            ],
        ],
        'mailer_ecp' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.yandex.ru',
                'username' => 'ecp@ei.ru',
                'password' => 'qazwsx123',
                'port' => 465,
                'encryption' => 'ssl',
            ],
        ],
        'mailer_support' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.yandex.ru',
                'username' => 'support@ei.ru',
                'password' => 'qazwsx123',
                'port' => 465,
                'encryption' => 'ssl',
            ],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],

        'db' => [
            'class' => 'yii\db\Connection',
            'dsn'      => 'pgsql:host=localhost;dbname=uds',
            'username' => 'bankrupt',
            'password' => 'bankrupt',
            'charset'  => 'utf8',
            /*
            'tablePrefix' => 'eidb"."',
            'emulatePrepare' => false,
            'schemaMap' => [
                'pgsql'=> [
                    'class'=>'yii\db\pgsql\Schema',
                    'defaultSchema' => 'eidb' //specify your schema here
                ]
            ],
            */
            // Schema cache options (for production environment)
            'enableSchemaCache' => false,
            'schemaCacheDuration' => 6,
            'schemaCache' => 'cache',
        ],
        'obj' => [
            'class' => 'yii\db\Connection',
            'dsn'      => 'pgsql:host=localhost;dbname=uds',
            'username' => 'bankrupt',
            'password' => 'bankrupt',
            'charset'  => 'utf8',
            'tablePrefix' => 'obj$',
            // Schema cache options (for production environment)
            'enableSchemaCache' => false,
            'schemaCacheDuration' => 6,
            'schemaCache' => 'cache',
        ],
    ],
];
