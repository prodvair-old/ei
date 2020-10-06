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
 * @property Lot $lot которому принадлежит данная категория
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
                'class' => TimestampBehavior::className(),
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

    public static function primaryKey()
    {
        return 'id';
    }

    /**
     * Update one-to-many links.
     * 
     * @param integer $host_id
     * @param array $old links ID
     * @param array $new links ID
     * @throw InvalidParamException
     */
    public static function updateOneToMany($host_id, $old, $new)
    {
        $host_name = 'lot_id';
        $link_name = 'category_id';

        if (!is_array($old)) $old = [];
        if (!is_array($new)) $new = [];
        // delete links if some of them have been deleted in a form
        foreach(array_diff($old, $new) as $i => $link_id) {
            if($link = self::find()->where([$host_name => $host_id, $link_name => $link_id])->one())
                $link->delete();
        }
        // add links if some of them have been added in a form
        foreach(array_diff($new, $old) as $i => $link_id) {
            $link = new self([$host_name => $host_id, $link_name => $link_id]);
            $link->save();
        }
    }
}
