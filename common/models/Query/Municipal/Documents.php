<?php
namespace common\models\Query\Municipal;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;

use common\models\Query\Municipal\Torgs;

use common\models\Query\Lot\Parser;

// Таблица лотов арестовки
class Documents extends ActiveRecord
{
    public static function tableName()
    {
        return 'bailiff.{{documents}}';
    }
    public static function getDb()
    {
        return Yii::$app->get('db');
    }

    public function getTorg()
    {
        return $this->hasOne(Torgs::className(), ['trgBidNumber' => 'tdocBidNumber'])->alias('torg');
    }

    // Связь с таблицей парсинга
    public function getParser()
    {
        return $this->hasMany(Parser::className(), ['tableIdFrom' => 'tdocId'])->alias('parser')->onCondition(['parser.tableNameFrom'=>'bailiff.documents']);
    }
}