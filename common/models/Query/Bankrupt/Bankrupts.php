<?php
namespace common\models\Query\Bankrupt;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use common\models\Query\Bankrupt\Persons;
use common\models\Query\Bankrupt\Company;
use common\models\Query\Bankrupt\Links;

use common\models\Query\Lot\Parser;

// Арбитражный управляющий
class Bankrupts extends ActiveRecord
{
    public static function tableName()
    {
        return 'uds.{{%bankrupts}}';
    }
    public static function getDb()
    {
        return Yii::$app->get('obj');
    }
    public function fields()
    {
        return [
            'bankrupt_id'       => 'id',
            'bankrupt_type'     => 'bankrupttype',
            'bankrupt_category' => 'bankruptcategory',
        ];
    }
    public function getAddress()
    {
        if ($this->bankrupttype == 'Organization') {
            return $this->company->postaddress;
        } else {
            return $this->person->address;
        }
    }
    public function getPerson()
    {
        return $this->hasOne(Persons::className(), ['id' => 'lnkobjid'])->alias('bnkr_prsn')
            ->viaTable(Links::tableName(), ['objid' => 'id'], function ($query) {
                $query->alias('bnkr_prsn_lnk')->onCondition(['bnkr_prsn_lnk.objtype' => 1049, 'bnkr_prsn_lnk.lnkobjtype' => 1042]);
        });
    }
    public function getCompany()
    {
        return $this->hasOne(Company::className(), ['id' => 'lnkobjid'])->alias('bnkr_cmpn')
            ->viaTable(Links::tableName(), ['objid' => 'id'], function ($query) {
                $query->alias('bnkr_cmpn_lnk')->onCondition(['bnkr_cmpn_lnk.objtype' => 1049, 'bnkr_cmpn_lnk.lnkobjtype' => 1043]);
        });
    }

    // Связь с таблицей парсинга
    public function getParser()
    {
        return $this->hasMany(Parser::className(), ['tableIdFrom' => 'id'])->alias('parser')->onCondition(['parser.tableNameFrom'=>'uds.obj$bankrupts']);
    }
}