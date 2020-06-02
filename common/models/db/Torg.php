<?php
namespace common\models\db;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use sergmoro1\lookup\models\Lookup;

/**
 * Torg model
 * Торг, аукцион по продаже лотов.
 *
 * @var integer $id
 * @var string  $msg_id
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
 * @property Document[] $documents
 */
class Torg extends ActiveRecord
{
    // сценарии
    const SCENARIO_MIGRATION = 'torg_migration';
    
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

    const SHORT_TITLE_LENGTH = 20;

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
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['property', 'started_at'], 'required'],
            ['msg_id', 'required', 'on' => self::SCENARIO_MIGRATION],
            ['offer', 'required', 'except' => self::SCENARIO_MIGRATION],
            ['msg_id', 'string', 'max' => 255],
            [['property', 'offer'], 'integer'],
            ['property', 'in', 'range' => self::getProperties()],
            ['offer', 'in', 'range' => self::getOffers()],
			['started_at', 'datetime', 'except' => self::SCENARIO_MIGRATION, 'format' => 'php:d.m.Y', 'timestampAttribute' => 'started_at'],
			['end_at', 'datetime', 'except' => self::SCENARIO_MIGRATION, 'format' => 'php:d.m.Y', 'timestampAttribute' => 'end_at'],
			['completed_at', 'date', 'except' => self::SCENARIO_MIGRATION, 'format' => 'php:d.m.Y', 'timestampAttribute' => 'completed_at'],
			['published_at', 'date', 'except' => self::SCENARIO_MIGRATION, 'format' => 'php:d.m.Y', 'timestampAttribute' => 'published_at'],
            [['end_at', 'completed_at', 'published_at'], 'default', 'value' => null],
            [['end_at', 'completed_at', 'published_at'], 'safe', 'on' => self::SCENARIO_MIGRATION],
            [['description', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'msg_id'       => Yii::t('app', 'Message'),
            'property'     => Yii::t('app', 'Property'),
            'description'  => Yii::t('app', 'Description'),
            'started_at'   => Yii::t('app', 'Start at'),
            'end_at'       => Yii::t('app', 'End at'),
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
     * Get short title
     * @return string
     */
    public function getShortTitle() {
        mb_internal_encoding('UTF-8');
        return mb_strlen($this->description) > self::SHORT_TITLE_LENGTH
            ? mb_substr($this->description, 0, self::SHORT_TITLE_LENGTH) . '...'
            : $this->description;
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
        if ($this->property != self::PROPERTY_BANKRUPT)
            return null;
        return $this->hasOne(Bankrupt::className(), ['id' => 'bankrupt_id'])
            ->viaTable(TorgDebtor::tableName(), ['torg_id' => 'id']);
    }

    /**
     * Получить информацию об управляющем
     * @return yii\db\ActiveQuery
     */
    public function getManager() {
        if ($this->property == self::PROPERTY_ZALOG)
            return null;
        return $this->hasOne(Manager::className(), ['id' => 'manager_id'])
            ->viaTable(TorgDrawish::tableName(), ['torg_id' => 'id']);
    }
    
    /**
     * Получить эдектронную торговую площадку (ETP)
     * 
     * @return yii\db\ActiveRecord
     */
    public function getEtp()
    {
        if ($this->property != self::PROPERTY_BANKRUPT)
            return null;
        $torg_debtor = TorgDebtor::find()->select(['etp_id'])->where(['torg_id' =>$this->id]);
        return Organization::findOne(['model' => Etp::INT_CODE, 'parent_id' => $torg_debtor->etp_id]);
    }
    
    /**
     * Получить дело по торгу
     *
     * @return yii\db\ActiveQuery
     */
    public function getCasefile() {
        if ($this->property != self::PROPERTY_BANKRUPT)
            return null;
        return $this->hasOne(Casefile::className(), ['id' => 'case_id'])
            ->viaTable(TorgDebtor::tableName(), ['torg_id' => 'id']);
    }

    /**
     * Получить связи залогодержателя
     * @return yii\db\ActiveRecord
     */
    public function getTorgPledge() {
        return TorgPledge::findOne(['torg_id' => $this->id]);
    }

    /**
     * Получить информацию о залогодержателе
     * @return yii\db\ActiveQuery
     */
    public function getOwner() {
        if ($this->property != self::PROPERTY_ZALOG)
            return null;
        return $this->hasOne(Owner::className(), ['id' => 'owner_id'])
            ->viaTable(TorgPledge::tableName(), ['torg_id' => 'id']);
    }

    /**
     * Получить информацию о собственнике залога
     * @return yii\db\ActiveQuery
     */
    public function getUser() {
        if ($this->property != self::PROPERTY_ZALOG)
            return null;
        return $this->hasOne(User::className(), ['id' => 'user_id'])
            ->viaTable(TorgPledge::tableName(), ['torg_id' => 'id']);
    }

    /**
     * Получить документы по торгу.
     * 
     * @return yii\db\ActiveRecord
     */
    public function getDocuments()
    {
        return Document::find()
            ->where(['model' => self::INT_CODE, 'parent_id' => $this->id])
            ->all();
    }

    /**
     * Получить ответственного за торг.
     * 
     * @return yii\db\ActiveRecord
     */
    public function getResponsible()
    {
        switch($this->property) { 
            case self::PROPERTY_BANKRUPT:
                return $this->getBankrupt();
            case self::PROPERTY_ARRESTED:
            case self::PROPERTY_MUNICIPAL:
                return $this->getManager();
            case self::PROPERTY_ZALOG:
                return $this->getUser();
        }
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
     
            if (!$this->msg_id)
                $this->msg_id = 'u/' . $this->torg_id . '/' . date('dmy', $this->created_at);
            
            return true;
        }
        return false;
    }
}
