<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language'=>'ru-RU',
    'sourceLanguage'=>'ru-RU',
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'formatter' => [
            'decimalSeparator' => '.',
            'thousandSeparator' => ' ',
            'currencyCode' => 'RUB',
       ],
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
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
            //'suffix' => '.html',
            'rules' => [
                
                '' => 'site/index',

                '/about'   => 'pages/about',
                '/licens'  => 'pages/licens',
                '/politic' => 'pages/politic',
                '/contact' => 'pages/contact',
                '/service' => 'pages/service',
                '/faq'     => 'pages/faq',
                '/sitemap' => 'pages/sitemap',

                '/login'      => 'site/login',
                '/logout'     => 'site/logout',
                '/signup'     => 'site/signup',
                '/signup/emailcheck' => 'site/emailcheck',
                '/repass'     => 'site/repass',
                '/image'      => 'site/image',
                
                '/test' => 'test/index',
                '/404' => 'site/error',

                '/arbitrs'        => 'arbitr/list',
                '/arbitrs/<arb_id:\d+>' => 'arbitr/arbitr_page',

                '/doljniks'         => 'doljnik/list',
                '/doljniks/<bnkr_id:\d+>' => 'doljnik/doljnik_page',

                '/profile'         => 'user/index',
                '/profile/setting' => 'user/setting',

                

                '/sitemap.xml' => 'sitemap/index',
                '/sitemap_other_page.xml' => 'sitemap/other',
                '/sitemap_lots-<category_lot:(transport_i_tekhnika|nedvizhimost|oborudovanie|selskoe_hozyajstvo|imushchestvennyj_kompleks|tovarno-materialnye_cennosti|debitorskaya_zadolzhennost|cennye_bumagi_nma_doli_v_ustavnyh_kapitalah|syre|prochee|lot-list)>.xml' => 'sitemap/lots',
                '/sitemap_lots-filter.xml' => 'sitemap/lotsfilter',
                '/sitemap_lots-arrest-filter.xml' => 'sitemap/lotsarrestfilter',
                '/sitemap_lots-arrest-<limit:\d+>.xml' => 'sitemap/lotsarrest',
                '/sitemap_arbitrs-<is_have:(is_have_lot|is_not_have_lot)>.xml' => 'sitemap/arbtr',
                '/sitemap_bankrupts-<is_type:(company|person)>-<limit:\d+>.xml' => 'sitemap/bnkr',
                
                '/<type:(bankrupt|arrest)>'                                     => 'lot/index',
                '/<type:(bankrupt|arrest)>/<category>'                          => 'lot/category',
                '/<type:(bankrupt|arrest)>/<category>/<subcategory>'            => 'lot/subcategory',
                '/<type:(bankrupt|arrest)>/<category>/<subcategory>/<id:\d+>'   => 'lot/lot_page',
                '/<type:(bankrupt|arrest)>/<category>/<subcategory>/<region>'   => 'lot/region',
            ]
        ],
        'assetManager' => [
            'basePath' => '@webroot/assets',
            'baseUrl' => '@web/assets'
        ],  
        'request' => [
            'baseUrl' => ''
        ]
    ],
    'params' => $params,
];
