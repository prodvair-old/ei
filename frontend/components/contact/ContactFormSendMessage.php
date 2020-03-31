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
            $model->name    = Yii::$app->user->identity->info['firstname'];
            $model->email   = Yii::$app->user->identity->firstEmail;
            $model->phone   = Yii::$app->user->identity->firstPhone;
        }

        return $this->render('contactFormSendMessage', ['model' => $model]);
    }
}