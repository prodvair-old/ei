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
  'modules' => [
      'lot' => [
          'class' => 'frontend\modules\Lot',
      ],
  ],
  'components' => [
    'socialShare' => [
      'class' => \ymaker\social\share\configurators\Configurator::class,
      'socialNetworks' => [
          'vkontakte' => [
              'class' => \ymaker\social\share\drivers\Vkontakte::class,
          ],
          'facebook' => [
              'class' => \ymaker\social\share\drivers\Facebook::class,
          ],
          'odnoklasniki' => [
              'class' => \ymaker\social\share\drivers\Odnoklassniki::class,
          ],
          'telegram' => [
            'class' => \ymaker\social\share\drivers\Telegram::class,
          ],
      ],
      'enableIcons' => true,
      // 'icons' => [
      //     \ymaker\social\share\drivers\Twitter::class => 'icon-twitter', // CSS class
      //     \ymaker\social\share\drivers\Facebook::class => 'icon-facebook',  // CSS class
      // ],
    ],
    'formatter' => [
      'decimalSeparator' => '.',
      'thousandSeparator' => ' ',
      'language' => 'ru-RU',
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
        '/' => 'site/index',

        '/test'    => 'test/index',
        '/test/str'    => 'test/str',
        '/test/del-lots'    => 'test/del-lots',

        '/about'    => 'pages/about',
        '/license'  => 'pages/license',
        '/policy'   => 'pages/policy',
        '/contact'  => 'pages/contact',
        '/faq'      => 'pages/faq',
        '/sitemap'  => 'pages/sitemap',

        // '/tariffs'     => 'pay/tariffs',
        // '/tariffs/pay' => 'pay/payments',

        '/service'             => 'services/index',
        '/service/agent'       => 'services/agent',
        '/service/ecp'         => 'services/ecp',
        '/service/specialist'  => 'services/specialist',
        '/service/lot'         => 'services/lot',


        '/login'      => 'site/login',
        '/logout'     => 'site/logout',
        '/signup'     => 'site/signup',
        '/signup/emailcheck'        => 'site/emailcheck',
        '/verify-email'             => 'site/verify-email',
        '/request-password-reset'   => 'site/request-password-reset',
        '/reset-password'           => 'site/reset-password',
        '/image'                    => 'site/image',

        '/404'  => 'site/error',

        '/arbitrazhnye-upravlyayushchie'                => 'arbitr/list',
        '/arbitrazhnye-upravlyayushchie/<arb_id:\d+>'   => 'arbitr/arbitr_page',

        '/sro'              => 'sro/list',
        '/sro/<sro_id:\d+>' => 'sro/sro-page',

        '/dolzhniki'                    => 'doljnik/list',
        '/dolzhniki/<bnkr_id:\d+>'      => 'doljnik/doljnik_page',


        '/profile'               => 'user/index',

        '/profile/notification'  => 'profile/notification',

        '/profile/setting'       => 'user/setting',
        '/profile/setting_image' => 'user/setting_image',

        '/profile/favorite'      => 'user/wish_list',

        '/profile/lots'          => 'user/lots',
        '/profile/get-arrest-bankrupt' => 'user/get-arrest-bankrupt',
        '/profile/lots/<id:\d+>' => 'user/edit-lot',

        '/profile/lots/import'   => 'user/import-lots',
        '/profile/lots/zalog-image-del'   => 'user/lot-images-del',
        '/profile/lots/add'      => 'user/add-lot',


        '/profile/lot-images'    => 'user/lot-images',
        '/profile/lot-category'  => 'user/lot-category',
        '/profile/lot-remove'    => 'user/lot-remove',
        '/profile/lot-status'    => 'user/lot-status',

        '/wishlist/unsubscribe' => 'wishlist/unsubscribe',

        '/lots-arrest-<limit:\d+>.xlsx' => 'lots/arrest',
        '/arbitrs-<limit:\d+>.xlsx' => 'lots/arbitrs',


        '/sitemap.xml'                      => 'sitemap/index',
        '/sitemap-<type>-<limit:\d+>.xml'   => 'sitemap/pages',
        '/sitemap-<type>.xml'               => 'sitemap/pages',

        '/get-arrest-bankrupt'  => 'lots/arrest-bankrupt',

        '/map'              => 'lot/map',
        '/load-category'    => 'lot/load_category',
        '/wish-list-edit'   => 'lot/wish_list',

        '/lot/load-sub-categories' => '/lot/lot/load-sub-categories',

        '/<type>'                     => 'lot/lot/index',
        '/<type>/<category>'          => 'lot/lot/index',
        '/<type>/<category>/<id:\d+>' => '/lot/lot/view',


//        '/<type>'                                     => 'lot/index',
//        '/<type>/<category>'                          => 'lot/search',
//        '/<type>/<category>/<subcategory>'            => 'lot/search',
//        '/<type>/<category>/<subcategory>/<id:\d+>'   => 'lot/page',
//        '/<type>/<category>/<subcategory>/<region>'   => 'lot/search',
      ]
    ],
    /*
    'assetManager' => [
      'basePath' => '@webroot/assets',
      'baseUrl' => '@web/assets'
    ],
    'request' => [
      'baseUrl' => ''
    ],*/
  ],
  'params' => $params,
];
