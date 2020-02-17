<?php

namespace backend\models\Editors;

use Yii;

/**
 * This is the model class for table "eiLot.bankrupts".
 *
 * @property int $id ID должника
 * @property string|null $createdAt Дата и время создания записи
 * @property string|null $updatedAt Дата и время последнего изменения записи 
 * @property string $type тип должника компания или физ. лицо
 * @property int $typeId ID типа должника
 * @property string $category Категория должника
 * @property int $categoryId ID категории должника
 * @property string $name Название или ФИО должника
 * @property string|null $inn Инн должника
 * @property int|null $regionId ID региона должника
 * @property string $info Дополнительная информация по должнику
 * @property string|null $city
 * @property string|null $district
 * @property int|null $oldId
 * @property string|null $address
 */
class BankruptEditor extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'eiLot.bankrupts';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['createdAt', 'updatedAt', 'info'], 'safe'],
            [['type', 'typeId', 'category', 'categoryId', 'name', 'info'], 'required'],
            [['typeId', 'categoryId', 'regionId', 'oldId'], 'default', 'value' => null],
            [['typeId', 'categoryId', 'regionId', 'oldId'], 'integer'],
            [['name', 'inn', 'city', 'district', 'address'], 'string'],
            [['type'], 'string', 'max' => 50],
            [['category'], 'string', 'max' => 70],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID должника',
            'createdAt' => 'Дата и время создания записи',
            'updatedAt' => 'Дата и время последнего изменения записи ',
            'type' => 'тип должника компания или физ. лицо',
            'typeId' => 'ID типа должника',
            'category' => 'Категория должника',
            'categoryId' => 'ID категории должника',
            'name' => 'Название или ФИО должника',
            'inn' => 'Инн должника',
            'regionId' => 'ID региона должника',
            'info' => 'Дополнительная информация по должнику',
            'city' => 'City',
            'district' => 'District',
            'oldId' => 'Old ID',
            'address' => 'Address',
        ];
    }
}
