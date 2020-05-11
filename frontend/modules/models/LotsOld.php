<?php

namespace frontend\modules\models;

use common\models\Query\LotsCategory;
use Yii;

/**
 * This is the model class for table "eiLot.lots".
 *
 * @property int $id ID лота
 * @property int $torgId ID торга по лоту
 * @property string $msgId Номер или id сообщения лота
 * @property int|null $lotNumber Номер лота в торге
 * @property string|null $createdAt Дата и время добавления записи
 * @property string|null $updatedAt Дата и время последнего изменения записи
 * @property string $title Заголовок лота
 * @property string $description Описание лота
 * @property float $startPrice Начальная цена лота
 * @property float|null $step Шаг аукциона
 * @property string|null $stepType Тип шага торга. Может принимать "процент" или "сумма". Зависит от stepTypeId
 * @property int|null $stepTypeId ID типа шага торга. Может принимать "1" или "2". Зависит от stepType
 * @property float|null $deposit Задаток для участия в торге.
 * @property string|null $depositType Тип цены задатка для участия в торге. Может принимать "процент" или "сумма". Зависит от depositTypeId
 * @property int|null $depositTypeId ID типа цены задатка для участия в торге. Может принимать "1" или "2". Зависит от depositType
 * @property string $status Стату лота на торгах.
 * @property string|null $info Дополнительная информация о лота в виде json объектов
 * @property string|null $images Картинки лота в виде массива объектов
 * @property bool $published Лот опубликован на сайте или нет
 * @property int|null $regionId ID Региона где находится этот лот
 * @property string|null $city
 * @property string|null $district
 * @property int|null $oldId
 * @property int|null $bankId
 * @property string|null $address
 * @property int $archive
 */
class LotsOld extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'eiLot.lots';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['torgId', 'msgId', 'title', 'description', 'startPrice', 'status'], 'required'],
            [['torgId', 'lotNumber', 'stepTypeId', 'depositTypeId', 'regionId', 'oldId', 'bankId', 'archive'], 'default', 'value' => null],
            [['torgId', 'lotNumber', 'stepTypeId', 'depositTypeId', 'regionId', 'oldId', 'bankId', 'archive'], 'integer'],
            [['msgId', 'title', 'description', 'city', 'district', 'address'], 'string'],
            [['createdAt', 'updatedAt', 'info', 'images'], 'safe'],
            [['startPrice', 'step', 'deposit'], 'number'],
            [['published'], 'boolean'],
            [['stepType', 'depositType'], 'string', 'max' => 50],
            [['status'], 'string', 'max' => 70],
//            [['bankId'], 'exist', 'skipOnError' => true, 'targetClass' => EiLotBank::className(), 'targetAttribute' => ['bankId' => 'id']],
            [['torgId'], 'exist', 'skipOnError' => true, 'targetClass' => TorgsOld::className(), 'targetAttribute' => ['torgId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'            => 'ID лота',
            'torgId'        => 'ID торга по лоту',
            'msgId'         => 'Номер или id сообщения лота',
            'lotNumber'     => 'Номер лота в торге',
            'createdAt'     => 'Дата и время добавления записи',
            'updatedAt'     => 'Дата и время последнего изменения записи',
            'title'         => 'Заголовок лота',
            'description'   => 'Описание лота',
            'startPrice'    => 'Начальная цена лота',
            'step'          => 'Шаг аукциона',
            'stepType'      => 'Тип шага торга. Может принимать \"процент\" или \"сумма\". Зависит от stepTypeId',
            'stepTypeId'    => 'ID типа шага торга. Может принимать \"1\" или \"2\". Зависит от stepType',
            'deposit'       => 'Задаток для участия в торге.',
            'depositType'   => 'Тип цены задатка для участия в торге. Может принимать \"процент\" или \"сумма\". Зависит от depositTypeId',
            'depositTypeId' => 'ID типа цены задатка для участия в торге. Может принимать \"1\" или \"2\". Зависит от depositType',
            'status'        => 'Стату лота на торгах.',
            'info'          => 'Дополнительная информация о лота в виде json объектов',
            'images'        => 'Картинки лота в виде массива объектов',
            'published'     => 'Лот опубликован на сайте или нет',
            'regionId'      => 'ID Региона где находится этот лот',
            'city'          => 'City',
            'district'      => 'District',
            'oldId'         => 'Old ID',
            'bankId'        => 'Bank ID',
            'address'       => 'Address',
            'archive'       => 'Archive',
        ];
    }

    public function getTorg()
    {
        return $this->hasOne(TorgsOld::className(), ['id' => 'torgId']);
    }

    public function getCategory() {
        return $this->hasMany(LotCategories::className(), ['lotId' => 'id']);
    }

    public function getPriceHistory() {
        return $this->hasOne(LotPriceHistory::className(), ['lotId' => 'id']);
    }

//    public function getCategory() {
//        return $this->hasMany(LotsCategory::className(), ['lotId' => 'id']);
//    }
}
