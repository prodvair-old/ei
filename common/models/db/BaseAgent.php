<?php
namespace common\models\db;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use common\interfaces\ProfileInterface;
use common\interfaces\PlaceInterface;

/**
 * BaseAgent model
 * Агент это либо Персона либо Организация.
 *
 * @var integer $id
 * @var integer $who
 * @var integer $created_at
 * @var integer $updated_at
 * 
 * @property Place        $place
 * @property Profile      $profile
 * @property Organization $organization
 */
class BaseAgent extends ActiveRecord implements ProfileInterface, PlaceInterface
{
    const WHO_PERSON       = 1;
    const WHO_ORGANIZATION = 2;

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                TimestampBehavior::className(),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['who', 'required'],
            ['who', 'in', 'range' => self::getWhoVariants()],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'who'         => Yii::t('app', 'Who'),
            'created_at'  => Yii::t('app', 'Created'),
            'updated_at'  => Yii::t('app', 'Modified'),
        ];
    }

    /**
     * Get manager variants
     * @return array
     */
    public static function getWhoVariants() {
        return [
            self::WHO_PERSON,
            self::WHO_ORGANIZATION, 
        ];
    }

    /**
     * Get profile
     * @return string
     */
    public function getProfile() {
        return $this->who == WHO_PERSON ? Profile::findOne(['model' => self::INT_CODE, 'parent_id' => $this->id]) : null;
    }

    /**
     * Get place that model connected with
     * @return yii\db\ActiveRecord
     */
    public function getPlace()
    {
        return Place::findOne(['model' => self::INT_CODE, 'parent_id' => $this->id]);
    }

    /**
     * Get organization
     * @return string
     */
    public function getOrganization() {
        return $this->who == WHO_ORGANIZATION ? Organization::findOne(['model' => self::INT_CODE, 'parent_id' => $this->id]) : null;
    }

    /**
     * Get full name
     * @return string
     */
    public static function getFullName() {
        if ($this->who == self::WHO_PERSON)
            return $this->profile->fullName
        elseif ($this->who == self::WHO_ORGANIZATION)
            return $this->organization->title;
        else
            return '';
    }

    /**
     * Get address
     * @return string
     */
    public static function getAdderss() {
        return $this->place->address;
    }
}
