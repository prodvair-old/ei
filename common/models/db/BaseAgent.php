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
 * @property integer $id
 * @property integer $who
 * @property integer $type
 * @property integer $created_at
 * @property integer $updated_at
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
            ['who', 'in', 'range' => self::getWhoAgent()],
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
     * Get gender variants
     * @return array
     */
    public static function getWhoAgent() {
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
        return Profile::findOne(['model' => self::INT_CODE, 'parent_id' => $this->id]);
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
        return Organization::findOne(['model' => self::INT_CODE, 'parent_id' => $this->id]);
    }

    /**
     * Get full name
     * @return string
     */
    public static function getFullName() {
        if ($this->who == self::PERSON)
            return $this->profile->fullName
        elseif ($this->who == self::ORGANIZATION)
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
