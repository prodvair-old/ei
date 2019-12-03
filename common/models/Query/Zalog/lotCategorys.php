<?php
namespace common\models\Query\Zalog;

use Yii;
use yii\db\ActiveRecord;

class lotCategorys extends ActiveRecord
{
    public static function tableName()
    {
        return 'zlg.{{lotCategorys}}';
    }
}