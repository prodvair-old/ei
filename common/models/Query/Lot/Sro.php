<?php
namespace common\models\Query\Lot;

use Yii;
use yii\db\ActiveRecord;

use common\models\Query\Lot\Managers;

class Sro extends ActiveRecord
{
    public static function tableName()
    {
        return '"eiLot".{{sro}}';
    }
    public static function getDb()
    {
        return Yii::$app->get('db');
    }

    // Связи с таблицами
    public function getManagers()
    {
        return $this->hasMany(Managers::className(), ['sroId' => 'id'])->alias('manager'); // Арбитражник
    }
}