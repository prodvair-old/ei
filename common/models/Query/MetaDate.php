<?php
namespace common\models\Query;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;
// Таблица мета данных таблицы
class MetaDate extends ActiveRecord
{
    public static function tableName()
    {
        return 'site.{{metaDate}}';
    }
    public static function getDb()
    {
        return Yii::$app->get('db');
    }
}