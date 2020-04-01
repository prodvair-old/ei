<?php
namespace common\models\Query;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;

// Таблица Всех gобщих под категории лота данных таблицы
class LotsSubCategory extends ActiveRecord
{
    public static function tableName()
    {
        return 'site.{{lotsSubCategory}}';
    }

    // Связи с таблицами
    public function getCategorys()
    {
        return $this->hasOne(LotsCategory::className(), ['id' => 'categoryId'])->alias('categorys'); // Категории
    }
}