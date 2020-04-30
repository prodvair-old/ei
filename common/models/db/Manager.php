<?php

namespace common\models\db;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use common\interfaces\ProfileInterface;
use common\interfaces\PlaceInterface;

/**
 * Manager model
 * Управляющий, ответственный за ведение дел по банкротному имуществу.
 *
 * @var integer $id
 * @var integer $type
 * @var integer $organizer_id
 * @var integer $created_at
 * @var integer $updated_at
 * 
 * @property Place        $place
 * @property Profile      $profile
 * @property Organization $organization
 */
class Manager extends ActiveRecord implements ProfileInterface, PlaceInterface
{
    // внутренний код модели используемый в составном ключе
    const INT_CODE = 3;

    const TYPE_ARBITR     = 1;
    const TYPE_ORGANIZER  = 3;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%manager}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'organizer_id'], 'required'],
            ['type', 'in', 'range' => self::getTypes()],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'type'         => Yii::t('app', 'Type'),
            'organizer_id' => Yii::t('app', 'Organizer'),
            'created_at'   => Yii::t('app', 'Created'),
            'updated_at'   => Yii::t('app', 'Modified'),
        ];
    }

    /**
     * Get manager variants
     * @return array
     */
    public static function getTypes() {
        return [
            self::TYPE_ARBITR,
            self::TYPE_ORGANIZER, 
        ];
    }

    /**
     * Get profile
     * @return yii\db\ActiveRecord
     */
    public function getProfile() {
        return $this->type == TYPE_ARBITR 
            ? Profile::findOne(['model' => self::INT_CODE, 'parent_id' => $this->id]) 
            : null;
    }

    /**
     * Get organization
     * @return ActiveRecord | null
     */
    public function getOrganizer() {
        return Organization::findOne([
            'model'     => ($this->type == TYPE_ARBITR ? Organization::TYPE_SRO : Organization::TYPE_MANAGER), 
            'parent_id' => $this->organizer_id,
        ]);
    }

    /**
     * Get place that model connected with
     * @return yii\db\ActiveRecord
     */
    public function getPlace()
    {
        return $this->type == TYPE_ARBITR 
            ? Place::findOne(['model' => self::INT_CODE, 'parent_id' => $this->id])
            : $this->organizer->place;
    }

    /**
     * Get full name
     * @return string
     */
    public function getFullName() {
        if ($this->type == self::TYPE_ARBITR)
            return $this->profile->fullName;
        else
            return $this->organizer->title;
    }

    /**
     * Get address
     * @return string
     */
    public function getAddress() {
        return $this->place->address;
    }
}
