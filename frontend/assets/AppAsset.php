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
        'https://fonts.googleapis.com/css?family=Playfair+Display:400,400i,700,700i|Source+Sans+Pro:200,200i,300,300i,400,400i,600,600i,700,700i,900,900i&display=swap',
        'font-faces/metropolis/metropolis.css',
        'css/animate.min.css',
        'css/main.css',
        'css/plugin.css',
        'css/style.css',
        'css/your-style.css'
    ];
    public $js = [
        'js/jquery-2.2.4.min.js',
        'js/plugins.js',
        'js/custom-core.js'
    ];
    public $depends = [
        // 'yii\web\YiiAsset',
        // 'yii\bootstrap\BootstrapAsset',
    ];
}
