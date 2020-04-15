<?php
namespace common\models\db;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use common\interfaces\ProfileInterface;

/**
 * Owner model
 * Владелец залога.
 *
 * @property integer $id
 * @property string  $title
 * @property string  $link
 * @property string  $logo
 * @property string  $description
 * @property string  $email
 * @property string  $phone
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class Owner extends ActiveRecord implements ProfileInterface
{
    // внутренний код модели используемый в составном ключе
    const INT_CODE = 4;

    const STATUS_WAITING = 1;
    const STATUS_CHECKED = 2;

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
            [['title', 'link'], 'required'],
            [['logo', 'email', 'link'], 'string', 'max' => 255],
            ['link', 'url', 'defaultScheme' => 'http'],
            ['email', 'email'],
            ['phone', 'match', 'pattern' => '/^\+7 \d\d\d-\d\d\d-\d\d-\d\d$/',
                'message' => 'Номер должен состоять ровно из 10 цифр.'],
            ['status', 'in', 'range' => self::getStatuses()],
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
            'link'        => Yii::t('app', 'Link'),
            'logo'        => Yii::t('app', 'Logo'),
            'description' => Yii::t('app', 'Description'),
            'email'       => Yii::t('app', 'Email'),
            'phone'       => Yii::t('app', 'Phone'),
            'created_at'  => Yii::t('app', 'Created'),
            'updated_at'  => Yii::t('app', 'Modified'),
        ];
    }

    /**
     * Get statuses
     * @return array
     */
    public static function getStatuses() {
        return [
            self::STATUS_WAITING,
            self::STATUS_CHECKED, 
        ];
    }

    /**
     * Get place owner connected with
     * @return yii\db\ActiveRecord
     */
    public function getPlace()
    {
        return Place::findOne(['model' => self::INT_CODE, 'parent_id' => $this->id]);
    }

    /**
     * @inheritdoc
     */
    public function getFullName()
    {
        return $this->title;
    };
}
