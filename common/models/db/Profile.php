<?php
namespace common\models\db;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use common\interfaces\ProfileInterface;

/**
 * Profile model
 * Данные персоны.
 *
 * @var integer $id
 * @var integer $model
 * @var integer $parent_id
 * @var string  $inn
 * @var integer $activity
 * @var integer $gender
 * @var integer $birthday
 * @var string  $phone
 * @var string  $first_name
 * @var string  $last_name
 * @var string  $middle_name
 * @var integer $created_at
 * @var integer $updated_at
 */
class Profile extends ActiveRecord implements ProfileInterface
{
    // значения перечислимых переменных
    const GENDER_MALE     = 1;
    const GENDER_FEMALE   = 2;

    const ACTIVITY_ENTERPRENEUR = 13;
    const ACTIVITY_FARMER       = 14;
    const ACTIVITY_SIMPLE       = 15;
    const ACTIVITY_OTHER        = 16;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%profile}}';
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
            [['model', 'parent_id', 'first_name'], 'required'],
            ['inn', 'match', 'pattern' => '/\d{12}/'],
            [['activity', 'gender', 'birthday'], 'integer'],
            ['gender', 'in', 'range' => self::getGenderVariants()],
            ['gender', 'default', 'value' => null],
            ['activity', 'in', 'range' => self::getActivities()],
            ['activity', 'default', 'value' => self::ACTIVITY_SIMPLE],
            ['phone', 'match', 'pattern' => '/^\+7 \d\d\d-\d\d\d-\d\d-\d\d$/',
                'message' => 'Номер должен состоять ровно из 10 цифр.'],
            [['first_name', 'last_name', 'middle_name'], 'string', 'max' => 255],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'inn'         => Yii::t('app', 'INN'),
            'gender'      => Yii::t('app', 'Gender'),
            'birthday'    => Yii::t('app', 'Birthday'),
            'first_name'  => Yii::t('app', 'First name'),
            'last_name'   => Yii::t('app', 'Last name'),
            'middle_name' => Yii::t('app', 'Middle name'),
            'created_at'  => Yii::t('app', 'Created'),
            'updated_at'  => Yii::t('app', 'Modified'),
        ];
    }

    /**
     * Get gender variants
     * @return array
     */
    public static function getGenderVariants() {
        return [
            self::GENDER_MALE,
            self::GENDER_FEMALE, 
        ];
    }

    /**
     * Get activity variants
     * @return array
     */
    public static function getActivities() {
        return [
            self::ACTIVITY_ENTERPRENEUR,
            self::ACTIVITY_FARMER,
            self::ACTIVITY_SIMPLE,
            self::ACTIVITY_OTHER,
        ];
    }
    
    /**
     * Get full name
     * @return string
     */
    public function getFullName() {
        return
            ($this->last_name ? $this->last_name . ' ' : '') .
            $this->first_name . 
            ($this->middle_name ? ' ' . $this->middle_name : '');
    }
}
