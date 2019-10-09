<?php
namespace common\models\Query;

use Yii;
use yii\db\ActiveRecord;

class PageViews extends ActiveRecord
{
    public static function tableName()
    {
        return 'site.{{pageViews}}';
    }
    public static function getDb()
    {
        return Yii::$app->get('db');
    }
}