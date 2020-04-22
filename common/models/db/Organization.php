<?php
namespace common\models\db;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use sergmoro1\lookup\Lookup;

/**
 * Organization model
 * Данные организации.
 *
 * @var integer $id
 * @var integer $type
 * @var integer $ownership
 * @var string  $title
 * @var string  $full_title
 * @var string  $inn
 * @var string  $ogrn
 * @var string  $reg_number
 * @var string  $email
 * @var string  $phone
 * @var string  $website
 * @var integer $status
 * @var integer $created_at
 * @var integer $updated_at
 * 
 * @property Place $place
 */
class Organization extends ActiveRecord
{
    // внутренний код модели используемый в составном ключе
    const INT_CODE              = 2;
    
    const STATUS_WAITING        = 1;
    const STATUS_CHECKED        = 2;

    const TYPE_NOMATTER         = 1;
    const TYPE_SRO              = 2;
    const TYPE_ETP              = 3;
    const TYPE_OWNER            = 4;
    const TYPE_BANKRUPT         = 5;

    const OWNERSHIP_ORDINARY    = 1,
    const OWNERSHIP_ENTERPRISER = 2,
    const OWNERSHIP_DISSOLVED   = 3, 
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%organization}}';
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
            [['title', 'inn'], 'required'],
            [['type', 'ownership'], 'integer'],
            ['type', 'in', 'range' => self::getTypes()],
            ['type', 'default', 'value' => self::TYPE_NOMATTER],
            ['ownership', 'in', 'range' => self::getOwnerships()],
            ['ownership', 'default', 'value' => self::OWNERSHIP_ORDINARY],
            ['inn', 'match', 'pattern' => '/\d{10}/'],
            ['ogrn', 'match', 'pattern' => '/\d{13}/'],
            [['title', 'full_title', 'reg_number', 'website'], 'string', 'max' => 255],
            ['email', 'email'],
            ['phone', 'match', 'pattern' => '/^\+7 \d\d\d-\d\d\d-\d\d-\d\d$/',
            ['website', 'url'],
            ['status', 'in', 'range' => self::getStatuses()],
            ['status', 'default', 'value' => self::STATUS_WAITING],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'title'       => Yii::t('app', 'Title'),
            'type'        => Yii::t('app', 'Type'),
            'ownership'   => Yii::t('app', 'Ownership'),
            'inn'         => Yii::t('app', 'INN'),
            'ogrn'        => Yii::t('app', 'OGRN'),
            'reg_number'  => Yii::t('app', 'Reg number'),
            'email'       => Yii::t('app', 'Email'),
            'phone'       => Yii::t('app', 'Phone'),
            'website'     => Yii::t('app', 'Website'),
            'status'      => Yii::t('app', 'Status'),
            'created_at'  => Yii::t('app', 'Created'),
            'updated_at'  => Yii::t('app', 'Modified'),
        ];
    }

    /**
     * Get status variants
     * @return array
     */
    public static function getStatuses() {
        return [
            self::STATUS_WAITING,
            self::STATUS_CHECKED, 
        ];
    }

    /**
     * Get type variants
     * @return array
     */
    public static function getTypes() {
        return [
            self::TYPE_NOMATTER,
            self::TYPE_SRO, 
            self::TYPE_OWNER, 
            self::TYPE_ETP, 
            self::TYPE_BANKRUPT, 
        ];
    }

    /**
     * Get ownership variants
     * @return array
     */
    public static function getOwnerships() {
        return [
            self::OWNERSHIP_ORDINARY,
            self::OWNERSHIP_ENTERPRISER,
            self::OWNERSHIP_DISSOLVED, 
        ];
    }

    /**
     * Получить информацию о месте
     * @return yii\db\ActiveRecord
     */
    public function getPlace() {
        return Place::findOne(['model' => self::INT_CODE, 'parent_id' => 'id']);
    }
}
