<?php
namespace common\models\Query\Bankrupt;

use Yii;
use yii\db\ActiveRecord;

use common\models\Query\Lot\Parser;

// Таблица ЭТП
class Etp extends ActiveRecord
{
    public static function tableName()
    {
        return 'uds.{{tradeplace}}';
    }
    public static function getDb()
    {
        return Yii::$app->get('db');
    }
    public function fields()
    {
        return [
            'etp_id'        => 'idtradeplace',
            'etp_inn'       => 'inn',
            'etp_url'       => 'tradesite',
            'etp_name'      => 'tradename',
            'etp_fullname'  => 'ownername',
        ];
    }

    // Связь с таблицей парсинга
    public function getParser()
    {
        return $this->hasMany(Parser::className(), ['tableIdFrom' => 'id'])->alias('parser')->onCondition(['parser.tableNameFrom'=>'uds.tradeplace']);
    }
}