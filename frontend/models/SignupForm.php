<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\db\User;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $phone;
    public $email;
    public $password;
    public $passwordConfirm;
    public $checkPolicy;

    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['phone', 'trim'],
            ['phone', 'required'],
            // ['phone', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            ['phone', 'string', 'min' => 5, 'max' => 12],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetAttribute' => 'username', 'targetClass' => User::className(), 'message' => 'Такой пользователь уже зарегистрирован.'],
            
            [['password', 'passwordConfirm'], 'required'],
            [['password', 'passwordConfirm'], 'string', 'min' => 6],
            ['passwordConfirm', 'compare', 'compareAttribute'=>'password', 'message'=>"Пароли не совподают!" ],

            ['checkPolicy', 'required'],
            ['checkPolicy', 'compare', 'compareValue' => 1, 'message' => 'Поставте галочку на соглашение!'], 
        ];
    }

    /**
     * @return bool|null
     * @throws \yii\base\Exception
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->username = $this->email;
        $user->email = $this->email;

        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();
        return $user->save() && $this->sendEmail($user);

    }

    /**
     * Sends confirmation email to user
     * @param User $user user model to with email should be send
     * @return bool whether the email was sent
     */
    protected function sendEmail($user)
    {
        return Yii::$app
            ->mailer_support
            ->compose(
                ['html' => 'emailVerify-html'],
                ['user' => $user, 'password'=>$this->password]
            )
            ->setFrom(['support@ei.ru' => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Подтверите регистрацию на сайте ei.ru')
            ->send();
    }
}
