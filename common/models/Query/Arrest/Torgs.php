<?php
namespace common\models\Query\Arrest;

use Yii;
use yii\db\ActiveRecord;

use common\models\Query\Arrest\Documents;

use common\models\Query\Lot\Parser;

// Таблица лотов арестовки
class Torgs extends ActiveRecord
{
    public static function tableName()
    {
        return 'bailiff.{{torgs}}';
    }
    public static function getDb()
    {
        return Yii::$app->get('db');
    }

    public function getDocuments()
    {
        return $this->hasMany(Documents::className(), ['tdocBidNumber' => 'trgBidNumber'])->alias('documents');
    }

    // Связь с таблицей парсинга
    public function getParser()
    {
        return $this->hasMany(Parser::className(), ['tableIdFrom' => 'trgId'])->alias('parser')->onCondition(['parser.tableNameFrom'=>'bailiff.torgs']);
    }
}