<?php
namespace frontend\components\about;

use yii\base\Widget;

class AboutNavigation extends Widget
{
    // public $lot;

    public function run()
    {
        return $this->render('aboutNavigation');
    }
}