<?php

namespace frontend\modules\profile\forms;

use Yii;
use yii\base\Model;
use yii\helpers\FileHelper;
use common\models\db\User;
use yii\imagine\Image;

class ProfileForm extends Model
{
    public $first_name;
    public $last_name;
    public $middle_name;
    public $phone;
    public $email;
    public $gender;
    public $birthday;
    public $city;
    public $address;

    public $new_password;
    public $repeat_password;
    public $old_password;

    public function rules()
    {
        return [
            [['first_name', 'last_name', 'middle_name', 'gender', 'birthday', 'city', 'address'], 'string'],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],

            ['phone', 'string'],

            [['old_password', 'new_password', 'repeat_password'], 'string', 'min' => 6],
            ['repeat_password', 'compare', 'compareAttribute' => 'new_password', 'message' => "Пароли не совпадают!"],
        ];
    }
}
