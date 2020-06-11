<?php

namespace frontend\modules\models;

use creocoder\nestedsets\NestedSetsBehavior;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "eidb.category".
 *
 * @property int $id
 * @property int $lft Left
 * @property int $rgt Right
 * @property int $depth depth or level of a tree
 * @property string $name Node name
 * @property string $slug Slug
 * @property int $created_at
 * @property int $updated_at
 */
class Category extends \yii\db\ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'eidb.category';
    }

    public function behaviors()
    {
        return [
            'tree' => [
                'class' => NestedSetsBehavior::className(),
//                 'treeAttribute' => 'tree',
//                 'leftAttribute' => 'lft',
//                 'rightAttribute' => 'rgt',
//                 'depthAttribute' => 'depth',
            ],
        ];
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public static function find()
    {
        return new CategoryQuery(get_called_class());
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['lft', 'rgt', 'depth', 'name', 'slug', 'created_at', 'updated_at'], 'required'],
            [['lft', 'rgt', 'depth', 'created_at', 'updated_at'], 'default', 'value' => null],
            [['lft', 'rgt', 'depth', 'created_at', 'updated_at'], 'integer'],
            [['name', 'slug'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'         => 'ID',
            'lft'        => 'Left',
            'rgt'        => 'Right',
            'depth'      => 'depth or level of a tree',
            'name'       => 'Node name',
            'slug'       => 'Slug',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public static function getMainCategoriesList()
    {
        return $categories = ArrayHelper::map(
            self::find()->andFilterWhere(['in', 'depth', [0, 1]])->all(),
            'id', 'name'
        );
    }
}
