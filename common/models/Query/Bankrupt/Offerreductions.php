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

class Offerreductions extends ActiveRecord 
{
    public static function tableName()
    {
        return 'bailiff.{{offerreductions}}';
    }
    public static function getDb()
    {
        return Yii::$app->get('db');
    }
    public static function find()
    {
        return parent::find()->orderBy('ofrRdnDateTimeBeginInterval ASC');
    }
}