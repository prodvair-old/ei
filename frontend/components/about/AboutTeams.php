<?php
namespace frontend\components\about;

use yii\base\Widget;

class AboutTeams extends Widget
{
    // public $lot;

    public function run()
    {
        return $this->render('aboutTeams');
    }
}