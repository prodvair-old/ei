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
    'name'=>'Единый агрегатор торгов',
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
                '/license'  => 'pages/license',
                '/policy' => 'pages/policy',
                '/contact' => 'pages/contact',
                // '/service' => 'pages/service',
                '/faq'     => 'pages/faq',
                '/sitemap' => 'pages/sitemap',
                
                '/service'             => 'services/index',
                '/service/agent'       => 'services/agent',
                '/service/ecp'         => 'services/ecp',
                '/service/specialist'  => 'services/specialist',

                '/login'      => 'site/login',
                '/logout'     => 'site/logout',
                '/signup'     => 'site/signup',
                '/signup/emailcheck'        => 'site/emailcheck',
                '/request-password-reset'   => 'site/request_password_reset',
                '/reset-password'           => 'site/reset-password',
                '/image'      => 'site/image',
                
                '/test' => 'test/index',
                '/404' => 'site/error',

                '/arbitrazhnye-upravlyayushchie'        => 'arbitr/list',
                '/arbitrazhnye-upravlyayushchie/<arb_id:\d+>' => 'arbitr/arbitr_page',

                '/sro'        => 'sro/list',
                '/sro/<sro_id:\d+>' => 'sro/sro_page',

                '/dolzhniki'         => 'doljnik/list',
                '/dolzhniki/<bnkr_id:\d+>' => 'doljnik/doljnik_page',

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
                
                
                '/load-category' => 'lot/load_category',
                '/wish-list-edit' => 'lot/wish_list',
                '/<type:(bankrupt|arrest)>'                                     => 'lot/index',
                '/<type:(bankrupt|arrest)>/<category>'                          => 'lot/search',
                '/<type:(bankrupt|arrest)>/<category>/<subcategory>'            => 'lot/search',
                '/<type:(bankrupt|arrest)>/<category>/<subcategory>/<id:\d+>'   => 'lot/page',
                '/<type:(bankrupt|arrest)>/<category>/<subcategory>/<region>'   => 'lot/search',
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
