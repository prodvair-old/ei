<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\Api;
use yii\db\Query;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;
use common\models\User;
use yii\imagine\Image;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;

class UserSetting extends Model
{ 
    public $firstname;
    public $lastname;
    public $middlename;
    public $sex;
    public $birthday;
    public $city;
    public $address;
    public $avatar;
    public $passport_img;
    public $email;
    public $phone;
    public $photo;
    public $passport;
    public $new_password;
    public $repeat_password;
    public $old_password;
    public $old_photo;
    public $old_passport;

    public function rules()
    {
        return [
            [
                ['firstname', 'lastname', 'middlename', 'sex', 'birthday', 'city', 'address'], 'string'
            ],
            [['avatar', 'passport_img'], 'string'],
            
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            // ['email', 'unique', 'targetAttribute' => 'username', 'targetClass' => User::className(), 'message' => 'Такой пользователь уже зарегистрирован.'],

            ['phone', 'required'],
            ['phone', 'string'],

            [['old_photo', 'old_passport'], 'string'],

            [['photo', 'passport'], 'file', 'extensions' => 'png, jpg, svg', 'skipOnEmpty' => true],
            [['old_password', 'new_password', 'repeat_password'], 'string', 'min' => 6],
            ['repeat_password', 'compare', 'compareAttribute'=>'new_password', 'message'=>"Пароли не совподают!" ],
            // [['old_password', 'new_password', 'repeat_password'], 'min' => 6, 'string'],
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
        ];
    }

    public function setting($user_id)
    {

        $user = User::findOne($user_id);

        $userInfo = [
            'sex' => $this->sex,
            'birthday' => $this->birthday,
            'contacts' => [
                'phones' => [$this->phone],
                'emails' => [$this->email],
                'address' => $this->address,
                'city' => $this->city
            ]
        ];

        if ($this->lastname) {
            $userInfo['lastname'] = $this->lastname;
        }
        if ($this->firstname) {
            $userInfo['firstname'] = $this->firstname;
        }
        if ($this->middlename) {
            $userInfo['middlename'] = $this->middlename;
        }
        // if ($this->photo != Null) {
        //     $userInfo['avatar'] = "img/users/$user_id-avatar.".$this->photo->getExtension().'?'.$this->photo->name;
        // } else {
        //     $userInfo['avatar'] = $this->old_photo;
        // }

        // if ($this->passport != Null) {
        //     $userInfo['documents']['passport'] = "img/users/$user_id-passport_".$this->passport->name;
        // } else {
        //     $userInfo['documents']['passport'] = $this->old_passport;
        // }

        
        
        if ($this->new_password != '' && $this->old_password != '' && $this->repeat_password) {
            if ($user->validatePassword($this->old_password)) {

                if ($this->old_password != $this->new_password) {

                    if ($this->new_password == $this->repeat_password) {
                        $user->setPassword($this->new_password);
                        $this->old_password = $this->new_password = $this->repeat_password = null;
                    } else {
                        $this->addError('repeat_password', 'Пароли не совпадают');
                    }

                } else {
                    $this->addError('new_password', 'Новый пароль не должен совподать со старым');
                }
                
            } else {
                $this->addError('old_password', 'Не верный пароль');
            }
        }

        $user->info = $userInfo;

        $user->update();

    }
    public function upload($user_id)
    {
        // if ($this->validate()) {

            FileHelper::createDirectory(Yii::getAlias('@frontendWeb').'/img/users/');

            if ($this->photo != null) {
                $pathPhoto = Yii::getAlias('@frontendWeb').'/img/users/'.$user_id.'-avatar.'.$this->photo->getExtension();

                $this->photo->saveAs( $pathPhoto );

                Image::thumbnail($pathPhoto, 300, 300)->save(Yii::getAlias($pathPhoto), ['quality' => 80]);

                $user = User::findOne($user_id);
                $user->avatar = '/img/users/'.$user_id.'-avatar.'.$this->photo->getExtension();
                $user->update();
                return ['src' => '/img/users/'.$user_id.'-avatar.'.$this->photo->getExtension()];
            } else {
                return false;
            }
            
            // if ($this->passport != Null) {
            //     $pathPassport = Yii::getAlias('@frontendWeb').'/img/users/'.$user_id.'-passport_';
            //     $this->passport->saveAs( $pathPassport . $this->passport );
            // }

            
        // } else {
        //     return false;
        // }
    }
}