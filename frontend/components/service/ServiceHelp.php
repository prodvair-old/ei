<?php
namespace frontend\components\service;

use yii\base\Widget;

class ServiceHelp extends Widget
{
    // public $lot;

    public function run()
    {
        return $this->render('serviceHelp');
    }
}