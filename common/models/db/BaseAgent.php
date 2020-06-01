<?php

namespace common\models\db;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use common\interfaces\ProfileInterface;
use common\interfaces\PlaceInterface;

/**
 * BaseAgent model
 *
 * @var integer $id
 * @var integer $agent
 * @var integer $created_at
 * @var integer $updated_at
 * 
 * @property Place        $place
 * @property Profile      $profile
 * @property Organization $organization
 */
class BaseAgent extends ActiveRecord implements ProfileInterface, PlaceInterface
{
    const AGENT_ORGANIZATION  = 1;
    const AGENT_PERSON        = 2;

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
            ['agent', 'required'],
            ['agent', 'in', 'range' => self::getAgents()],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'agent'        => Yii::t('app', 'Agent'),
            'created_at'   => Yii::t('app', 'Created'),
            'updated_at'   => Yii::t('app', 'Modified'),
        ];
    }

    /**
     * Get agent variants
     * @return array
     */
    public static function getAgents() {
        return [
            self::AGENT_PERSON,
            self::AGENT_ORGANIZATION, 
        ];
    }

    /**
     * Get profile
     * @return ActiveRecord || null
     */
    public function getProfile() {
        return $this->agent == self::AGENT_PERSON 
            ? Profile::findOne(['model' => $this->int_code, 'parent_id' => $this->id]) 
            : null;
    }

    /**
     * Get organization
     * @return ActiveRecord || null
     */
    public function getOrganization() {
        return $this->agent == self::AGENT_ORGANIZATION 
            ? Organization::findOne(['model' => $this->int_code, 'parent_id' => $this->id])
            : null;
    }

    /**
     * Get place that model connected with
     * @return ActiveRecord
     */
    public function getPlace()
    {
        return Place::findOne(['model' => $this->int_code, 'parent_id' => $this->id]);
    }

    /**
     * Get full name
     * @return string
     */
    public function getFullName() {
        if ($this->agent == self::AGENT_PERSON)
            return isset($this->profile) ? $this->profile->fullName : '';
        else
            return isset($this->organization) ? $this->organization->title : '';
    }

    /**
     * Get address
     * @return string
     */
    public function getAddress() {
        return $this->place->address;
    }
}
