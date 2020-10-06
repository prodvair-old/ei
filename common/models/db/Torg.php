<?php
namespace common\models\db;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use sergmoro1\lookup\models\Lookup;
use common\components\IntCode;

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
 * @property Casefile $casefile
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
            [['property'], 'required'],
            [['started_at'], 'required', 'except' => self::SCENARIO_MIGRATION],
            ['msg_id', 'required', 'on' => self::SCENARIO_MIGRATION],
            ['offer', 'required', 'except' => self::SCENARIO_MIGRATION],
            ['msg_id', 'string', 'max' => 255],
            [['property', 'offer'], 'integer'],
            ['property', 'in', 'range' => self::getProperties()],
            ['offer', 'in', 'range' => self::getOffers()],
			['started_at', 'date', 'format' => 'dd.MM.yyyy', 'timestampAttribute' => 'started_at'],
			['end_at', 'date', 'format' => 'dd.MM.yyyy', 'timestampAttribute' => 'end_at'],
			['completed_at', 'date', 'format' => 'dd.MM.yyyy', 'timestampAttribute' => 'completed_at'],
			['published_at', 'date', 'format' => 'dd.MM.yyyy', 'timestampAttribute' => 'published_at'],
            [['description', 'etp_id', 'created_at', 'updated_at'], 'safe'],
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
     * Получить информацию о других лотах торга
     * @param $currentLot
     * @return array|ActiveRecord[]
     */
    public function getOtherLots($currentLot, $limit = 15)
    {
        return $this->hasMany(Lot::className(), ['torg_id' => 'id'])
            ->andFilterWhere(['!=', Lot::tableName() . '.id', $currentLot])
            ->limit($limit)
            ->all();
    }

    /**
     * Получить информацию о должнике
     * @return yii\db\ActiveQuery
     * @throws InvalidConfigException
     * @throws \yii\base\InvalidConfigException
     */
    public function getBankrupt()
    {
        return $this->hasOne(Organization::className(), ['parent_id' => 'bankrupt_id'])
            ->where(['organization.model' => Bankrupt::INT_CODE])
            ->viaTable(TorgDebtor::tableName(), ['torg_id' => 'id']);
    }

    public function getBankruptEtp() {
        return $this->hasOne(Etp::className(), ['id' => 'etp_id'])
            ->viaTable(TorgDebtor::tableName(), ['torg_id' => 'id']);
    }

    public function getBankruptProfile()
    {
        return $this->hasOne(Profile::className(), ['parent_id' => 'bankrupt_id'])
            ->where(['profile.model' => Bankrupt::INT_CODE])
            ->viaTable(TorgDebtor::tableName(), ['torg_id' => 'id']);
    }

    /**
     * Получить информацию об управляющем
     * @return yii\db\ActiveQuery
     * @throws InvalidConfigException
     */
    public function getManager()
    {
        if ($this->property == self::PROPERTY_ZALOG)
            return null;
        return $this->hasOne(Manager::className(), ['id' => 'manager_id'])
            ->viaTable(TorgDebtor::tableName(), ['torg_id' => 'id']);
    }

    /**
     * Получить эдектронную торговую площадку (ETP)
     *
     * @return \yii\db\ActiveQuery
     * @throws InvalidConfigException
     */
    public function getEtp()
    {
//        if ($this->property != self::PROPERTY_BANKRUPT)
//            return null;
        return $this->hasOne(Organization::className(), ['parent_id' => 'etp_id'])
            ->andFilterWhere(['=', Organization::tableName() . '.model', Etp::INT_CODE])
            ->viaTable(TorgDebtor::tableName(), ['torg_id' => 'id']);
    }

    /**
     * Получить дело по торгу
     *
     * @return yii\db\ActiveQuery
     * @throws InvalidConfigException
     */
    public function getCasefile()
    {
        if ($this->property != self::PROPERTY_BANKRUPT)
            return null;
        return $this->hasOne(Casefile::className(), ['id' => 'case_id'])
            ->viaTable(TorgDebtor::tableName(), ['torg_id' => 'id']);
    }

    /**
     * Get Debtor link
     * @return yii\db\ActiveQuery
     */
    public function getDebtor()
    {
        return $this->hasOne(TorgDebtor::className(), ['torg_id' => 'id']);
    }
    
    /**
     * Get Pledge link
     * @return yii\db\ActiveQuery
     */
    public function getTorgPledge()
    {
        return $this->hasOne(TorgPledge::className(), ['torg_id' => 'id']);
    }

    /**
     * Получить информацию о залогодержателе
     * @return yii\db\ActiveQuery
     * @throws InvalidConfigException
     */
    public function getOwner()
    {
//        if ($this->property != self::PROPERTY_ZALOG)
//            return null;
        return $this->hasOne(Organization::className(), ['parent_id' => 'owner_id'])
            ->andFilterWhere(['=', Organization::tableName() . '.model', Owner::INT_CODE])
            ->viaTable(TorgPledge::tableName(), ['torg_id' => 'id']);
    }

    /**
     * Получить информацию о собственнике залога
     * @return yii\db\ActiveQuery
     * @throws InvalidConfigException
     */
    public function getUser()
    {
        if ($this->property != self::PROPERTY_ZALOG)
            return null;
        return $this->hasOne(User::className(), ['id' => 'user_id'])
            ->viaTable(TorgPledge::tableName(), ['torg_id' => 'id']);
    }

    /**
     * Получить документы по торгу.
     *
     * @return yii\db\ActiveQuery
     */
    public function getDocuments()
    {
        return $this->hasMany(Document::className(), ['parent_id' => 'id'])
            ->andOnCondition(['=', Document::tableName() . '.model', self::INT_CODE]);
    }

    /**
     * Calculate - at what stage of the auction (Torg).
     *
     * @param integer $torg_id
     * @param integer $property
     * @return integer count of auctions for bankrupt property or empty string for others
     */
    public static function getStage($torg_id, $property)
    {
        if ($property != self::PROPERTY_BANKRUPT)
            return '';
        $select =
            // select count of torgs by the casefile
            'select count(torg_debtor.torg_id) as stage '. 
            'from eidb.torg_debtor ' .
            'where case_id = ('.
                // select casefile of the torg
                'select torg_debtor.case_id from eidb.torg '. 
                'inner join eidb.torg_debtor on (torg.id=torg_debtor.torg_id) '.
                'where torg.id=:id'.
            ')';

        $db = Yii::$app->db;
        if ($db->driverName === 'mysql')
            $select = str_replace('eidb.', '', $select);
        $command = $db->createCommand($select);
        $command->bindValue(':id', $torg_id);
        return $command->queryScalar();
    }

    /**
     * @return array
     */
    public static function getTypeList()
    {
        $result[ '0' ] = 'Все типы';
        $result += Lookup::items('TorgProperty');
        return $result;
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

    /**
     * {@inheritdoc}
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {

            if (!$this->msg_id)
                // if the field is empty, generate a unique value
                $this->msg_id = uniqid('u/'); // 20200924 -> $this->torg_id . '/' . date('dmy', $this->created_at)

            return true;
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function beforeDelete()
    {
        foreach($this->lots as $lot)
            $lot->delete();
        foreach($this->documents as $document)
            $document->delete();
        $model = null;
        switch ($this->property) {
            case self::PROPERTY_BANKRUPT:
                $model = TorgDebtor::findOne(['torg_id' => $this->id]);
                break;
            case self::PROPERTY_ZALOG:
                $model = TorgPledge::findOne(['torg_id' => $this->id]);
                break;
            case self::PROPERTY_ARRESTED:
            case self::PROPERTY_MUNICIPAL:
                $model = TorgDrawish::findOne(['torg_id' => $this->id]);
        }
        if ($model)
            $model->delete();
        
        return parent::beforeDelete();
    }
}
