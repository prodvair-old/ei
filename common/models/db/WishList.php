<?php
namespace common\models\db;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * WishList model
 * Избранные лоты.
 *
 * @var integer $id
 * @var integer $lot_id
 * @var integer $user_id
 * @var integer $created_at
 * 
 * @property User $user
 * @property Lot  $lot
 */
class WishList extends ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%wish_list}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                TimestampBehavior::className(),
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
            'lot_id'  => Yii::t('app', 'Лот'),
            'user_id' => Yii::t('app', 'Пользователь'),
        ]);
    }

    /**
     * Получить информацию о лоте
     * @return yii\db\ActiveQuery
     */
    public function getLot() {
        return $this->hasOne(Lot::className(), ['id' => 'lot_id']);
    }

    /**
     * Получить информацию о пользователе
     * @return yii\db\ActiveQuery
     */
    public function getUser() {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
