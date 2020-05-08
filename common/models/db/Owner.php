<?php

namespace common\models\db;

use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use sergmoro1\uploader\behaviors\HaveFileBehavior;
use common\traits\Company;

/**
 * Owner model
 * Владельцы лотов
 *
 * @var integer $id
 * @var string  $slug
 * @var text    $description
 * @var integer $created_at
 * @var integer $updated_at
 * 
 * @property Organization $organization
 * @property Place $place
 */
class Owner extends ActiveRecord
{
    use Company;
    
    // внутренний код модели используемый в составном ключе
    const INT_CODE = 13;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%owner}}';
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
			[
				'class' => HaveFileBehavior::className(),
				'file_path' => '/owner/',
                'sizes' => [
                    'original'  => ['width' => 600, 'height' => 600, 'catalog' => 'original'],
                    'main'      => ['width' => 300, 'height' => 300, 'catalog' => ''],
                    'thumb'     => ['width' => 90,  'height' => 90,  'catalog' => 'thumb'],
                ],
			],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['slug'], 'required'],
            [['description', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'slug'        => Yii::t('app', 'Slug'),
            'description' => Yii::t('app', 'Description'),
            'created_at'  => Yii::t('app', 'Created'),
            'updated_at'  => Yii::t('app', 'Modified'),
        ];
    }
}
