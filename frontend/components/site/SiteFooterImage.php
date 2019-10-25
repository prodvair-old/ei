<?php
namespace frontend\components\site;

use yii\base\Widget;

class SiteFooterImage extends Widget
{
    // public $lot;

    public function run()
    {
        return $this->render('siteFooterImage');
    }
}