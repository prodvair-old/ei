<?php
namespace common\models\Query\Lot;

use yii\db\ActiveRecord;

use Yii;

class Parser extends ActiveRecord
{
    public static function tableName()
    {
        return '"eiLot".{{parser}}';
    }
    public static function getDb()
    {
        return Yii::$app->get('db');
    }
}