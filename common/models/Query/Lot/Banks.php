<?php
namespace common\models\Query\Lot;

use yii\db\ActiveRecord;

use Yii;
use common\models\Query\Lot\Lots;

class Banks extends ActiveRecord
{
    public static function tableName()
    {
        return '"eiLot".{{banks}}';
    }
    public static function getDb()
    {
        return Yii::$app->get('db');
    }

    // Связи с таблицами
    public function getLots()
    {
        return $this->hasMany(Lots::className(), ['bankId' => 'id'])->alias('lots'); // Лоты
    }
}