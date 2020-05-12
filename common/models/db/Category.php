<?php

namespace common\models\db;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\SluggableBehavior;

use creocoder\nestedsets\NestedSetsBehavior;

/**
 * Caterory model class (Nested Set)
 * Категории Лотов.
 *
 * @var integer $id
 * @var integer $lft
 * @var integer $rgt
 * @var integer $depth
 * @var string  $name
 * @var string  $slug
 * @var integer $created_at
 * @var integer $updated_at
 */
class Category extends ActiveRecord
{
    /* count of models in the category */
    public $model_count;        
    
    const ROOT = 0;
    
    /**
     * @return string the associated database table name
     */
    public static function tableName()
    {
        return '{{%category}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            ['class' => TimestampBehavior::className()],
            [
                'class' => SluggableBehavior::className(),
                'attribute' => 'name',
                'ensureUnique' => true,
                'immutable' => true,
            ],
            'tree' => [
                'class' => NestedSetsBehavior::className(),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    /**
     * @inheritdoc
     */
    public static function find()
    {
        return new Category(get_called_class());
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return [
            [['name', 'slug'], 'required'],
            [['lft', 'rgt', 'depth'], 'integer', 'max' => 255],
            [['name', 'slug'], 'string', 'max' => 255],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'name'        => Module::t('core', 'Name'),
            'slug'        => Module::t('core', 'Slug'),
            'model_count' => Module::t('core', 'Count'),
        );
    }

    /**
     * Array of category names with ID as index.
     * 
     * @return array
     */
    public static function items()
    {
        $a = [];
        foreach(self::find()->orderBy('lft ASC')->all() as $node)
            $a[$node->id] = $node->getPrettyName(true);
        return $a;
    }
    
    /**
     * Get Category by slug.
     * @param string $slug
     * @return Category | false
     */
    public static function item($slug)
    {
        return $slug ? self::findOne(['slug' => $slug]) : false;
    }

    /**
     * Retrieves the list of Categorys based on the current search/filter conditions.
     * @return ActiveDataProvider the data provider
     */
    public function search()
    {
        $query = Category::find()->orderBy('lft ASC, position DESC');

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => Yii::$app->params['itemsPerPage'],
            ],
        ]);
    }
    
    /**
     * Get Category name with indentation.
     * @param boolean view of indentation - plain or wrapped. 
     * @return ActiveDataProvider the data provider.
     */
    public function getPrettyName($plain = false)
    {
        $indentation = str_repeat('-', ($this->level - 2) * 3);
        return $plain
            ? $indentation . ' ' . $this->name
            : '<span class="branch">' . $indentation . '</span>' . ' ' . $this->name;
    }

    /**
     * @inheritdoc
     */
    public function afterFind()
    {
        parent::afterFind();
        $query = LotCategory::find()->where(['category_id' => $this->id]);
        // set Lot count in Category
        $this->model_count = $query->count();
    }

    /**
     * @inheritdoc
     */
    public function beforeDelete()
    {
        if (!parent::beforeDelete()) {
            return false;
        }
        $this->merge();
        return true;
    }
    
    /**
     * Current Category will be deleted. 
     * Before that all models for the Category to be deleted are merged with models from the parent Category.
     * @return boolean
     */
    public function merge()
    {
        // ID of Category that will be deleted
        $deleted_id = $this->id;
        // ID of a recipient or parent Category
        $recipient_id = $this->node_id ? $this->node_id : $this->parents(1)->one()->id;
        // find all childrens of current Category
        $childrens = $this->children()->all();
        // update field Category_id to $recipient_id in all posts with Category $deleted_id or it's childrens id
        $ids = $deleted_id;
        foreach($childrens as $node)
            $ids .= ',' . $node->id;
        // replace links in a connected model
        return LotCategory::updateAll(['category_id' => $recipient_id], 'category_id IN (' . $ids . ')');
    }
}
