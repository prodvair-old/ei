<?php

namespace common\models\db;

use Yii;
use yii\db\ActiveRecord;
use yii\web\NotFoundHttpException;
use yii\behaviors\TimestampBehavior;

/**
 * Tariff model
 * Subscription tariffs.
 *
 * @var integer $id
 * @var string $name
 * @var text $description
 * @var integer $fee
 * @var integer $cteated_at
 * @var integer $updated_at last updated
 */
class Tariff extends ActiveRecord
{
    // internal model code used in the composite key
    const INT_CODE = 22;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%tariff}}';
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
            [['name', 'fee'], 'required'],
            [['name'], 'string', 'max' => 255],
            ['fee', 'integer'],
            [['description', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name'        => Yii::t('app', 'Name'),
            'description' => Yii::t('app', 'Description'),
            'fee'         => Yii::t('app', 'Fee'),
            'created_at'  => Yii::t('app', 'Created'),
            'updated_at'  => Yii::t('app', 'Modified'),
        ];
    }

    /**
     * @return mixed
     */
    public function getPeriods()
    {
        return [
            ['term' => 1, 'fee' => 199],
            ['term' => 30, 'fee' => 599],
            ['term' => 90, 'fee' => 1500],
            ['term' => 180, 'fee' => 2800],
            ['term' => 360, 'fee' => 5000]
        ];
    }
}
