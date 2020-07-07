<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        // 'https://fonts.googleapis.com/css?family=Playfair+Display:400,400i,700,700i|Source+Sans+Pro:200,200i,300,300i,400,400i,600,600i,700,700i,900,900i&display=swap',
        

        'css/data_picker.css',
        'css/custom.min.css',
        'css/your-style.css?v1.3',
        'css/map.css?v1.0',
        'https://cdnjs.cloudflare.com/ajax/libs/fotorama/4.6.4/fotorama.css'
    ];
    public $js = [
        'js/jquery-2.2.4.min.js',
        // 'js/costom-plugins/jquery.serializejson.min.js',
        'https://cdn.jsdelivr.net/npm/jquery.maskedinput@1.4.1/src/jquery.maskedinput.js',
        'js/plugins.js',
        'js/costom-plugins/toast.js',
        'js/custom-core.js?v=1.06',
        'https://cdnjs.cloudflare.com/ajax/libs/fotorama/4.6.4/fotorama.js',
        // 'https://api-maps.yandex.ru/2.1-stable/?load=package.standard&lang=ru-RU',
        'https://cdn.jsdelivr.net/npm/vue/dist/vue.js',
        'js/data_picker.js',
        'https://api-maps.yandex.ru/2.1/?apikey=c779baf7-d09e-4558-9661-55d19272043f&lang=ru_RU',
        'js/map.js',
        'js/scripts.min.js?v=2.12',
    ];
    public $depends = [
        // 'yii\web\YiiAsset',
        // 'yii\bootstrap\BootstrapAsset',
    ];
    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
}
