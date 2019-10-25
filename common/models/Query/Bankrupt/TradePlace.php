<?php
namespace common\models\Query\Bankrupt;

use Yii;
use yii\db\ActiveRecord;
// Таблица Всех торговых площадок лота
class TradePlace extends ActiveRecord
{
    public static function tableName()
    {
        return 'uds.{{tradeplace}}';
    }
}