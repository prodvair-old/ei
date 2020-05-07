<?php

namespace common\models\db;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * Etp model
 * Торговая площадка для размещения лотов.
 *
 * @var integer $id
 * @var integer $number
 * @var integer $organizer_id
 * @var integer $created_at
 * @var integer $updated_at
 * 
 * @property Organization $organization
 */
class Etp extends ActiveRecord
{
    // внутренний код модели используемый в составном ключе
    const INT_CODE = 12;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%etp}}';
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
            [['number', 'organizer_id'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'number'       => Yii::t('app', 'Number'),
            'organizer_id' => Yii::t('app', 'Organizer'),
            'created_at'   => Yii::t('app', 'Created'),
            'updated_at'   => Yii::t('app', 'Modified'),
        ];
    }


    /**
     * Get organization
     * @return ActiveRecord | null
     */
    public function getOrganizer() {
        return Organization::findOne([
            'model'     => Organization::TYPE_ETP, 
            'parent_id' => $this->organizer_id,
        ]);
    }

    /**
     * Get place that model connected with
     * @return yii\db\ActiveRecord
     */
    public function getPlace()
    {
        return $this->organizer->place;
    }

    /**
     * Get full name
     * @return string
     */
    public function getTitle() {
        return $this->organizer->title;
    }

    /**
     * Get address
     * @return string
     */
    public function getAddress() {
        return $this->organizer->place->address;
    }
}
