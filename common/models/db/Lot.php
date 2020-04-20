<?php
namespace common\models\db;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use sergmoro1\uploader\behaviors\HaveFileBehavior;
use sergmoro1\lookup\models\Lookup;

/**
 * Lot model
 * Информация о лоте.
 *
 * @property integer $id
 * @property integer $torg_id
 * @property string  $title
 * @property text    $description
 * @property float   $start_price
 * @property float   $step
 * @property integer $step_measure
 * @property float   $deposite
 * @property integer $deposite_measure
 * @property integer $status
 * @property integer $reason
 * @property integer $created_at
 * @property integer $updated_at
 */
class Log extends ActiveRecord
{
    // внутренний код модели используемый в составном ключе
    const INT_CODE = 6;
    
    const MEASURE_PERCENT    = 1;
    const MEASURE_SUM        = 2;

    const STATUS_IN_PROGRESS = 1;
    const STATUS_ANNOUNCED   = 2;
    const STATUS_APPLICATION = 3;
    const STATUS_CANCELLED   = 4;
    const STATUS_COMPLETED   = 5;
    const STATUS_SUSPENDED   = 6;
    const STATUS_ARCHIVED    = 7;

    const REASON_NO_MATTER   = 1; 
    const REASON_PRICE       = 2;
    const REASON_CONTRACT    = 3;
    const REASON_PARTICIPANT = 4;
    const REASON_SUMMARIZING = 5;

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
            [['torg_id', 'title', 'start_price', 'step', 'deposite'], 'required'],
            ['title', 'string', 'max' => 255],
            [['start_price', 'step', 'deposite'], 'number', 'numberFormat' => '/^\s*[-+]?[0-9]*\.?\d{0,2}\s*$/'],
            ['step_measure', 'deposite_measure'], 'in', 'range' => self::getMeasures()],
            ['step_measure', 'deposite_measure'], 'default', 'value' => MEASURE_PERCENT],
            ['status', 'in', 'range' => self::getStatuses()],
            ['status', 'default', 'value' => self::STATUS_IN_PROGRESS],
            ['reason', 'in', 'range' => self::getReasons()],
            ['status', 'default', 'value' => self::REASON_NO_MATTER],
            [['description', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'torg_id'          => Yii::t('app', 'Torg'),
            'title'            => Yii::t('app', 'Title'),
            'description'      => Yii::t('app', 'Description'),
            'start_price'      => Yii::t('app', 'Start price'),
            'step'             => Yii::t('app', 'Step'),
            'step_measure'     => Yii::t('app', 'Step measure'),
            'deposite'         => Yii::t('app', 'Deposite'),
            'deposite_measure' => Yii::t('app', 'Deposite measure'),
            'status'           => Yii::t('app', 'Status'),
            'reason'           => Yii::t('app', 'Reason'),
            'created_at'       => Yii::t('app', 'Created'),
            'updated_at'       => Yii::t('app', 'Modified'),
        ];
    }

    /**
     * Get measure keys
     * @return array
     */
    public static function getMeasures() {
        return [
            self::MEASURE_PERCENT,
            self::MEASURE_SUM,
        ];
    }

    /**
     * Get status keys
     * @return array
     */
    public static function getStatuses() {
        return array_keys(Lookup::items('LotStatus'));
    }

    /**
     * Get reasons keys
     * @return array
     */
    public static function getReasons() {
        return array_keys(Lookup::items('LotReason'));
    }

    /**
     * Получить информацию о торге
     * @return yii\db\ActiveQuery
     */
    public function getTorg() {
        return $this->hasOne(Torg::className(), ['id' => 'torg_id']);
    }
}
