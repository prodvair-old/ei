<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Class LotAsset
 * @package frontend\assets
 */
class ScrollAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $js = [
        'js/infinityScroll.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset'
    ];
    public $jsOptions = ['position' => \yii\web\View::POS_END];
}
