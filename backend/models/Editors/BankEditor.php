<?php

namespace backend\models\Editors;

use Yii;

/**
 * This is the model class for table "eiLot.banks".
 *
 * @property int $id
 * @property string|null $createdAt
 * @property string|null $updatedAt
 * @property int $bik
 * @property string $name
 * @property string $payment
 * @property string $personal
 * @property string|null $info
 */
class BankEditor extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'eiLot.banks';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['createdAt', 'updatedAt', 'info'], 'safe'],
            [['bik', 'name', 'payment', 'personal'], 'required'],
            [['bik'], 'default', 'value' => null],
            [['bik'], 'integer'],
            [['name', 'payment', 'personal'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
            'bik' => 'Bik',
            'name' => 'Name',
            'payment' => 'Payment',
            'personal' => 'Personal',
            'info' => 'Info',
        ];
    }
}
