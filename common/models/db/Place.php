<?php
namespace common\models\db;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use common\interfaces\PlaceInterface;

/**
 * Place model
 * Адресные данные.
 * 
 * @var integer $id
 * @var integer $model
 * @var integer $parent_id
 * @var string  $city
 * @var integer $region_id
 * @var integer $district_id
 * @var text    $address
 * @var string  $geo_lat
 * @var string  $geo_lon
 * @var integer $created_at
 * @var integer $updated_at
 */
class Place extends ActiveRecord implements PlaceInterface
{
    // сценарии
    const SCENARIO_CREATE = 'place_create';

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
            [['model', 'parent_id'], 'required', 'except' => self::SCENARIO_CREATE],
            ['address', 'required'],
            [['model', 'parent_id', 'region_id', 'district_id'], 'integer'],
            [['city'], 'string', 'max' => 255],
            ['address', 'string', 'max' => 512],
            [['geo_lat', 'geo_lon'],'string'],
            // [['geo_lat', 'geo_lon'],'match', 'pattern' => '/^\d{1,5}\.\d{0,10}$/'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'city'        => Yii::t('app', 'City'),
            'region_id'   => Yii::t('app', 'Region'),
            'district_id' => Yii::t('app', 'District'),
            'address'     => Yii::t('app', 'Address'),
            'geo_lat'     => Yii::t('app', 'Latitude'),
            'geo_lon'     => Yii::t('app', 'Longitude'),
            'created_at'  => Yii::t('app', 'Created'),
            'updated_at'  => Yii::t('app', 'Modified'),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * {@inheritdoc}
     */
    public function getRegion()
    {
        return $this->hasOne(Region::className(), ['id' => 'region_id']);
    }

    public function getLot()
    {
        return $this->hasOne(Lot::className(), ['id' => 'parent_id']);
    }
}
