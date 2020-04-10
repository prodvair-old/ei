<?php
namespace common\models\db;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use common\models\db\ArbitrInterface;

/**
 * Manager model
 *
 * @property integer $id
 * @property integer $sro_id
 * @property string  $inn
 * @property integer $created_at
 * @property integer $updated_at
 */
class Manager extends ActiveRecord implements ArbitrInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%manager}}';
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
            ['sro_id', 'required'],
            ['inn', 'match', 'pattern' => '/\d{12}/'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'sro_id'     => Yii::t('app', 'SRO'),
            'inn'        => Yii::t('app', 'INN'),
            'created_at' => Yii::t('app', 'Created'),
            'updated_at' => Yii::t('app', 'Modified'),
        ];
    }

    /**
     * Get place manager connected with
     * @return yii\db\ActiveRecord
     */
    public function getPlace() {
        return Place::findOne(['model' => self::INT_CODE, 'parent_id' => $this->id]);
    }

    /**
     * {@inheritdoc}
     */
    public function getAddress() {
        return $this->place->address;
    }

    /**
     * Get manager profile
     * @return yii\db\ActiveRecord
     */
    public function getProfile() {
        return Profile::findOne(['model' => self::INT_CODE, 'parent_id' => $this->id]);
    }

    /**
     * {@inheritdoc}
     */
    public function getFullName() {
        return $this->profile->fullName;
    }
}
