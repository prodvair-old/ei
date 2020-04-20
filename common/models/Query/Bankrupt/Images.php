<?php
namespace common\models\Query\Bankrupt;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;

// Таблица персон
class Images extends ActiveRecord
{
    public static function tableName()
    {
        return 'uds.{{%images}}';
    }
    public static function getDb()
    {
        return Yii::$app->get('obj');
    }
    public function fields()
    {
        return [
            'images_id'     => 'id',
            'images_name'   => 'fileurl',
            'images_url'    => function() {
                return ((Yii::$app->request->hostInfo == 'http://localhost:80')? 'https://ei.ru' : Yii::$app->request->hostInfo).'/img/lot/'.$this->objid.'/'.$this->fileurl;
            }
        ];
    }
}