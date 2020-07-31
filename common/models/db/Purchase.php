<?php

namespace common\models\db;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "eidb.purchase".
 *
 * @property int $id
 * @property int $user_id Пользователь
 * @property int $report_id Отчет
 * @property int $invoice_id Счет, по которому произведена оплата
 * @property int $created_at
 */
class Purchase extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'eidb.purchase';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                'class'              => TimestampBehavior::className(),
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
            [['user_id', 'report_id', 'invoice_id'], 'required'],
            [['user_id', 'report_id', 'invoice_id'], 'default', 'value' => null],
            [['user_id', 'report_id', 'invoice_id', 'created_at'], 'integer'],
            [['invoice_id'], 'exist', 'skipOnError' => true, 'targetClass' => Invoice::className(), 'targetAttribute' => ['invoice_id' => 'id']],
            [['report_id'], 'exist', 'skipOnError' => true, 'targetClass' => Report::className(), 'targetAttribute' => ['report_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['created_at'], 'safe'],
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
            'report_id'  => Yii::t('app', 'Отчет'),
            'invoice_id' => Yii::t('app', 'Счет, по которому произведена оплата'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }

    /**
     * @param $userId
     * @return Purchase[]
     */
    public static function getPurchaseByUser($userId)
    {
        return self::findAll(['user_id' => $userId]);
    }

    public static function getPurchasedReportsIdByUser($userId)
    {
        return self::find()->select('report_id')->where(['user_id' => $userId])->asArray()->column();
    }
}
