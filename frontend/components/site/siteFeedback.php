<?php
namespace frontend\components\site;

use yii\base\Widget;

class siteFeedback extends Widget
{
    // public $lot;

    public function run()
    {
        return $this->render('siteFeedback');
    }
}