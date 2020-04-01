<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $verification_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 * @property integer $ownerId
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = false;
    const STATUS_INACTIVE = false;
    const STATUS_ACTIVE = true;

    public $firstname;
    public $lastname;
    public $middlename;
    public $sex;
    public $birthday;
    public $city;
    public $address;
    public $email;
    public $phone;
    public $notification;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'site.user';
    }

    public function getNameForUser()
    {
        return $this->info['firstname'].' '.$this->info['lastname'];
    }
    public function getFirstEmail()
    {
        return $this->info['contacts']['emails'][0];
    }
    public function getFirstPhone()
    {
        return $this->info['contacts']['phones'][0];
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_INACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE, self::STATUS_DELETED]],
        ];
    }

    public function attributeLabels()
    {
        return [
            'lastname' => 'Фамилия',
            'firstname' => 'Имя',
            'middlename' => 'Отчество',
            'phone' => 'Номер телефона',
            'birthday' => 'Дата рождения',
            'sex' => 'Пол',
            'city' => 'Город',
            'address' => 'Адрес',
            'email' => 'E-Mail',
            'new_password' => 'Новый пароль',
            'old_passport' => 'Старый пароль',
            'repeat_password' => 'Подтвеждение пароля',
            'notifications' => 'Уведомления',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }
    public static function findByToken($token)
    {
        return static::findOne(['auth_key' => $token, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds user by verification email token
     *
     * @param string $token verify email token
     * @return static|null
     */
    public static function findByVerificationToken($token) {
        return static::findOne([
            'verification_token' => $token,
            'status' => self::STATUS_INACTIVE
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public function getInfo()
    {
        return $this->info;
    }

    /**
     * Получить полное имя пользователя
     * 
     * @return string
     */
    public function getFullName()
    {
        return (isset($this->info['firstname']) && isset($this->info['lastname'])) 
            ? $this->info['firstname'] . ' ' . $this->info['lastname']
            : $this->username;
    }

    /**
     * Проверить, нужно ли отправлять уведомление о событии
     * 
     * @param string $name of event
     * @return boolean
     */
    public function needNotify($name)
    {
        return isset($this->info[$name]) && $this->info[$name];
    }

    /**
     * inheritdoc
     */
    public function afterFind()
    {
        parent::afterFind();
        
        // установить внутренний переменные по json массиву
        $this->firstname = $this->info['firstname']; 
        $this->lastname = $this->info['lastname']; 
        $this->middlename = $this->info['middlename']; 
        $this->sex = $this->info['sex']; 
        $this->birthday = $this->info['birthday']; 
        $this->phone = $this->info['contacts']['phone']; 
        $this->email = $this->info['contacts']['email']; 
        $this->address = $this->info['contacts']['address']; 
        $this->city = $this->info['contacts']['city']; 
        $this->notifications = $this->info['notifications']; 
    }
    
    /**
     * inheritdoc
     */
    public function beforeSave($insert)
    {
        // сохранить внутренние переменные в json массиве 
        $this->info['firstname'] = $this->firstname; 
        $this->info['lastname'] = $this->lastname; 
        $this->info['middlename'] = $this->middlename; 
        $this->info['sex'] = $this->sex; 
        $this->info['birthday'] = $this->birthday; 
        $this->info['contacts']['phone'] = $this->phone; 
        $this->info['contacts']['email'] = $this->email; 
        $this->info['contacts']['address'] = $this->address; 
        $this->info['contacts']['city'] = $this->city; 
        $this->info['notifications'] = $this->notifications;
        
        return parent::beforeSave($insert); 
    }

    public function getOwnerId()
    {
        return $this->ownerId;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    public function generateEmailVerificationToken()
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
}
