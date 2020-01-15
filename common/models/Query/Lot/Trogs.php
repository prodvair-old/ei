<?php
namespace common\models\Query\Lot;

use yii\db\ActiveRecord;

use Yii;
use common\models\Query\Lot\Managers;
use common\models\Query\Lot\Owners;
use common\models\Query\Lot\Etp;
use common\models\Query\Lot\Bankrupts;
use common\models\Query\Lot\Cases;
use common\models\Query\Lot\Lots;

use common\models\Query\User;

class Torgs extends ActiveRecord
{
    public static function tableName()
    {
        return '"eiLot".{{torgs}}';
    }
    public static function getDb()
    {
        return Yii::$app->get('db');
    }

    // Связи с таблицами
    public function getPublisher()
    {
        return $this->hasOne(Managers::className(), ['id' => 'publisherId'])->alias('publisher'); // Кем опубликован
    }
    public function getOwner()
    {
        return $this->hasOne(Owners::className(), ['id' => 'ownerId'])->alias('owner'); // Владелец торга
    }
    public function getEtp()
    {
        return $this->hasOne(Etp::className(), ['id' => 'etpId'])->alias('etp'); // Торговая площадка
    }
    public function getBankrupt()
    {
        return $this->hasOne(Bankrupts::className(), ['id' => 'bankruptId'])->alias('bankrupt'); // Должник
    }
    public function getCase()
    {
        return $this->hasOne(Cases::className(), ['id' => 'caseId'])->alias('case'); // Дела по торгу
    }
    public function getLots()
    {
        return $this->hasMany(Lots::className(), ['torgId' => 'id'])->alias('lots'); // Лоты торга
    }
}