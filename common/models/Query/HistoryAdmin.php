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

    public static function findByUserId($id)
    {
        return static::find()->where(['userId' => $id])->orderBy('"createdAt" ASC');
    }

    // Связи с таблицами
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'userId'])->alias('user'); // Пользователь
    }
}