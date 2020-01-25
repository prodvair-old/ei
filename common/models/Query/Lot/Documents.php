<?php
namespace common\models\Query\Lot;

use yii\db\ActiveRecord;

use Yii;
use common\models\Query\Lot\Lots;
use common\models\Query\Lot\Cases;
use common\models\Query\Lot\Torgs;

class Documents extends ActiveRecord
{
    public static function tableName()
    {
        return '"eiLot".{{documents}}';
    }
    public static function getDb()
    {
        return Yii::$app->get('db');
    }

    // Связь с таблицами
    public function getLot()
    {
        return $this->hasOne(Lots::className(), ['id' => 'tableId'])->alias('lot')->onCondition(['tableTypeId'=>1]); // Лот
    }
    public function getTorg()
    {
        return $this->hasOne(Torgs::className(), ['id' => 'tableId'])->alias('torg')->onCondition(['tableTypeId'=>2]); // Торг
    }
    public function getCase()
    {
        return $this->hasOne(Cases::className(), ['id' => 'tableId'])->alias('case')->onCondition(['tableTypeId'=>3]); // Дела по должнику
    }
}