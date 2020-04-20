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
class UsersEditor extends \common\models\User
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            [['username', 'password', 'role', 'auth_key', 'password_reset_token', 'email_hash', 'verification_token', 'avatar'], 'string'],
            [['created_at', 'updated_at', 'access'], 'safe'],
            [['status'], 'boolean'],
            [['ownerId'], 'default', 'value' => null],
            [['access'], 'default', 'value' => [
                "lots" => [
                    "add" => false,
                    "edit" => false,
                    "delete" => false,
                    "import" => false,
                    "status" => false
                ],
                "find"  => [
                    "arrest" => false
                ],
                "torgs" => [
                    "add" => false,
                    "edit" => false,
                    "delete" => false,
                    "status" => false
                ],
                "users" => [
                    "add" => false,
                    "edit" => false,
                    "delete" => false,
                    "status" => false
                ],
                "owners" => [
                    "add" => false,
                    "edit" => false,
                    "delete" => false,
                    "status" => false
                ],
                "debug" => false
            ]],
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
            'access'         => '',
        ];
    }
}
