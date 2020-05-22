<?php
namespace common\models\Query\Arrest;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;

use common\models\Query\Lot\Parser;

use common\models\Query\Arrest\Lots;

// Таблица лотов арестовки
class LotDocuments extends ActiveRecord
{
    public static function tableName()
    {
        return 'bailiff.{{lotdocuments}}';
    }
    public static function getDb()
    {
        return Yii::$app->get('db');
    }

    // Связь с таблицей парсинга
    public function getParser()
    {
        return $this->hasMany(Parser::className(), ['tableIdFrom' => 'ldocId'])->alias('parser')->onCondition(['parser.tableNameFrom'=>'bailiff.lotdocuments']);
    }

    public function getLot()
    {
        return $this->hasOne(Lots::className(), ['lotBidNumber' => 'ldocBidNumber'])->alias('lot')->onCondition(['lot.lotNum'=>'ldocLotNum']);
    }
}