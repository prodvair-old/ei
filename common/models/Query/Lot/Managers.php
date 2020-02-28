<?php
namespace common\models\Query\Lot;

use yii\db\ActiveRecord;

use Yii;
use common\models\Query\Lot\Sro;
use common\models\Query\Lot\Torgs;

class Managers extends ActiveRecord
{
    public static function tableName()
    {
        return '"eiLot".{{managers}}';
    }
    public static function getDb()
    {
        return Yii::$app->get('db');
    }

    // Связи с таблицами
    public function getSro()
    {
        return $this->hasOne(Sro::className(), ['id' => 'sroId'])->alias('sro'); // СРО
    }
    public function getTorgs()
    {
        return $this->hasMany(Torgs::className(), ['publisherId' => 'id'])->alias('torgs'); // Торги
    }
}