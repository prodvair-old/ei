<?php

namespace frontend\modules\models;

use Yii;

/**
 * This is the model class for table "eidb.lot_category".
 *
 * @property int $lot_id Лот
 * @property int $category_id Категория
 * @property int $created_at
 */
class LotCategory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'eidb.lot_category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['lot_id', 'category_id', 'created_at'], 'required'],
            [['lot_id', 'category_id', 'created_at'], 'default', 'value' => null],
            [['lot_id', 'category_id', 'created_at'], 'integer'],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['lot_id'], 'exist', 'skipOnError' => true, 'targetClass' => Lot::className(), 'targetAttribute' => ['lot_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'lot_id' => 'Лот',
            'category_id' => 'Категория',
            'created_at' => 'Created At',
        ];
    }

    public function getCategory() {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }
}
