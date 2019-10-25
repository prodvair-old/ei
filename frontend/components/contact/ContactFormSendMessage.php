<?php
namespace frontend\components\contact;

use yii\base\Widget;

class ContactFormSendMessage extends Widget
{
    // public $lot;

    public function run()
    {
        return $this->render('contactFormSendMessage');
    }
}