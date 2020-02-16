<?php
namespace common\models\Query;

use Yii;
use yii\db\ActiveRecord;

// Таблица истории по админке таблицы

class HistoryAdmin extends ActiveRecord
{
    public static function tableName()
    {
        return 'site.{{historyAdmin}}';
    }
    public static function getDb()
    {
        return Yii::$app->get('db');
    }
}