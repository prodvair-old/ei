<?php

namespace common\models\db;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * District model
 * Справочник округов.
 *
 * @var integer $id
 * @var string  $name
 * @var integer $created_at
 */
class District extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%district}}';
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
            [['id', 'name'], 'required'],
            ['created_at', 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name'       => Yii::t('app', 'District'),
            'created_at' => Yii::t('app', 'Created'),
        ];
    }

    /**
     * Get list of items.
     * @return array id => name pairs
     */
    public static function items()
    {
        $a = [];
        foreach(self::find()->select(['id', 'name'])->all() as $model)
            $a[$model->id] = $model->name;
        return $a;
    }
}
