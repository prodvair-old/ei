<?php
namespace common\models\Query\Bankrupt;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use common\models\Query\Bankrupt\Cases;

use common\models\Query\Lot\Parser;

// Файлы лота
class Files extends ActiveRecord 
{
    public function fields()
    {
        return [
            'file_id'       => 'id',
            'file_caseid'   => 'caseid',
            'file_name'     => 'filename',
            'file_url'      => 'fileurl',
        ];
    }
    public static function tableName()
    {
        return 'uds.{{%casefiles}}';
    }
    public static function getDb()
    {
        return Yii::$app->get('db');
    }
    public function getCase()
    {
        return $this->hasOne(Cases::className(), ['id' => 'caseid']);
    }

    // Связь с таблицей парсинга
    public function getParser()
    {
        return $this->hasMany(Parser::className(), ['tableIdFrom' => 'id'])->alias('parser')->onCondition(['parser.tableNameFrom'=>'uds.obj$casefiles']);
    }
}