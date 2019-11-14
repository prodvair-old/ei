<?php
namespace frontend\models;

use Yii;
use \yii\base\Module;

class getUserId extends Module
{
    public function getId()
    {
        return Yii::$app->user->id;
    }
}