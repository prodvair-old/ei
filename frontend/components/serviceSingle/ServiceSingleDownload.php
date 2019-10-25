<?php
namespace frontend\components\serviceSingle;

use yii\base\Widget;

class ServiceSingleDownload extends Widget
{
    // public $lot;

    public function run()
    {
        return $this->render('serviceSingleDownload');
    }
}