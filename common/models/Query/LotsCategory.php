<?php
namespace common\models\Query;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;
// Таблица Всех категории лота данных таблицы
class LotsCategory extends ActiveRecord
{
    public static function tableName()
    {
        return 'site.{{lotsCategory}}';
    }
}