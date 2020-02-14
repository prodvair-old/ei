<?php
/**
 * Created by PhpStorm.
 * User: zein
 * Date: 8/2/14
 * Time: 11:40 AM
 */

namespace backend\assets;

use yii\web\AssetBundle;

class BowerAssets extends AssetBundle
{
    public $sourcePath = '@bower';
    public $css = [
            // 'css/adminlte.css',
        ];
    public $js = [
            'jquery/dist/jquery.min.js',
            // 'fastclick/lib/fastclick.js',
            // 'jquery-sparkline/dist/jquery.sparkline.min.js',
            // 'jquery-slimscroll/jquery.slimscroll.min.js',
            // 'chart.js/Chart.js',
        ];
    public $depends = [];
}