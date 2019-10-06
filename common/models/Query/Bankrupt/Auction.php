<?php
namespace common\models\Query\Bankrupt;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use app\models\Func;
use common\models\Query\Bankrupt\Cases;
use common\models\Query\Bankrupt\Images;
use common\models\Query\Bankrupt\Links;
use common\models\Query\Bankrupt\Value;

class Auction extends ActiveRecord
{
    public static function tableName()
    {
        return 'uds.{{%auctions}}';
    }
    public static function getDb()
    {
        return Yii::$app->get('db');
    }
    public function fields()
    {
        return [
            'auction_id'            => 'id',
            'auction_state'         => 'state',
            'auction_timepublication' => function () {
                return Yii::$app->formatter->asDatetime($this->timepublication, "php:Y-d-m H:i:s");
            },
            'auction_description'   => 'description',
            'auction_tradetype'     => 'tradetype',
            'auction_pricetype'     => 'pricetype',
            'auction_timebegin'     => function () {
                return Yii::$app->formatter->asDatetime($this->timebegin, "php:Y-d-m H:i:s");

            },
            'auction_timeend'       => function () {
                return Yii::$app->formatter->asDatetime($this->timeend, "php:Y-d-m H:i:s");
            },
            'auction_rules'         => 'rules',
            'auction_tradesite'     => 'tradesite',
            'auction_idtradeplace'  => 'idtradeplace',
        ];
    }
    public function getCaseLink()
    {
        return $this->hasOne(Links::className(), ['lnkobjid' => 'id'])->alias('clink')->onCondition(['clink.objtype'=>1044, 'clink.lnkobjtype'=>1048]);
    }
    public function getCase()
    {
        return $this->hasOne(Cases::className(), ['id' => 'objid'])->via('caseLink');
    }
    public function getEtp()
    {
        return $this->hasOne(Etp::className(), ['idtradeplace' => 'idtradeplace'])->alias('etplink');
    }
}