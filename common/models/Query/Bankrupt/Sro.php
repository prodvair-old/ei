<?php
namespace common\models\Query\Bankrupt;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;

class Sro extends ActiveRecord
{
    public static function tableName()
    {
        return 'uds.{{%sro}}';
    }
    public static function getDb()
    {
        return Yii::$app->get('db');
    }
    public function fields()
    {
        return [
            'sro_id'        => 'id',
            'sro_sroid'     => 'sroid',
            'sro_title'     => 'title',
            'sro_regnum'    => 'regnum',
            'sro_ogrn'      => 'ogrn',
            'sro_inn'       => 'inn',
            'sro_address'   => 'address',
        ];
    }
}