<?php
namespace frontend\components\contact;

use yii\base\Widget;

class ContactMap extends Widget
{
    // public $lot;

    public function run()
    {
        return $this->render('contactMap');
    }
}