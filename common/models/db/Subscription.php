<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "subscription".
 *
 * @property int $id
 * @property int $user_id Пользователь
 * @property int $tariff_id Тариф
 * @property int $invoice_id Счет, по которому произведена оплата
 * @property int $from_at Дата, с которой действует тариф
 * @property int $till_at Дата, по которую действует тариф
 * @property int $created_at
 *
 * @property Invoice $invoice
 * @property Tariff $tariff
 * @property User $user
 */
class Subscription extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%subscription}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'tariff_id', 'invoice_id', 'from_at', 'till_at', 'created_at'], 'required'],
            [['user_id', 'tariff_id', 'invoice_id', 'from_at', 'till_at'], 'default', 'value' => null],
            [['created_at'], 'default', 'value' => time()],
            [['user_id', 'tariff_id', 'invoice_id', 'from_at', 'till_at', 'created_at'], 'integer'],
            [['invoice_id'], 'exist', 'skipOnError' => true, 'targetClass' => Invoice::className(), 'targetAttribute' => ['invoice_id' => 'id']],
            [['tariff_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tariff::className(), 'targetAttribute' => ['tariff_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'         => Yii::t('app', 'ID'),
            'user_id'    => Yii::t('app', 'Пользователь'),
            'tariff_id'  => Yii::t('app', 'Тариф'),
            'invoice_id' => Yii::t('app', 'Счет, по которому произведена оплата'),
            'from_at'    => Yii::t('app', 'Дата, с которой действует тариф'),
            'till_at'    => Yii::t('app', 'Дата, по которую действует тариф'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }

    /**
     * Gets query for [[Invoice]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getInvoice()
    {
        return $this->hasOne(Invoice::className(), ['id' => 'invoice_id']);
    }

    /**
     * Gets query for [[Tariff]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTariff()
    {
        return $this->hasOne(Tariff::className(), ['id' => 'tariff_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
