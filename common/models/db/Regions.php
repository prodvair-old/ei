<?php

namespace common\models\db;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * Regions model
 * Список регионов.
 *
 * @var integer $id
 * @var string $name
 * @var string $name_translit
 * @var integer $created_at
 * @var integer $updated_at
 */
class Regions extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%regions}}';
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
            [['id', 'name', 'name_translit'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'            => Yii::t('app', 'Код региона'),
            'name'          => Yii::t('app', 'Наименование региона'),
            'name_translit' => Yii::t('app', 'Наименование региона в транслитерации'),
            'created_at'    => Yii::t('app', 'Created'),
            'updated_at'    => Yii::t('app', 'Modified'),
        ];
    }
}
