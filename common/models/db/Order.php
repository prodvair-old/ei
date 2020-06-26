<?php
namespace common\models\db;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * Order model
 * Lot orders with a bid price.
 *
 * @var integer $id
 * @var integer $lot_id
 * @var integer $user_id
 * @var float   $bid_price
 * @var integer $created_at
 * 
 * @property User $user
 * @property Lot  $lot
 */
class Order extends ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%order}}';
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
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'lot_id'    => Yii::t('app', 'Id'),
            'user_id'   => Yii::t('app', 'User'),
            'bid_price' => Yii::t('app', 'Bid price'),
        ]);
    }

    /**
     * Get lot
     * @return yii\db\ActiveQuery
     */
    public function getLot() {
        return $this->hasOne(Lot::className(), ['id' => 'lot_id']);
    }

    /**
     * Get user
     * @return yii\db\ActiveQuery
     */
    public function getUser() {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
