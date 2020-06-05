<?php
namespace frontend\models;

use Yii;
//use yii\base\Model;
use common\models\Api;
use yii\db\Query;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;
use common\models\User;
use yii\imagine\Image;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;

class UserEditPhone extends User
{ 
    public $phone;
    public $phone_hide;
    public $code;

    public function rules()
    {
        return [
            [
                ['phone', 'code'], 'string'
            ],
        ];
    }

    public function editPhone($code)
    {

        $user = User::findOne($user_id);

    }

}
