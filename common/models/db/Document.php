<?php
namespace common\models\db;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * Document model
 * Документы.
 *
 * @var integer $id
 * @var integer $model
 * @var integer $parent_id
 * @var string  $name
 * @var string  $ext
 * @var string  $url
 * @var string  $hash
 * @var integer $created_at
 * @var integer $updated_at
 */
class Document extends ActiveRecord
{
    private static $model_convertor = [1 => 6, 2 => 7, 3 => 4];
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{eiLot.documens}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['model', 'parent_id', 'url'], 'required'],
            [['name', 'ext', 'url', 'hash'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name'        => Yii::t('app', 'Name'),
            'ext'         => Yii::t('app', 'Extension'),
            'url'         => Yii::t('app', 'Url'),
            'hash'        => Yii::t('app', 'Hash'),
            'created_at'  => Yii::t('app', 'Created'),
            'updated_at'  => Yii::t('app', 'Modified'),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function fields()
    {
        return [
            'model' => function($model) { return self::$model_convertor[$model->tableTypeId]; },
            'parent_id' => 'tableId',
            'name',
            'ext' => 'format',
            'url',
            'hash',
            'created_at',
            'updated_at',
        ];
    }
}
