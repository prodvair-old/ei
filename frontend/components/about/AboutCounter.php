<?php
namespace frontend\components\about;

use yii\base\Widget;

class AboutCounter extends Widget
{
    // public $lot;

    public function run()
    {
        return $this->render('aboutCounter');
    }
}