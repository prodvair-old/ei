<?php
namespace common\models\Query;

use Yii;
use yii\db\ActiveRecord;
use common\models\Query\Lot\Lots;

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

    public function getLots()
    {
        return $this->hasOne(Lots::className(), ['id' => 'lotId'])->alias('lots');
    }
}