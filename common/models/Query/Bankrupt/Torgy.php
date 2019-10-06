<?php
namespace common\models\Query\Bankrupt;

use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use app\models\Func;
use Imagine\Image\Box;
use yii\imagine\Image;
use common\models\Query\Bankrupt\Cases;
use common\models\Query\Bankrupt\Images;
use common\models\Query\Bankrupt\Links;
use common\models\Query\Bankrupt\Value;
use common\models\Query\Bankrupt\Bankrupts;
use common\models\Query\Bankrupt\Company;
use common\models\Query\Bankrupt\Persons;
use common\models\Query\Bankrupt\WishList;
use common\models\Query\Bankrupt\Lots;

// Таблица торгов
class Torgy extends ActiveRecord
{
    public static function tableName()
    {
        return 'uds.{{%auctions}}';
    }
    public static function getDb()
    {
        return Yii::$app->get('db');
    }
    public function getTorgyMsgId() {
        return $this->msgid;
    }
    public function getTorgyBnkrName() {
        if ($this->case->bnkr->bankrupttype == 'Person') {
            return $this->case->bnkr->person->lname.' '.$this->case->bnkr->person->fname.' '.$this->case->bnkr->person->mname;
        } else {
            return $this->case->bnkr->company->fullname;
        }
    }
    public function getTorgyArbtrName() {
        return $this->case->arbitr->person->lname.' '.$this->case->arbitr->person->fname.' '.$this->case->arbitr->person->mname;
    }
    public function getTorgyPrice() {
        $priceSumm = 0;
        foreach ($this->lots as $value) {
            $priceSumm = $priceSumm + $value->startprice;
        }
        return Func::fixSumm($priceSumm, 2);
    }
    public function getTorgySroTitle() {
        return $this->case->arbitr->sro->title;
    }
    public function getTorgyPeriod() {
        return Func::biutyDate($this->timebegin, true, false, true).' - '.Func::biutyDate($this->timeend, true, false, true);
    }
    public function getTorgyEtp() {
        return $this->tradesite;
    }
    // Атрибуты для поиска
    public function attributeLabels() {
        return [
            'torgyMsgId'     => '№ сообщения',
            'torgyPeriod'    => 'Период',
            'torgySroTitle'  => 'СРО',
            'torgyArbtrName' => 'Кем опубликован',
            'torgyBnkrName'  => 'Должник',
            'torgyEtp'       => 'ЭТП',
            'torgyPrice'     => 'Общая стоимость',
        ];
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
        return $this->hasOne(Cases::className(), ['id' => 'objid'])->via('caseLink')->alias('cases');
    }
    public function getEtp()
    {
        return $this->hasOne(Etp::className(), ['idtradeplace' => 'idtradeplace'])->alias('etplink');
    }
    public function getLots()
    {
        return $this->hasMany(Lots::className(), ['auctionid' => 'id'])->alias('lot');
    }
    public function getOffer()
    {
        return $this->hasMany(Offerreductions::className(), ['ofrRdnNumberInEFRSB' => 'msgid']);
    }
}