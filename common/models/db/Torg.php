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
 * @property Casefile $case
 * @property sergmoro1\uploader\models\OneFile[] $files
 */
class Torg extends ActiveRecord
{
    // внутренний код модели используемый в составном ключе
    const INT_CODE = 7;

    // тип имущества
    const PROPERTY_BANKRUPT  = 1;
    const PROPERTY_ARRESTED  = 2;
    const PROPERTY_ZALOG     = 3;
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
                'class' => TimestampBehavior::className(),
            ],
			// [
			// 	'class' => HaveFileBehavior::className(),
			// 	'file_path' => 'torg/',
            //     'sizes' => [
            //         'original'  => ['width' => 1600, 'height' => 900, 'catalog' => 'original'],
            //         'main'      => ['width' => 400,  'height' => 400, 'catalog' => ''],
            //         'thumb'     => ['width' => 90,   'height' => 90,  'catalog' => 'thumb'],
            //     ],
			// ],
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['property', 'required'],
            [['property', 'offer'], 'integer'],
            //[['started_at', 'end_at', 'completed_at', 'published_at'], 'integer', 'skipOnEmpty' => true],
            ['property', 'in', 'range' => self::getProperties()],
            ['offer', 'in', 'range' => self::getOffers()],
            [['description', 'etp_id', 'started_at', 'end_at', 'completed_at', 'published_at', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'etp_id'       => Yii::t('app', 'Etp'),
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
            self::PROPERTY_ARRESTED,
            self::PROPERTY_ZALOG,
            self::PROPERTY_MUNICIPAL,
        ];
    }

    /**
     * Get offer types
     * @return array
     */
    public static function getOffers() {
        return [
            self::OFFER_PUBLIC,
            self::OFFER_AUCTION,
            self::OFFER_AUCTION_OPEN,
            self::OFFER_CONTEST,
            self::OFFER_CONTEST_OPEN,
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
        return $this->hasOne(Bankrupt::className(), ['id' => 'bankrupt_id'])
            ->viaTable(TorgDebtor::tableName(), ['torg_id' => 'id']);
    }

    /**
     * Получить информацию об управляющем
     * @return yii\db\ActiveQuery
     */
    public function getManager() {
        return $this->hasOne(Manager::className(), ['id' => 'manager_id'])
            ->viaTable(TorgDebtor::tableName(), ['torg_id' => 'id']);
    }
    
    /**
     * Получить информацию о залогодержателе
     * @return yii\db\ActiveQuery
     */
    public function getOwner() {
        return $this->hasOne(Owner::className(), ['id' => 'owner_id'])
            ->viaTable(TorgPledge::tableName(), ['torg_id' => 'id']);
    }

    /**
     * Получить информацию о собственнике залога
     * @return yii\db\ActiveQuery
     */
    public function getUser() {
        return $this->hasOne(User::className(), ['id' => 'user_id'])
            ->viaTable(TorgPledge::tableName(), ['torg_id' => 'id']);
    }

    /**
     * Получить эдектронную торговую площадку (ETP)
     * 
     * @return yii\db\ActiveRecord
     */
    public function getEtp()
    {
        return $this->hasOne(Organization::className(), ['id' => 'etp_id'])
            ->viaTable(TorgDebtor::tableName(), ['torg_id' => 'id']);
    }
    
    /**
     * Получить дело по торгу
     *
     * @return yii\db\ActiveQuery
     */
    public function getCase() {
        return $this->hasOne(Casefile::className(), ['id' => 'case_id'])
            ->viaTable(TorgDebtor::tableName(), ['torg_id' => 'id']);
    }
}
