<?php
namespace common\models\db;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * LotPrice model
 * Цены по Лоту.
 *
 * @var integer $id
 * @var integer $lot_id
 * @var integer $started_at
 * @var integer $end_at
 * @var integer $created_at
 * @var integer $updated_at
 * 
 * @property Lot $lot которому принадлежит данная цена
 */
class LotPrice extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%lot_price}}';
    }

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
            [['lot_id', 'started_at', 'end_at'], 'required'],
            [['started_at', 'end_at'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'started_at' => Yii::t('app', 'Started at'),
            'end_at'     => Yii::t('app', 'End at'),
            'created_at' => Yii::t('app', 'Created'),
            'updated_at' => Yii::t('app', 'Modified'),
        ];
    }
}
