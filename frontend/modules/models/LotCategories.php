<?php

namespace frontend\modules\models;

use frontend\modules\LotOld;
use Yii;

/**
 * This is the model class for table "eiLot.lotCategorys".
 *
 * @property int $id ID категория лота
 * @property int $lotId ID лота
 * @property int $categoryId ID категория
 * @property string $name Названия категория
 * @property string $nameTranslit Названия категория в транслите
 * @property string|null $createdAt Дата и время добавления категория
 */
class LotCategories extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'eiLot.lotCategorys';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['lotId', 'categoryId', 'name', 'nameTranslit'], 'required'],
            [['lotId', 'categoryId'], 'default', 'value' => null],
            [['lotId', 'categoryId'], 'integer'],
            [['name', 'nameTranslit'], 'string'],
            [['createdAt'], 'safe'],
            [['lotId'], 'exist', 'skipOnError' => true, 'targetClass' => LotOld::className(), 'targetAttribute' => ['lotId' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID категория лота',
            'lotId' => 'ID лота',
            'categoryId' => 'ID категория',
            'name' => 'Названия категория',
            'nameTranslit' => 'Названия категория в транслите',
            'createdAt' => 'Дата и время добавления категория',
        ];
    }
}
