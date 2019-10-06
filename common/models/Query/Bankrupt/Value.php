<?php
namespace common\models\Query\Bankrupt;

use Yii;
use yii\base\Module;

// Таблица Категории лота
class Value extends Module
{
    public function param($id)
    {
        return Yii::$app->db->createCommand("select dctgetvalue as value from dctGetValue($id)")->queryOne();
    }
    public function all($id)
    {
        return Yii::$app->db->createCommand("select * from dctGet($id)")->queryAll();
    }
    public function region()
    {
        return Yii::$app->db->createCommand('select regionid, title from obj$cadastre where areaid is null')->queryAll();
    }
}