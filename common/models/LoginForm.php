<?php
namespace common\models;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    public $token;

    private $_user;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
            // Token
            ['token', 'string']
        ];
    }

    public function attributeLabels()
    {
    	return ArrayHelper::merge(parent::attributeLabels(),[
                'username'      => 'E-mail',
                'password'      => 'Пароль',
                'rememberMe'    => 'Запомнить меня',
        	]);
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Не верный логин или пароль.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        }
        
        return false;
    }

    public function loginAdmin()
    {
        if ($this->validate()) {
            $this->getUser();

            if ($this->_user->role !== 'user') {
                return ['status' => Yii::$app->user->login($this->_user, $this->rememberMe ? 3600 * 24 * 30 : 0)];
            } else {
                $this->addError('username', 'У вас нет доступа в панель управления!');
                return ['status' => false, 'user' => $this->getUser()];
            }
        }
        
        return ['status' => false, 'user' => $this->getUser()];
    }
    public function loginAdminToken()
    {
        if ($this->_user = User::findByToken($this->token)) {
            $this->username = $user->username;

            if ($this->_user->role !== 'user') {
                return ['status' => Yii::$app->user->login($this->_user, $this->rememberMe ? 3600 * 24 * 30 : 0)];
            } else {
                $this->addError('username', 'У вас нет доступа в панель управления!');
                return ['status' => false, 'user' => $this->getUser()];
            }
        }
        
        $this->addError('token', 'Не верный токен авторизации!');
        return ['status' => false, 'user' => $this->getUser()];
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = \common\models\db\User::findByUsername($this->username);
        }

        return $this->_user;
    }
}
