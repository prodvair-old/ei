<?php
namespace common\models\Query\Bankrupt;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;

// Таблица персон
class Links extends ActiveRecord
{
    public static function tableName()
    {
        return 'uds.{{%links}}';
    }
    public static function getDb()
    {
        return Yii::$app->get('db');
    }
}