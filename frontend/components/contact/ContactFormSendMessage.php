<?php
namespace frontend\components\contact;

use Yii;
use yii\base\Widget;
use frontend\models\ContactForm;

class ContactFormSendMessage extends Widget
{
    // public $lot;

    public function run()
    {
        $model = new ContactForm();

        if (!Yii::$app->user->isGuest) {
            $model->name    = Yii::$app->user->identity->profile->first_name;
            $model->email   = Yii::$app->user->identity->email;
            $model->phone   = Yii::$app->user->identity->profile->phone;
        }

        return $this->render('contactFormSendMessage', ['model' => $model]);
    }
}