<?php
namespace common\models\Query\Lot;

use yii\db\ActiveRecord;

use Yii;
use common\models\Query\Lot\Lots;

class LotFiles extends ActiveRecord
{
    public static function tableName()
    {
        return '"eiLot".{{lotFiles}}';
    }
    public static function getDb()
    {
        return Yii::$app->get('db');
    }

    // Связь с таблицами
    public function getLot()
    {
        return $this->hasOne(Lots::className(), ['id' => 'lotId'])->alias('lot'); // Лот
    }
}