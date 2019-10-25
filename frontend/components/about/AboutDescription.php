<?php
namespace frontend\components\about;

use yii\base\Widget;

class AboutDescription extends Widget
{
    // public $lot;

    public function run()
    {
        return $this->render('aboutDescription');
    }
}