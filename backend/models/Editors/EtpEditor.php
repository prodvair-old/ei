<?php

namespace backend\models\Editors;

use Yii;

/**
 * This is the model class for table "eiLot.etp".
 *
 * @property int $id ID торговой площадки
 * @property string|null $createdAt Дата и время добавления записи 
 * @property string|null $updatedAt Дата и время последнего изменения записи 
 * @property string $title Название торговой площадки 
 * @property string $url URL на сайт торговой площадки 
 * @property string|null $description  Описание торговой площадки 
 * @property string|null $email E-Mail адрес торговой площадки 
 * @property string|null $phone Номер телефона торговой площадки 
 * @property string|null $address Полный адрес торговой площадки
 * @property string $inn ИНН торговой площадки
 * @property string $info Дополнительная информация о топрговой площадки
 * @property int|null $regionId Регион торговой площадки 
 * @property string|null $city
 * @property string|null $district
 * @property int|null $number
 * @property int|null $oldId
 */
class EtpEditor extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'eiLot.etp';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['createdAt', 'updatedAt', 'info'], 'safe'],
            [['title', 'url', 'inn', 'info'], 'required'],
            [['title', 'url', 'description ', 'email', 'phone', 'address', 'inn', 'city', 'district'], 'string'],
            [['regionId', 'number', 'oldId'], 'default', 'value' => null],
            [['regionId', 'number', 'oldId'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID торговой площадки',
            'createdAt' => 'Дата и время добавления записи ',
            'updatedAt' => 'Дата и время последнего изменения записи ',
            'title' => 'Название торговой площадки ',
            'url' => 'URL на сайт торговой площадки ',
            'description ' => 'Описание торговой площадки ',
            'email' => 'E-Mail адрес торговой площадки ',
            'phone' => 'Номер телефона торговой площадки ',
            'address' => 'Полный адрес торговой площадки',
            'inn' => 'ИНН торговой площадки',
            'info' => 'Дополнительная информация о топрговой площадки',
            'regionId' => 'Регион торговой площадки ',
            'city' => 'City',
            'district' => 'District',
            'number' => 'Number',
            'oldId' => 'Old ID',
        ];
    }
}
