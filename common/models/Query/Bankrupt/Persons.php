<?php
namespace common\models\Query\Bankrupt;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;

// Таблица персон
class Persons extends ActiveRecord
{
    public static function tableName()
    {
        return 'uds.{{%persons}}';
    }
    public static function getDb()
    {
        return Yii::$app->get('db');
    }
    public function getFullName() {
        return $this->lname.' '.$this->fname.' '.$this->mname;
    }
    
    public function attributeLabels() {
        return [
            'fullName' => 'full_name'
        ];
    }
    public function fields()
    {
        return [
            'person_id'     => 'id',
            'last_name'     => 'lname',
            'first_name'    => 'fname',
            'middle_name'   => 'mname',
            'full_name'     => 'fullName',
            'person_pol'    => 'sexid',
            'person_inn'    => 'inn',
            'person_snils'  => 'snils',
            'person_birthday' => function () {
                return Yii::$app->formatter->asDatetime($this->birthday, "php:d.m.Y");
            },
            'person_birthplace' => 'birthplace',
            'person_address'    => 'address',
        ];
    }
}