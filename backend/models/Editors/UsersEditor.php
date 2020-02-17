<?php

namespace backend\models\Editors;

use Yii;

/**
 * This is the model class for table "site.user".
 *
 * @property int $id
 * @property string $username
 * @property string $password
 * @property string $role
 * @property string $info
 * @property string $created_at
 * @property string $updated_at
 * @property bool $status
 * @property string|null $auth_key
 * @property string|null $password_reset_token
 * @property string|null $email_hash
 * @property string|null $verification_token
 * @property string|null $avatar
 * @property int|null $ownerId
 * @property string|null $access
 */
class UsersEditor extends \yii\db\ActiveRecord
{
    public $lotAccess;
    public $etpAccess;
    public $sroAccess;
    public $torgAccess;
    public $usersAccess;
    public $arbitrAccess;
    public $bankruptAccess;
    public $organizationAccess;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'site.user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'password', 'info'], 'required'],
            [['username', 'password', 'role', 'auth_key', 'password_reset_token', 'email_hash', 'verification_token', 'avatar'], 'string'],
            [['info', 'created_at', 'updated_at', 'access'], 'safe'],
            [[
                'lotAccess', 'etpAccess', 'sroAccess', 'torgAccess', 'usersAccess', 'arbitrAccess', 'bankruptAccess', 'organizationAccess'
            ], 'safe'],
            [['status'], 'boolean'],
            [['ownerId'], 'default', 'value' => null],
            [['ownerId'], 'integer'],
            [['username'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Имя пользователя',
            'password' => 'Пароль',
            'role' => 'Роль',
            'info' => 'Инвормация пользователя',
            'created_at' => 'Дата регистрации',
            'updated_at' => 'Дата обновления',
            'status' => 'Статус',
            'avatar' => 'Аватарка',
            'ownerId' => 'Организация',
            'lotAccess'         => 'Доступ к Лотам',
            'etpAccess'         => 'Доступ к Торговым площадкам',
            'sroAccess'         => 'Доступ к СРО',
            'torgAccess'        => 'Доступ к Торгам',
            'usersAccess'       => 'Доступ к Пользователям',
            'arbitrAccess'      => 'Доступ к Арбитражным управляющим',
            'bankruptAccess'    => 'Доступ к Должникам',
            'organizationAccess'=> 'Доступ к Организациям',
        ];
    }
}
