<?php
namespace common\models\db;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * Torg model
 * Торг, аукцион по продаже лотов.
 *
 * @property integer $id
 * @property integer $etp_id
 * @property integer $case_id
 * @property integer $property
 * @property text    $description
 * @property string  $started_at
 * @property string  $end_at
 * @property string  $completed_at
 * @property string  $published_at
 * @property integer $auction
 * @property integer $created_at
 * @property integer $updated_at
 */
class Torg extends ActiveRecord
{
    // внутренний код модели используемый в составном ключе
    const INT_CODE = 5;

    const PROPERTY_DEBTOR = 1;
    const PROPERTY_PLEDGE = 2;


    const AUCTION_OPEN    = 1;
    const AUCTION_PUBLIC  = 2;

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
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['etp_id', 'case_id', 'property', 'description'], 'required'],
            [['etp_id', 'case_id', 'propery', 'auction'], 'integer'],
            [['started_at', 'end_at', 'completed_at', 'published_at'], 'date', 'format' => 'php:Y-m-d H:i:s+O'],
            ['property', 'in', 'range' => self::getPropertyTypes()],
            ['auction', 'in', 'range' => self::getAuctionTypes()],
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
            'property'     => Yii::t('app', 'Property type'),
            'description'  => Yii::t('app', 'Description'),
            'started_at'   => Yii::t('app', 'Start'),
            'end_at'       => Yii::t('app', 'End'),
            'completed_at' => Yii::t('app', 'Completed'),
            'published_at' => Yii::t('app', 'Published'),
            'auction'      => Yii::t('app', 'Auction type'),
            'created_at'   => Yii::t('app', 'Created'),
            'updated_at'   => Yii::t('app', 'Modified'),
        ];
    }

    /**
     * Get property types
     * @return array
     */
    public static function getPropertyTypes() {
        return [
            self::PROPERTY_DEBTOR,
            self::PROPERTY_PLEDGE,
        ];
    }

    /**
     * Get auction types
     * @return array
     */
    public static function getAuctionTypes() {
        return [
            self::AUCTION_OPEN,
            self::AUCTION_PUBLIC,
        ];
    }

    /**
     * Получить информацию о должнике
     * @return yii\db\ActiveRecord
     */
    public function getDebtor()
    {
        return $this->hasOne(TorgDebtor::className(), ['id' => 'torg_id']);
    }

    /**
     * Получить информацию о залоге
     * @return yii\db\ActiveRecord
     */
    public function getPledge()
    {
        return $this->hasOne(TorgPledge::className(), ['id' => 'torg_id']);
    }
}
