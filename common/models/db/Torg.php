<?php
namespace common\models\db;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use sergmoro1\lookup\models\Lookup;
use sergmoro1\uploader\behaviors\HaveFileBehavior;

/**
 * Torg model
 * Торг, аукцион по продаже лотов.
 *
 * @var integer $id
 * @var integer $etp_id
 * @var integer $case_id
 * @var integer $property
 * @var text    $description
 * @var string  $started_at
 * @var string  $end_at
 * @var string  $completed_at
 * @var string  $published_at
 * @var integer $offer
 * @var integer $created_at
 * @var integer $updated_at
 * 
 * @property Lot[]    $lots
 * @property Bankrupt $bankrupt
 * @property Manager  $manager
 * @property Owner    $owner
 * @property User     $user
 * @property Etp      $etp
 * @property Case     $case
 * @property sergmoro1\uploader\models\OneFile[] $files
 */
class Torg extends ActiveRecord
{
    // внутренний код модели используемый в составном ключе
    const INT_CODE = 7;

    // тип имущества
    const PROPERTY_BANKRUPT  = 1;
    const PROPERTY_ZALOG     = 2;
    const PROPERTY_ARRESTED  = 3;
    const PROPERTY_MUNICIPAL = 4;

    // тип предложения
    const OFFER_PUBLIC       = 1;
    const OFFER_AUCTION      = 2;
    const OFFER_AUCTION_OPEN = 3;
    const OFFER_CONTEST      = 4;
    const OFFER_CONTEST_OPEN = 5;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%torg}}';
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
			[
				'class' => HaveFileBehavior::className(),
				'file_path' => '/torg/',
			],
        ];
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
			[
				'class' => HaveFileBehavior::className(),
				'file_path' => '/lot/',
                'sizes' => [
                    'original'  => ['width' => 1600, 'height' => 900, 'catalog' => 'original'],
                    'main'      => ['width' => 400,  'height' => 400, 'catalog' => ''],
                    'thumb'     => ['width' => 90,   'height' => 90,  'catalog' => 'thumb'],
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
            [['etp_id', 'case_id', 'property', 'description'], 'required'],
            [['etp_id', 'case_id', 'propery', 'offer'], 'integer'],
            [['started_at', 'end_at', 'completed_at', 'published_at'], 'date', 'format' => 'php:Y-m-d H:i:s+O'],
            ['property', 'in', 'range' => self::getProperties()],
            ['offer', 'in', 'range' => self::getOffers()],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'etp_id'       => Yii::t('app', 'Etp'),
            'case_id'      => Yii::t('app', 'Case'),
            'property'     => Yii::t('app', 'Property'),
            'description'  => Yii::t('app', 'Description'),
            'started_at'   => Yii::t('app', 'Start'),
            'end_at'       => Yii::t('app', 'End'),
            'completed_at' => Yii::t('app', 'Completed'),
            'published_at' => Yii::t('app', 'Published'),
            'offer'        => Yii::t('app', 'Offer'),
            'created_at'   => Yii::t('app', 'Created'),
            'updated_at'   => Yii::t('app', 'Modified'),
        ];
    }

    /**
     * Get property types
     * @return array
     */
    public static function getProperties() {
        return [
            self::PROPERTY_BANKRUPT,
            self::PROPERTY_ZALOG,
            self::PROPERTY_ARRESTED,
            self::PROPERTY_MUNICIPAL,
        ];
    }

    /**
     * Get offer types
     * @return array
     */
    public static function getOffers() {
        return [
            self::PROPERTY_BANKRUPT,
            self::PROPERTY_ZALOG,
            self::PROPERTY_ARRESTED,
            self::PROPERTY_MUNICIPAL,
        ];
    }

    /**
     * Получить информацию о лотах
     * @return yii\db\ActiveQuery
     */
    public function getLots()
    {
        return $this->hasMany(Lot::className(), ['torg_id' => 'id']);
    }
    
    /**
     * Получить информацию о должнике
     * @return yii\db\ActiveQuery
     */
    public function getBankrupt() {
        return $this->hasOne(Torg::className(), ['id' => 'torg_id'])
            ->viaTable(TorgDebtor::tableName(), ['bankrupt_id' => 'id']);
    }

    /**
     * Получить информацию об управляющем
     * @return yii\db\ActiveQuery
     */
    public function getManager() {
        return $this->hasOne(Torg::className(), ['id' => 'torg_id'])
            ->viaTable(TorgDebtor::tableName(), ['manager_id' => 'id']);
    }
    
    /**
     * Получить информацию о залогодержателе
     * @return yii\db\ActiveQuery
     */
    public function getOwner() {
        return $this->hasOne(Torg::className(), ['id' => 'torg_id'])
            ->viaTable(TorgPledge::tableName(), ['owner_id' => 'id']);
    }

    /**
     * Получить информацию о собственнике залога
     * @return yii\db\ActiveQuery
     */
    public function getUser() {
        return $this->hasOne(Torg::className(), ['id' => 'torg_id'])
            ->viaTable(TorgPledge::tableName(), ['user_id' => 'id']);
    }

    /**
     * Получить эдектронную торговую площадку (ETP)
     * 
     * @return yii\db\ActiveRecord
     */
    public function getEtp()
    {
        return Organization::findOne(['model' => Organization::TYPE_ETP, 'parent_id' => $this->id]);
    }
    
    /**
     * Получить дело торга
     * @return yii\db\ActiveQuery
     */
    public function getCase() {
        return $this->hasOne(Case::className(), ['id' => 'case_id']);
    }
}
