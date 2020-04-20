<?php
namespace common\models\Query\Bankrupt;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use common\models\Query\Lot\Lots;

use common\models\Query\Lot\Parser;

class Purchaselots extends ActiveRecord 
{
    public static function tableName()
    {
        return 'bailiff.{{purchaselots}}';
    }
    public static function getDb()
    {
        return Yii::$app->get('db');
    }


    public function getLot()
    {
        return $this->hasOne(Lots::className(), ['lotNumber'=>'pheLotNumber', 'msgId' => 'pheLotNumberInEFRSB'])->alias('lot');
    }

    // Связь с таблицей парсинга
    public function getParser()
    {
        return $this->hasOne(Parser::className(), ['tableIdFrom' => 'pheLotId'])->alias('parser')->onCondition(['parser.tableNameFrom'=>'bailiff.purchaselots']);
    }
}