<?php
namespace common\models\db;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * LotTrace model
 * User trace with IP and date of the Lot viewed.
 *
 * @var integer $id
 * @var integer $lot_id
 * @var string  $ip
 * @var integer $created_at
 * 
 * @property Lot $lot, which has been viewed
 */
class LotTrace extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%lot_trace}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'updatedAtAttribute' => false,
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['lot_id', 'ip'], 'required'],
            ['ip', 'ip'],
            ['created_at', 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'lot_id'     => Yii::t('app', 'Lot'),
            'ip'         => Yii::t('app', 'IP'),
            'created_at' => Yii::t('app', 'Created'),
        ];
    }

    /**
     * Get the Lot.
     */
    public function getLot()
    {
        return $this->hasOne(Lot::className(), ['id' => 'lot_id']);
    }
}
