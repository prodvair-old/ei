<?php
namespace common\models\Query;

use Yii;
use yii\db\ActiveRecord;
use common\models\Query\Bankrupt\LotsBankrupt;
use common\models\Query\Arrest\LotsArrest;

class WishList extends ActiveRecord
{
    public static function tableName()
    {
        return 'site.{{wishList}}';
    }
    public static function getDb()
    {
        return Yii::$app->get('db');
    }
    public function getLotsBankrupt()
    {
        return $this->hasOne(LotsBankrupt::className(), ['lot_id' => 'lotId'])->alias('lots_bankrupt')->onCondition([
            'type' => 'bankrupt'
        ]);
    }
    public function getLotsArrest()
    {
        return $this->hasOne(LotsArrest::className(), ['lotId' => 'lotId'])->alias('lots_arrest')->onCondition([
            'type' => 'arrest'
        ]);
    }
}