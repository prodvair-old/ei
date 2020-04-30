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
 * @var integer $model
 * @var integer $parent_id
 * @var integer $activity
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
    
    // значения перечислимых переменных
    const STATUS_WAITING        = 1;
    const STATUS_CHECKED        = 2;

    const TYPE_NOMATTER         = 11;
    const TYPE_SRO              = 12;
    const TYPE_ETP              = 13;
    const TYPE_OWNER            = 14;
    const TYPE_BANKRUPT         = 15;
    const TYPE_MANAGER          = 3;

    const ACTIVITY_ABSENTBANKRUPT       = 1;
    const ACTIVITY_AGRICULTURE          = 2;
    const ACTIVITY_CITY                 = 3;
    const ACTIVITY_CREDIT               = 4;
    const ACTIVITY_DEVELOPMENT          = 5;
    const ACTIVITY_DISSOLVED_BANKRUPT   = 6;
    const ACTIVITY_INSURANCE            = 7;
    const ACTIVITY_MONOPOLY             = 8;
    const ACTIVITY_OTHER                = 9;
    const ACTIVITY_PRIVATE_PENSION_FUND = 10;
    const ACTIVITY_SIMPLE               = 11;
    const ACTIVITY_STRATEGIC            = 12;
    
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
            [['model', 'parent_id', 'title', 'inn'], 'required'],
            [['model', 'parent_id', 'activity'], 'integer'],
            ['model', 'in', 'range' => self::getTypes()],
            ['model', 'default', 'value' => self::TYPE_NOMATTER],
            ['activity', 'in', 'range' => self::getActivities()],
            ['activity', 'default', 'value' => self::ACTIVITY_SIMPLE],
            ['inn', 'match', 'pattern' => '/\d{10,12}/', 'skipOnEmpty' => true],
            ['ogrn', 'match', 'pattern' => '/\d{13,15}/', 'skipOnEmpty' => true],
            [['title', 'full_title', 'reg_number', 'phone', 'website'], 'string', 'max' => 255],
            ['email', 'email'],
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
            self::TYPE_MANAGER, 
        ];
    }

    /**
     * Get activity variants
     * @return array
     */
    public static function getActivities() {
        return [
            self::ACTIVITY_ABSENTBANKRUPT,
            self::ACTIVITY_AGRICULTURE,
            self::ACTIVITY_CITY,
            self::ACTIVITY_CREDIT,
            self::ACTIVITY_DEVELOPMENT,
            self::ACTIVITY_DISSOLVED_BANKRUPT,
            self::ACTIVITY_INSURANCE,
            self::ACTIVITY_MONOPOLY,
            self::ACTIVITY_OTHER,
            self::ACTIVITY_PRIVATE_PENSION_FUND,
            self::ACTIVITY_SIMPLE,
            self::ACTIVITY_STRATEGIC,
        ];
    }

    /**
     * Получить информацию о месте
     * @return yii\db\ActiveRecord
     */
    public function getPlace() {
        return Place::findOne(['model' => self::INT_CODE, 'parent_id' => $this->id]);
    }
}
