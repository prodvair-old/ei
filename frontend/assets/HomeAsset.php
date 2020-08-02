<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Class HomeAsset
 * @package frontend\assets
 */
class HomeAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $js = [
        'js/homePage.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset'
    ];
    public $jsOptions = ['position' => \yii\web\View::POS_END];
}
