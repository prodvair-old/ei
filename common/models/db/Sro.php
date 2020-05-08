<?php

namespace common\models\db;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * Sro model
 * Само-регулируемая организация.
 *
 * @var integer $id
 * @var integer $efrsb_id
 * @var integer $created_at
 * @var integer $updated_at
 * 
 * @property Organization $organization
 */
class Etp extends ActiveRecord
{
    // внутренний код модели используемый в составном ключе
    const INT_CODE = 11;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%sro}}';
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
            [['efrsb_id'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'efrsb_id'     => Yii::t('app', 'External ID'),
            'created_at'   => Yii::t('app', 'Created'),
            'updated_at'   => Yii::t('app', 'Modified'),
        ];
    }

    /**
     * Get organization
     * @return ActiveRecord | null
     */
    public function getOrganization() {
        return Organization::findOne([
            'model'     => self::INT_CODE, 
            'parent_id' => $this->id,
        ]);
    }
}
