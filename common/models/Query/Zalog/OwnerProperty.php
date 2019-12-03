<?php
namespace common\models\Query\Zalog;

use Yii;
use yii\db\ActiveRecord;

class OwnerProperty extends ActiveRecord
{
    public static function tableName()
    {
        return 'zlg.{{ownerProperty}}';
    }
}