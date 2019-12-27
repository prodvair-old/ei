<?php
namespace common\models\Query;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;

class User extends ActiveRecord
{
    public static function tableName()
    {
        return 'site.{{user}}';
    }
    public static function getDb()
    {
        return Yii::$app->get('db');
    }
}