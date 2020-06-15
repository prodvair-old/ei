<?php

namespace common\models\db;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use sergmoro1\uploader\behaviors\HaveFileBehavior;

/**
 * User model
 * Учетные данные.
 *
 * @var integer $id
 * @var string  $username
 * @var string  $auth_key
 * @var string  $password_hash
 * @var string  $password_reset_token
 * @var string  $email
 * @var integer $status
 * @var integer $role
 * @var integer $created_at
 * @var integer $updated_at
 * 
 * @property Notification $notification
 * @property Profile $profile
 * @property Lot[] $lots
 * @property sergmoro1\uploader\models\OneFile[] $files
 */
class User extends ActiveRecord implements IdentityInterface
{
    // внутренний код модели используемый в составном ключе
    const INT_CODE        = 1;
    
    const STATUS_ACTIVE   = 1;
    const STATUS_ARCHIVED = 2;

    const ROLE_ADMIN      = 1;
    const ROLE_MANAGER    = 2;
    const ROLE_AGENT      = 3;
    const ROLE_ARBITRATOR = 4;
    const ROLE_USER       = 5;

    public static function getIntCode() { return self::INT_CODE; }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
            ],
			[
				'class' => HaveFileBehavior::className(),
				'file_path' => '/user/',
                'sizes' => [
                    'original'  => ['width' => 1600, 'height' => 900, 'catalog' => 'original'],
                    'main'      => ['width' => 400,  'height' => 400, 'catalog' => ''],
                    'thumb'     => ['width' => 90,   'height' => 90,  'catalog' => 'thumb'],
                ],
			],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'email'], 'required'],
            [['status', 'role'], 'integer'],
            ['status', 'default', 'value' => self::STATUS_ARCHIVED],
            ['status', 'in', 'range' => self::getStatuses()],
            ['role', 'default', 'value' => self::ROLE_USER],
            ['role', 'in', 'range' => self::getRoles()],
            [['username', 'email', 'password_hash', 'password_reset_token'], 'string', 'max' => 255],
            ['username', 'unique', 'targetClass' => '\common\models\db\User', 'message' => Yii::t('app', 'This username has already been taken.')],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => '\common\models\db\User', 'message' => Yii::t('app', 'This email address has already been taken.')],
            [['auth_key'], 'string', 'max' => 32],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'username'   => Yii::t('app', 'Username'),
            'email'      => Yii::t('app', 'Email'),
            'role'       => Yii::t('app', 'Role'),
            'status'     => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created'),
            'updated_at' => Yii::t('app', 'Modified'),
        ];
    }

    /**
     * Get statuses
     * @return array
     */
    public static function getStatuses() {
        return [
            self::STATUS_ACTIVE,
            self::STATUS_ARCHIVED, 
        ];
    }

    /**
     * Get groups
     * @return array
     */
    public static function getRoles() {
        return [
            self::ROLE_ADMIN, 
            self::ROLE_MANAGER, 
            self::ROLE_AGENT,
            self::ROLE_USER,
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

    /**
     * Проверить, нужно ли отправлять уведомление о событии
     * 
     * @param string $name of event
     * @return boolean
     */
    public function needNotify($name)
    {
        return $this->notification ? $this->notification->$name : false;
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

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * Get avatar image
     */
    public function getAvatarImage()
    {
        return $image = $this->getImage('thumb') ?: Yii::getAlias('@uploader/site/user.png');
    }

    /**
     * Get user avatar from thumb if there is a registered user or icon if no.
     * Icon may be defined in App params.
     * 
     * @param array $htmlOptions of image
     * @param string $icon tag
     * @return string avatar
     */
    public function getAvatar($htmlOptions = [], $icon = false)
    {
        if($image = $this->getAvatarImage()) {
            return Html::img($image, $htmlOptions);
        } else {
            return $icon ? (isset(Yii::$app->params['icons']['user']) ? Yii::$app->params['icons']['user'] : '') : '';
        }
    }

    /**
     * Получить профиль
     * 
     * @return yii\db\ActiveQuery
     */
    public function getProfile() {
        return $this->hasOne(Profile::className(), ['parent_id' => 'id'])->where(['model' => self::INT_CODE]);
    }

    /**
     * Получить полное имя или  username 
     * 
     * @return string
     */
    public function getFullName()
    {
        return $this->profile ? $this->profile->getFullName() : $this->username;
    }

    /**
     * Уведомления, заказанные пользователем
     * @return yii\db\ActiveQuery
     */
    public function getNotification()
    {
        return $this->hasOne(Notification::className(), ['user_id' => 'id']);
    }

    /**
     * Получить понравившиеся лоты
     * @return yii\db\ActiveQuery
     */
    public function getLots()
    {
        return $this->hasMany(Lot::className(), ['id' => 'user_id'])
            ->viaTable(WishList::tableName(), ['lot_id' => 'id']);
    }

    /**
     * Getting user items for dropdown list.
     * $search string a part of User name
     * @return array of [id: integer, text: string]
     */
	public static function getItems($search = '')
	{
        $users = self::find()
            ->select(['user.id', 'username', 'first_name', 'last_name'])
            ->leftJoin('{{%profile}}', ['profile.parent_id' => 'user.id', 'model' => self::INT_CODE])
            ->where(['status' => self::STATUS_ACTIVE])
            ->asArray()
            ->all();
        $a = [];
        foreach($users as $user)
            if (!$search || !(stripos($user['username'] . $user['first_name'] . $user['last_name'], $search) === false))
                $a[] = [
                    'id' => $user['id'], 
                    'text' => (($user['last_name'] || $user['first_name'])
                        ? $user['last_name'] . ' ' . $user['first_name'] 
                        : $user['username']
                    ),
                ];
        return $a;
	}
}
