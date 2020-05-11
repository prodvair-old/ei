<?php

namespace frontend\modules\models;

use Yii;

/**
 * This is the model class for table "eiLot.lotPriceHistorys".
 *
 * @property int $id ID история изменения цены
 * @property int|null $lotId ID лота
 * @property string $msgId Номер сообщения
 * @property int $lotNumber Номер лота
 * @property string|null $createdAt Дата и время добавления записи 
 * @property string|null $updatedAt Дата и время последнего изменения записи 
 * @property string $intervalBegin Дата и время начала действия текущей цены
 * @property string $intervalEnd Дата и время окончания действия текущей цены
 * @property float $price Текущая цена 
 * @property int|null $oldId
 */
class LotPriceHistory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'eiLot.lotPriceHistorys';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['lotId', 'lotNumber', 'oldId'], 'default', 'value' => null],
            [['lotId', 'lotNumber', 'oldId'], 'integer'],
            [['msgId', 'lotNumber', 'intervalBegin', 'intervalEnd', 'price'], 'required'],
            [['msgId'], 'string'],
            [['createdAt', 'updatedAt', 'intervalBegin', 'intervalEnd'], 'safe'],
            [['price'], 'number'],
            [['lotId'], 'exist', 'skipOnError' => true, 'targetClass' => Lot::className(), 'targetAttribute' => ['lotId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID история изменения цены',
            'lotId' => 'ID лота',
            'msgId' => 'Номер сообщения',
            'lotNumber' => 'Номер лота',
            'createdAt' => 'Дата и время добавления записи ',
            'updatedAt' => 'Дата и время последнего изменения записи ',
            'intervalBegin' => 'Дата и время начала действия текущей цены',
            'intervalEnd' => 'Дата и время окончания действия текущей цены',
            'price' => 'Текущая цена ',
            'oldId' => 'Old ID',
        ];
    }
}
