<?php
namespace common\models\db;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * Place model
 * Адресные данные.
 * 
 * @var integer $id
 * @var integer $model
 * @var integer $parent_id
 * @var string  $city
 * @var integer $region
 * @var string  $district
 * @var string  $address
 * @var string  $geo_lat
 * @var string  $geo_lon
 * @var integer $created_at
 * @var integer $updated_at
 */
class Place extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%place}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                TimestampBehavior::className(),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['model', 'parent_id', 'address'], 'required'],
            ['region', 'integer'],
            [['city', 'district', 'address'], 'string', 'max' => 255],
            [['geo_lat', 'geo_lon'], 'string', 'max' => 16],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'city'       => Yii::t('app', 'City'),
            'region'     => Yii::t('app', 'Region'),
            'district'   => Yii::t('app', 'District'),
            'address'    => Yii::t('app', 'Address'),
            'geo_lat'    => Yii::t('app', 'Latitude'),
            'geo_lon'    => Yii::t('app', 'Longitude'),
            'created_at' => Yii::t('app', 'Created'),
            'updated_at' => Yii::t('app', 'Modified'),
        ];
    }
}
