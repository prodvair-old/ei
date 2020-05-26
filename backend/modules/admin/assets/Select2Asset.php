<?php
/**
 * Yii2 asset for Select2 plugin
 *
 * @link      https://github.com/hiqdev/yii2-asset-select2
 * @package   yii2-asset-select2
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2017, HiQDev (http://hiqdev.com/)
 * 
 * Changes: $sourcePath, $js, $css
 */

namespace backend\modules\admin\assets;

use yii\web\AssetBundle;
use yii\web\JqueryAsset;

class Select2Asset extends AssetBundle
{
    public $sourcePath = '@bower/select2/dist';

    public $js = [
        'js/select2.full.min.js',
    ];

    public $css = [
        'css/select2.min.css',
    ];

    public $depends = [
        JqueryAsset::class,
    ];

    public function init()
    {
        parent::init();

        $language = \Yii::$app->language;

        if (is_file(\Yii::getAlias("{$this->sourcePath}/js/i18n/{$language}.js"))) {
            $this->js[] = "js/i18n/{$language}.js";
        }
    }
}
