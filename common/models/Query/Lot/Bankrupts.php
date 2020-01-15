<?php
namespace common\models\Query\Lot;

use yii\db\ActiveRecord;

use Yii;
use common\models\Query\Lot\Trogs;

class Bankrupts extends ActiveRecord
{
    public static function tableName()
    {
        return '"eiLot".{{bankrupts}}';
    }
    public static function getDb()
    {
        return Yii::$app->get('db');
    }
    
    // Связи с таблицами
    public function getTrogs()
    {
        return $this->hasMany(Trogs::className(), ['bankruptId' => 'id'])->alias('trogs'); // Торги
    }
}