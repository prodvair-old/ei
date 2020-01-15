<?php
namespace common\models\Query\Lot;

use yii\db\ActiveRecord;

use Yii;
use common\models\Query\Lot\LotCategorys;
use common\models\Query\Lot\LotFiles;
use common\models\Query\Lot\LotPriceHistorys;
use common\models\Query\Lot\Participants;
use common\models\Query\Lot\Torgs;

class Lots extends ActiveRecord
{
    public static function tableName()
    {
        return '"eiLot".{{lots}}';
    }
    public static function getDb()
    {
        return Yii::$app->get('db');
    }

    // Связи с таблицами
    public function getCategorys()
    {
        return $this->hasMany(LotCategorys::className(), ['lotId' => 'id'])->alias('categorys'); // Категории лота
    }
    public function getFiles()
    {
        return $this->hasMany(LotFiles::className(), ['lotId' => 'id'])->alias('files'); // Файлы лота
    }
    public function getPriceHistorys()
    {
        return $this->hasMany(LotPriceHistorys::className(), ['lotId' => 'id'])->alias('priceHistorys'); // История снижения цен лота
    }
    public function getParticipants()
    {
        return $this->hasMany(Participants::className(), ['lotId' => 'id'])->alias('participants'); // Участники лота
    }
    public function getTorg()
    {
        return $this->hasOne(Torgs::className(), ['id' => 'torgId'])->alias('torg'); // Торги лота
    }
}