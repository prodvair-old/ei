<?php
namespace common\models\Query\Bankrupt;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;
// Таблица Категории лота
class Categorys extends ActiveRecord
{
    public static function tableName()
    {
        return 'uds.{{%lotclassifier}}';
    }
    public static function getDb()
    {
        return Yii::$app->get('db');
    }
}