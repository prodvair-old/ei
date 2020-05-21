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
    public static function getDb()
    {
        return Yii::$app->get('obj');
    }

    // Связи с таблицами
    public function getSubCategorys()
    {
        return $this->hasMany(LotsSubCategory::className(), ['categoryId' => 'id'])->alias('subCategorys'); // Подкатегории
    }
}