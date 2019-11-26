<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class ContactForm extends Model
{
    public $name;
    public $phone;
    public $verifyCode;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['name', 'phone', 'captcha'], 'required'],
        ];
    }


    /**
     * Sends an email to the specified email address using the information collected by this model.
     *
     * @param string $email the target email address
     * @return bool whether the email was sent
     */
    public function sendEmail($type)
    {
        if (!$this->validate()) {
            return null;
        }

        return Yii::$app->mailer_support->compose(
                ['html' => 'contactForm-html'],
                ['type' => $type, 'param'=>$this]
            )
            ->setTo($email)
            ->setFrom(['support@ei.ru' => 'Поддержка – '.Yii::$app->name])
            ->setTo('support@ei.ru')
            ->setSubject('Форма обратной связи')
            ->send();
    }
}
