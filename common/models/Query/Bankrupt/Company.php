<?php
namespace common\models\Query\Bankrupt;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;

// Таблица персон
class Company extends ActiveRecord
{
    public static function tableName()
    {
        return 'uds.{{%company}}';
    }
    public static function getDb()
    {
        return Yii::$app->get('db');
    }
    public function fields()
    {
        return [
            'company_id'            => 'id',
            'company_fullname'      => 'fullname',
            'company_shortname'     => 'shortname',
            'company_postaddress'   => 'postaddress',
            'company_legaladdress'  => 'legaladdress',
            'company_inn'           => 'inn',
            'company_okpo'          => 'okpo',
            'company_ogrn'          => 'ogrn',
        ];
    }
}