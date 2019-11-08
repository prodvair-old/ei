<?php
namespace frontend\components\contact;

use yii\base\Widget;

class ContactFindSociety extends Widget
{
    public $vk;
    public $google;

    public function run()
    {
        return $this->render('contactFindSociety', [
            'vk' => $this->vk, 
            'google' => $this->google,
        ]);
    }
}