<?php
namespace common\models\Query\Arrest;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;

// Таблица лотов арестовки
class Documents extends ActiveRecord
{
    public static function tableName()
    {
        return 'bailiff.{{documents}}';
    }
    public static function getDb()
    {
        return Yii::$app->get('db');
    }
}