<?php
namespace frontend\components\contact;

use yii\base\Widget;

class ContactData extends Widget
{
    // public $lot;

    public function run()
    {
        return $this->render('contactData');
    }
}