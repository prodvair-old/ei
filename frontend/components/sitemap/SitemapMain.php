<?php
namespace frontend\components\sitemap;

use yii\base\Widget;

class SitemapMain extends Widget
{
    // public $lot;

    public function run()
    {
        return $this->render('sitemapMain');
    }
}