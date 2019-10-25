<?php
namespace common\models\Query;

use Yii;
use yii\db\ActiveRecord;

// Таблица Всех регионов
class Regions extends ActiveRecord
{
    public static function tableName()
    {
        return 'site.{{regions}}';
    }
    public static function primaryKey()
    {
        return ['id'];
    }
}