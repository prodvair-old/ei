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
 * @var integer $id
 * @var integer $torg_id
 * @var string  $title
 * @var text    $description
 * @var float   $start_price
 * @var float   $step
 * @var integer $step_measure
 * @var float   $deposit
 * @var integer $deposit_measure
 * @var integer $status
 * @var integer $reason
 * @var integer $created_at
 * @var integer $updated_at
 * 
 * @property Torg $torg
 * @property WishList[] $observers
 * @property LotPrice[] $prices
 * @property Category[] $categories
 * @property sergmoro1\uploader\models\OneFile[] $files
 */
class Lot extends ActiveRecord
{
    // внутренний код модели используемый в составном ключе
    const INT_CODE = 6;

    // события
    const EVENT_NEW_PICTURE     = 'new_picture';     // Добавлено новое фото к лоту
    const EVENT_NEW_REPORT      = 'new_report';      // Добавлен новый отчет к лоту
    const EVENT_PRICE_REDUCTION = 'price_reduction'; // Снижена цена на лот

    // значения перечислимых переменых
    const MEASURE_PERCENT    = 1;
    const MEASURE_SUM        = 2;

    const STATUS_IN_PROGRESS = 1;
    const STATUS_ANNOUNCED   = 2;
    const STATUS_SUSPENDED   = 3;
    const STATUS_CANCELLED   = 4;
    const STATUS_COMPLETED   = 5;
    const STATUS_ARCHIVED    = 6;
    const STATUS_NOT_DEFINED = 10;

    const REASON_NO_MATTER   = 1; 
    const REASON_APPLICATION = 2;
    const REASON_PRICE       = 3;
    const REASON_CONTRACT    = 4;
    const REASON_PARTICIPANT = 5;
    const REASON_SUMMARIZING = 6;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%torg}}';
    }

    public function init()
    {
        parent::init();

        $this->on(self::EVENT_NEW_PICTURE,     function($event) { $this->notifyObservers($event); });
        $this->on(self::EVENT_NEW_REPORT,      function($event) { $this->notifyObservers($event); });
        $this->on(self::EVENT_PRICE_REDUCTION, function($event) { $this->notifyObservers($event); });
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
            [['start_price', 'step', 'deposit'], 'number', 'numberFormat' => '/^\s*[-+]?[0-9]*\.?\d{0,2}\s*$/'],
            [['step_measure', 'deposit_measure'], 'in', 'range' => self::getMeasures()],
            [['step_measure', 'deposit_measure'], 'default', 'value' => MEASURE_PERCENT],
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
            'deposit'          => Yii::t('app', 'Deposit'),
            'deposit_measure'  => Yii::t('app', 'Deposit measure'),
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
        return [
            self::STATUS_IN_PROGRESS,
            self::STATUS_ANNOUNCED,
            self::STATUS_SUSPENDED,
            self::STATUS_CANCELLED,
            self::STATUS_COMPLETED,
            self::STATUS_ARCHIVED,
            self::STATUS_NOT_DEFINED,
        ];
    }

    /**
     * Get reasons keys
     * @return array
     */
    public static function getReasons() {
        return [
            self::REASON_NO_MATTER, 
            self::REASON_PRICE,
            self::REASON_CONTRACT,
            self::REASON_PARTICIPANT,
            self::REASON_SUMMARIZING,
        ];
    }

    /**
     * Получить информацию о торге
     * @return yii\db\ActiveQuery
     */
    public function getTorg()
    {
        return $this->hasOne(Torg::className(), ['id' => 'torg_id']);
    }

    /**
     * Получить список ID подписчиков
     * 
     * @return yii\db\ActiveQuery
     */
    public function getObservers()
    {
        return $this->hasMany(WishList::className(), ['lotId' => 'id']);
    }

    /**
     * Известить подписчиков о произошедшем событии
     * 
     * @param array $data as in yii\base\Event
     */
    public function notifyObservers($event)
    {
        foreach($this->observers as $observer) {
            if ($observer->user->needNotify($event->name))
                $this->keepNotification([
                    'user_id' => $observer->userId,
                    'lot_id'  => $this->id,
                    'event'   => $event->name,
                ]);
        }
    }

    /**
     * Сохранить информацию о событии в общем списке
     * 
     * @param \yii\base\Event $event
     * @param array $data
     */
    public function keepNotification($data)
    {
        $file = fopen(Yii::$app->queue->path . '/data.csv', 'a');
        fwrite($file, "{$data['user_id']},{$data['lot_id']},{$data['event']}\n");
        fclose($file);
    }

    /**
     * Получить историю снижения цены по Лоту
     * 
     * @return yii\db\ActiveQuery
     */
    public function getPrices()
    {
        return $this->hasMany(LotPrice::className(), ['lotId' => 'id']);
    }

    /**
     * Получить категории, к которым принадлежит Лот
     * 
     * @return yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(Category::className(), ['category_id' => 'id'])
            ->viaTable(LotCategory::tableName(), ['lot_id' => 'id']);
    }
}
