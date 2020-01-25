<?php
namespace common\models\Query\Bankrupt;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use common\models\Query\Bankrupt\Arbitrs;
use common\models\Query\Bankrupt\Bankrupts;
use common\models\Query\Bankrupt\Links;
use common\models\Query\Bankrupt\Lots;
use common\models\Query\Bankrupt\Auction;
use common\models\Query\Bankrupt\Files;

use common\models\Query\Lot\Parser;

class Cases extends ActiveRecord 
{
    public static function tableName()
    {
        return 'uds.{{%cases}}';
    }
    public static function getDb()
    {
        return Yii::$app->get('db');
    }
    public function fields()
    {
        return [
            'case_id'           => 'id',
            'case_caseid'       => 'caseid',
            'case_caseopen'     => 'caseopen',
            'case_caseclose'    => 'caseclose',
            'case_regnum'       => 'regnum',
            'case_regyear'      => 'regyear',
            'case_regpostfix'   => 'regpostfix',
        ];
    }
    public function getFiles()
    {
        return $this->hasMany(Files::className(), ['caseid' => 'id']);
    }
    public function getArbitrLink()
    {
        return $this->hasOne(Links::className(), ['objid' => 'id'])->alias('case_arbitr')->onCondition(['case_arbitr.objtype'=>1044, 'case_arbitr.lnkobjtype'=>1047]);
    }
    public function getArbitr()
    {
        return $this->hasOne(Arbitrs::className(), ['id' => 'lnkobjid'])->via('arbitrLink')->alias('arbitr');
    }
    public function getBnkrLink()
    {
        return $this->hasOne(Links::className(), ['objid' => 'id'])->alias('case_bnkr')->onCondition(['case_bnkr.objtype'=>1044, 'case_bnkr.lnkobjtype'=>1049]);
    }
    public function getBnkr()
    {
        return $this->hasOne(Bankrupts::className(), ['id' => 'lnkobjid'])->via('bnkrLink')->alias('bnkr');
    }
    public function getAuctions()
    {
        return $this->hasMany(Auction::className(), ['id' => 'lnkobjid'])->alias('torgy')
            ->viaTable(Links::tableName(), ['objid' => 'id'], function ($query) {
                $query->alias('case_lots')->onCondition(['case_lots.objtype' => 1044, 'case_lots.lnkobjtype' => 1047]);
        });
    }

    // Связь с таблицей парсинга
    public function getParser()
    {
        return $this->hasMany(Parser::className(), ['tableIdFrom' => 'id'])->alias('parser')->onCondition(['parser.tableNameFrom'=>'uds.obj$cases']);
    }
}