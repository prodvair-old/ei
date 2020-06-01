<?php

namespace backend\modules\admin\assets;

use yii\web\AssetBundle;

/**
 * Load more model asset.
 */
class LoadMoreAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    ];
    public $js = [
        'js/load_more.min.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
