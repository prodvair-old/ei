<?php
namespace common\models\Query\Bankrupt;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use common\models\Query\Bankrupt\Persons;
use common\models\Query\Bankrupt\Sro;
use common\models\Query\Bankrupt\Links;
use common\models\Query\Bankrupt\Cases;

// Арбитражный управляющий
class Arbitrs extends ActiveRecord
{
    public static function tableName()
    {
        return 'uds.{{%arbitrs}}';
    }
    public static function getDb()
    {
        return Yii::$app->get('db');
    }
    public function fields()
    {
        return [
            'arb_id'        => 'id',
            'arb_arbid'     => 'arbid',
            'arb_personid'  => 'personid',
            'arb_regnum'    => 'regnum',
            'arb_ogrn'      => 'ogrn',
            'arb_address'   => 'postaddress',
            'arb_arbcheck'  => 'arbcheck',
        ];
    }
    public function getPerson()
    {
        return $this->hasOne(Persons::className(), ['id' => 'personid'])->alias('arb_prsn');
    }
    public function getSro()
    {
        return $this->hasOne(Sro::className(), ['id' => 'objid'])->alias('sro')
            ->viaTable(Links::tableName(), ['lnkobjid' => 'id'], function ($query) {
                $query->alias('arb_sro')->onCondition(['arb_sro.objtype' => 1046, 'arb_sro.lnkobjtype' => 1047]);
        });
    }
    public function getCases()
    {
        return $this->hasMany(Cases::className(), ['id' => 'objid'])->alias('cases')
            ->viaTable(Links::tableName(), ['lnkobjid' => 'id'], function ($query) {
                $query->alias('arb_case')->onCondition(['arb_case.objtype' => 1044, 'arb_case.lnkobjtype' => 1047]);
        });
    }
}