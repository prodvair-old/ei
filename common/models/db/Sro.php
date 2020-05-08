<?php

namespace common\models\db;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use common\traits\Company;

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
 * @property Place $place
 */
class Etp extends ActiveRecord
{
    use Company;

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
}
