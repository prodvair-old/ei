<?php
namespace common\models\db;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * WishList model
 * Избранные лоты.
 *
 * @property integer $id
 * @property integer $lot_id
 * @property integer $user_id
 * @property integer $created_at
 */
class WishList extends BaseAgent
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
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'lot_id'        => Yii::t('app', 'Лот'),
            'user_id'        => Yii::t('app', 'Пользователь'),
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
