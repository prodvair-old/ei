<?php

namespace backend\models\Editors;

use Yii;

/**
 * This is the model class for table "eiLot.managers".
 *
 * @property int $id ID Менеджера
 * @property int|null $sroId ID СРО для связи с таблицей СРО
 * @property string|null $createdAt Дата и время добавления записи 
 * @property string|null $updatedAt Дата и время последнего изменения записи 
 * @property string $type Тип менеджера. Арбитражный управляющий  или тот кто опубликовал лот
 * @property int $typeId ID типа менеджера
 * @property int|null $arbId ID арбитражного управляющего из сообщения
 * @property string|null $lastName Фамилия менеджера
 * @property string|null $firstName Имя менеджера 
 * @property string|null $middleName Отчество менеджера 
 * @property string|null $inn ИНН менеджера 
 * @property string|null $regnum Регистрационный номер менеджера
 * @property string $address Адрес менеджера 
 * @property string $info Дополнительная информация о менеджере
 * @property int $checked Проверен менеджерп или нет
 * @property int $regionId ID региона менеджера 
 * @property string|null $city
 * @property string|null $district
 * @property int|null $oldId
 * @property string $fullName Полное ФИО менеджера
 */
class ManagerEditor extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'eiLot.managers';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sroId', 'typeId', 'arbId', 'checked', 'regionId', 'oldId'], 'default', 'value' => null],
            [['sroId', 'typeId', 'arbId', 'checked', 'regionId', 'oldId'], 'integer'],
            [['createdAt', 'updatedAt', 'info'], 'safe'],
            [['type', 'typeId', 'address', 'info', 'regionId', 'fullName'], 'required'],
            [['lastName', 'firstName', 'middleName', 'inn', 'regnum', 'address', 'city', 'district', 'fullName'], 'string'],
            [['type'], 'string', 'max' => 50],
            [['sroId'], 'exist', 'skipOnError' => true, 'targetClass' => EiLotSro::className(), 'targetAttribute' => ['sroId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID Менеджера',
            'sroId' => 'ID СРО для связи с таблицей СРО',
            'createdAt' => 'Дата и время добавления записи ',
            'updatedAt' => 'Дата и время последнего изменения записи ',
            'type' => 'Тип менеджера. Арбитражный управляющий  или тот кто опубликовал лот',
            'typeId' => 'ID типа менеджера',
            'arbId' => 'ID арбитражного управляющего из сообщения',
            'lastName' => 'Фамилия менеджера',
            'firstName' => 'Имя менеджера ',
            'middleName' => 'Отчество менеджера ',
            'inn' => 'ИНН менеджера ',
            'regnum' => 'Регистрационный номер менеджера',
            'address' => 'Адрес менеджера ',
            'info' => 'Дополнительная информация о менеджере',
            'checked' => 'Проверен менеджерп или нет',
            'regionId' => 'ID региона менеджера ',
            'city' => 'City',
            'district' => 'District',
            'oldId' => 'Old ID',
            'fullName' => 'Полное ФИО менеджера',
        ];
    }
}
