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
    public $email;
    public $phone;
    public $message;
    public $checkPolicy;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['name', 'email', 'phone', 'message'], 'required'],
            ['phone', 'string', 'min' => 5, 'max' => 12],
            ['message', 'string', 'min' => 10],
            ['email', 'email'],
            ['checkPolicy', 'required'],
            ['checkPolicy', 'compare', 'compareValue' => 1, 'message' => 'Поставте галочку на соглашение!'], 
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

        return Yii::$app->mailer_support->compose(
                ['html' => 'contactForm-'.$type.'-html'],
                ['param'=>$this]
            )
            ->setFrom(['support@ei.ru' => 'Поддержка – '.Yii::$app->name])
            ->setTo('support@ei.ru')
            ->setSubject('Форма обратной связи')
            ->send();
    }
}
