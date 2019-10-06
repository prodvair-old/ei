<?php
namespace common\models\Query;

use Yii;
use yii\db\ActiveRecord;
use common\models\Query\Bankrupt\Lots;
use common\models\Query\Arrest\Lot;

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
    public function getLotsBaunkrupt()
    {
        return $this->hasOne(Lots::className(), ['id' => 'lotId'])->alias('lots_bankrupt')->onCondition([
            'type' => 'bankrupt'
        ]);
    }
    public function getLotsArrest()
    {
        return $this->hasOne(Lot::className(), ['lotId' => 'lotId'])->alias('lots_arrest')->onCondition([
            'type' => 'arrest'
        ]);
    }
}