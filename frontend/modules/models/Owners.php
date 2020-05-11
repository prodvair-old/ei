<?php

namespace frontend\modules\models;

use Yii;

/**
 * This is the model class for table "eiLot.owners".
 *
 * @property int $id ID владельца или организации 
 * @property string|null $createdAt Дата и время добавления записи 
 * @property string|null $updatedAt Дата и время последнего изменения записи 
 * @property string $type Тип владельца. Вид организации.
 * @property int $typeId ID типа владельца
 * @property string $title Название организации владельца
 * @property string $url URL на сайт владельца
 * @property string|null $logo Ссылка на логотип
 * @property string $description Описание владельца
 * @property string $email E-Mail адрес владельца
 * @property string $phone Номер телефона владельца
 * @property string $linkEi Ссылка транслит для сайта ei.ru
 * @property string $address Полный адрес владельца
 * @property string|null $inn ИНН владельца
 * @property string|null $info Дополнительная информация о владельце
 * @property int $checked Проверен ли владелец
 * @property int|null $regionId Регион владельца
 * @property string|null $template Параметры для персонализации страницы владельца
 * @property string|null $city
 * @property string|null $district
 */
class Owners extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'eiLot.owners';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['createdAt', 'updatedAt', 'info', 'template'], 'safe'],
            [['type', 'typeId', 'title', 'url', 'description', 'email', 'phone', 'linkEi', 'address', 'checked'], 'required'],
            [['typeId', 'checked', 'regionId'], 'default', 'value' => null],
            [['typeId', 'checked', 'regionId'], 'integer'],
            [['title', 'url', 'logo', 'description', 'email', 'phone', 'linkEi', 'address', 'inn', 'city', 'district'], 'string'],
            [['type'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID владельца или организации
',
            'createdAt' => 'Дата и время добавления записи ',
            'updatedAt' => 'Дата и время последнего изменения записи ',
            'type' => 'Тип владельца. Вид организации.',
            'typeId' => 'ID типа владельца',
            'title' => 'Название организации владельца',
            'url' => 'URL на сайт владельца',
            'logo' => 'Ссылка на логотип',
            'description' => 'Описание владельца',
            'email' => 'E-Mail адрес владельца',
            'phone' => 'Номер телефона владельца',
            'linkEi' => 'Ссылка транслит для сайта ei.ru',
            'address' => 'Полный адрес владельца',
            'inn' => 'ИНН владельца',
            'info' => 'Дополнительная информация о владельце',
            'checked' => 'Проверен ли владелец',
            'regionId' => 'Регион владельца',
            'template' => 'Параметры для персонализации страницы владельца',
            'city' => 'City',
            'district' => 'District',
        ];
    }
}
