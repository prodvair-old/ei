<?php

namespace backend\models\Editors;

use Yii;

/**
 * This is the model class for table "eiLot.cases".
 *
 * @property int $id ID дела по торгам
 * @property string|null $createdAt Дата и время добавления записи
 * @property string|null $updatedAt Дата и время последнего изменения записи
 * @property string $number Номер дела
 * @property string $regnum Регистрационный номер суда
 * @property string|null $judge Судья
 * @property string $info Дополнительная информация по делу в виде json объектов
 * @property int|null $oldId
 */
class CaseEditor extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'eiLot.cases';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['createdAt', 'updatedAt', 'info'], 'safe'],
            [['number', 'regnum', 'info'], 'required'],
            [['number', 'regnum', 'judge'], 'string'],
            [['oldId'], 'default', 'value' => null],
            [['oldId'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID дела по торгам',
            'createdAt' => 'Дата и время добавления записи',
            'updatedAt' => 'Дата и время последнего изменения записи',
            'number' => 'Номер дела',
            'regnum' => 'Регистрационный номер суда',
            'judge' => 'Судья',
            'info' => 'Дополнительная информация по делу в виде json объектов',
            'oldId' => 'Old ID',
        ];
    }
}
