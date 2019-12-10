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
  'language' => 'ru-RU',
  'name' => 'Единый агрегатор торгов',
  'sourceLanguage' => 'ru-RU',
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
        '/test' => 'test/index',


        '/about'    => 'pages/about',
        '/license'  => 'pages/license',
        '/policy'   => 'pages/policy',
        '/contact'  => 'pages/contact',
        '/faq'      => 'pages/faq',
        '/sitemap'  => 'pages/sitemap',

        '/service'             => 'services/index',
        '/service/agent'       => 'services/agent',
        '/service/ecp'         => 'services/ecp',
        '/service/specialist'  => 'services/specialist',

        '/login'      => 'site/login',
        '/logout'     => 'site/logout',
        '/signup'     => 'site/signup',
        '/signup/emailcheck'        => 'site/emailcheck',
        '/verify-email'             => 'site/verify-email',
        '/request-password-reset'   => 'site/request-password-reset',
        '/reset-password'           => 'site/reset-password',
        '/image'                    => 'site/image',

        '/404'  => 'site/error',

        '/arbitr-list'                                  => 'arbitr/redirect',
        '/arbitr-list/<arb_id:\d+>'                     => 'arbitr/redirect',
        '/arbitrazhnye-upravlyayushchie'                => 'arbitr/list',
        '/arbitrazhnye-upravlyayushchie/<arb_id:\d+>'   => 'arbitr/arbitr_page',

        '/sro'              => 'sro/list',
        '/sro/<sro_id:\d+>' => 'sro/sro_page',

        '/doljnik-list'                 => 'doljnik/redirect',
        '/doljnik-list/<bnkr_id:\d+>'   => 'doljnik/redirect',
        '/dolzhniki'                    => 'doljnik/list',
        '/dolzhniki/<bnkr_id:\d+>'      => 'doljnik/doljnik_page',


        '/profile'               => 'user/index',

        '/profile/setting'       => 'user/setting',
        '/profile/setting_image' => 'user/setting_image',

        '/profile/favorite'      => 'user/wish_list',

        '/profile/lots'          => 'user/lots',
        '/profile/addlots'       => 'user/addlots',
        '/profile/lots/edit'     => 'user/editlot',

        '/profile/lot-images'    => 'user/lot-images',
        '/profile/lot-category'  => 'user/lot-category',
        '/profile/lot-remove'    => 'user/lot-remove',
        '/profile/lot-status'    => 'user/lot-status',



        '/sitemap.xml'                      => 'sitemap/index',
        '/sitemap-<type>-<limit:\d+>.xml'   => 'sitemap/pages',
        '/sitemap-<type>.xml'               => 'sitemap/pages',


        '/load-category'    => 'lot/load_category',
        '/wish-list-edit'   => 'lot/wish_list',

        '/<type:(bankrupt|arrest|zalog)>'                                     => 'lot/index',
        '/<type:(bankrupt|arrest|zalog)>/<category>'                          => 'lot/search',
        '/<type:(bankrupt|arrest|zalog)>/<category>/<subcategory>'            => 'lot/search',
        '/<type:(bankrupt|arrest|zalog)>/<category>/<subcategory>/<id:\d+>'   => 'lot/page',
        '/<type:(bankrupt|arrest|zalog)>/<category>/<subcategory>/<region>'   => 'lot/search',

        '/<category>'                           => 'lot/redirect',
        '/<category>/<subcategory>'             => 'lot/redirect',
        '/<category>/<subcategory>/<id:\d+>'    => 'lot/redirect',
        '/<category>/<subcategory>/<region>'    => 'lot/redirect-region',
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
