<?php
namespace common\models\db;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * LotCategory model
 * Категории, к которым принадлежит Лот.
 *
 * @var integer $lot_id
 * @var integer $category_id
 * @var integer $created_at
 * 
 * @property Lot $lot которому принадлежит данная цена
 */
class LotCategory extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%lot_category}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                TimestampBehavior::className(),
                'updatedAtAttribute' => false,
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['lot_id', 'category_id'], 'required'],
            ['created_at', 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'lot_id'      => Yii::t('app', 'Lot'),
            'category_id' => Yii::t('app', 'Category'),
            'created_at'  => Yii::t('app', 'Created'),
        ];
    }
}
