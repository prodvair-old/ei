<?php
namespace common\models\db;

use Yii;
use yii\helpers\ArrayHelper;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

use common\models\db\Torg;
use common\traits\ShortPart;
use sergmoro1\uploader\behaviors\HaveFileBehavior;
use sergmoro1\lookup\models\Lookup;

/**
 * Lot model
 * Lot is a property that belongs to bankrupt debtor or pledge.
 *
 * @var integer $id
 * @var integer $torg_id
 * @var integer $ordinal_number
 * @var string  $title
 * @var text    $description
 * @var float   $start_price
 * @var float   $step
 * @var integer $step_measure
 * @var float   $deposit
 * @var integer $deposit_measure
 * @var integer $status
 * @var integer $status_changed_at
 * @var integer $reason
 * @var string  $url
 * @var text    $info
 * @var integer $created_at
 * @var integer $updated_at
 * 
 * @property Place $place
 * @property Torg $torg
 * @property WishList[] $observers
 * @property LotTrace[] $traces
 * @property LotPrice[] $prices
 * @property Category[] $categories
 * @property Document[] $documents
 * @property sergmoro1\uploader\models\OneFile[] $files
 */
class Lot extends ActiveRecord
{
    use ShortPart;
    
    // scenarious
    const SCENARIO_MIGRATION = 'lot_migration';

    // internal model code used in the composite key
    const INT_CODE = 6;

    // events
    const EVENT_NEW_PICTURE     = 'new_picture';     // A new photo was added to the Lot
    const EVENT_NEW_REPORT      = 'new_report';      // A new report was added to the Lot 
    const EVENT_PRICE_REDUCTION = 'price_reduction'; // Price reduction for the Lot
    const EVENT_VIEWED          = 'viewed';          // The Lot was viewed

    // values of enumerated variables
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

    const SHORT_TITLE_LENGTH = 20;

    public $new_categories = [];
    private $_old_categories;
    private $_old_images;

    public static function getIntCode() { return self::INT_CODE; }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%lot}}';
    }

    public function init()
    {
        parent::init();

        $this->on(self::EVENT_NEW_PICTURE,     function($event) { $this->notifyObservers($event); });
        $this->on(self::EVENT_NEW_REPORT,      function($event) { $this->notifyObservers($event); });
        $this->on(self::EVENT_PRICE_REDUCTION, function($event) { $this->notifyObservers($event); });
        $this->on(self::EVENT_VIEWED,          function($event) { $this->saveTrace($event); });
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
                    'main'      => ['width' => 400,  'height' => 300, 'catalog' => ''],
                    'thumb'     => ['width' => 120,  'height' => 90,  'catalog' => 'thumb'],
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
            [['torg_id', 'title', 'start_price', 'deposit'], 'required'],
            ['ordinal_number', 'integer'],
            ['ordinal_number', 'default', 'value' => function ($model, $attribute) { 
                return Torg::find()->where(['id' => $model->torg_id])->count() + 1; 
            }],
            ['start_price', 'number', 'numberPattern' => '/^\s*[-+]?[0-9]*\.?\d{0,2}\s*$/'],
            [['step', 'deposit'], 'number', 'numberPattern' => '/^\s*[-+]?[0-9]*\.?\d{0,4}\s*$/'],
            [['step', 'deposit'], 'default', 'value' => 0],
            [['step_measure', 'deposit_measure'], 'in', 'range' => self::getMeasures()],
            [['step_measure', 'deposit_measure'], 'default', 'value' => self::MEASURE_PERCENT],
            ['status', 'in', 'range' => self::getStatuses()],
            ['status', 'default', 'value' => self::STATUS_IN_PROGRESS],
            ['reason', 'in', 'range' => self::getReasons()],
            ['reason', 'default', 'value' => self::REASON_NO_MATTER],
            ['url', 'url', 'defaultScheme' => 'http', 'except' => self::SCENARIO_MIGRATION],
            [['description', 'info', 'new_categories', 'status_changed_at', 'created_at', 'updated_at'], 'safe'],
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
            'url'              => Yii::t('app', 'Source'),
            'new_categories'   => Yii::t('app', 'Categories'),
            'created_at'       => Yii::t('app', 'Created'),
            'updated_at'       => Yii::t('app', 'Modified'),
        ];
    }

    /**
     * Get measure keys.
     * 
     * @return array
     */
    public static function getMeasures() {
        return [
            self::MEASURE_PERCENT,
            self::MEASURE_SUM,
        ];
    }

    /**
     * Get status keys.
     * 
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
     * Get reasons keys.
     * 
     * @return array
     */
    public static function getReasons() {
        return [
            self::REASON_NO_MATTER, 
            self::REASON_APPLICATION,
            self::REASON_PRICE,
            self::REASON_CONTRACT,
            self::REASON_PARTICIPANT,
            self::REASON_SUMMARIZING,
        ];
    }

    /**
     * Get short title.
     * 
     * @return string
     */
    public function getShortTitle() {
        return $this->getShortPart(self::SHORT_TITLE_LENGTH, 'title');
    }

    /**
     * Get Place.
     * 
     * @return yii\db\ActiveQuery
     */
    public function getPlace()
    {
        return $this->hasOne(Place::className(), ['parent_id' => 'id'])->andOnCondition(['place.model' => self::INT_CODE]);
    }

    /**
     * Get Region by Place.
     * 
     * @return \yii\db\ActiveQuery
     */
    public function getRegion()
    {
        return $this->hasOne(Region::className(), ['id' => 'region_id'])
            ->via('place');
    }

    /**
     * Get Torg.
     * 
     * @return yii\db\ActiveQuery
     */
    public function getTorg()
    {
        return $this->hasOne(Torg::className(), ['id' => 'torg_id']);
    }

    /**
     * Checking whether the lot Is outdated.
     *
     * @return yii\db\ActiveQuery
     */
    public function archived()
    {
        return $this->status == self::STATUS_COMPLETED && $this->torg->end_at > time();
    }

    /**
     * Get observers.
     * 
     * @return yii\db\ActiveQuery
     */
    public function getObservers()
    {
        return $this->hasMany(WishList::className(), ['lot_id' => 'id']);
    }

    /**
     * Notify observers about an event.
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
     * Keep information about event in a text file.
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
     * Get traces.
     * 
     * @return yii\db\ActiveQuery
     */
    public function getTraces()
    {
        return $this->hasMany(LotTrace::className(), ['lot_id' => 'id']);
    }

    /**
     * Save the trace of the user who viewed the Lot.
     * 
     * @param array $data as in yii\base\Event
     */
    public function saveTrace($event)
    {
        $model = new LotTrace([
            'lot_id' => $this->id,
            'ip'     => Yii::$app->getRequest()->getUserIP(),
        ]);
        if (!$model->save())
            throw new yii\db\Exception(); 
    }

    /**
     * Get the history of price reduction for a lot.
     *
     * @return yii\db\ActiveQuery
     */
    public function getPrices()
    {
        return $this->hasMany(LotPrice::className(), ['lot_id' => 'id']);
    }

    /**
     * Get the categories that the Lot belongs to.
     *
     * @return yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(Category::className(), ['id' => 'category_id'])
            ->viaTable(LotCategory::tableName(), ['lot_id' => 'id']);
    }

    /**
     * Get Lot documents.
     *
     * @return yii\db\ActiveQuery
     */
    public function getDocuments()
    {
        return $this->hasMany(Document::className(), ['parent_id' => 'id'])
            ->andOnCondition(['=', Document::tableName() . '.model', self::INT_CODE]);
    }

    /**
     * {@inheritdoc}
     */
    public function afterFind()
    {
        parent::afterFind();
        $this->_old_categories = ArrayHelper::getColumn(LotCategory::find()->where(['lot_id' => $this->id])->all(), 'category_id');
        $this->new_categories = $this->_old_categories;
        foreach($this->files as $file) {
            if ($this->isImage($file->type))
                $this->_old_images[] = $file->name;
        }
    }

    /**
     * Checking for new photos of the Lot.
     *
     * @return boolean
     */
    public function areThereAnyNewImages()
    {
        foreach($this->files as $file) {
            if ($this->isImage($file->type) && !in_array($file->name, $this->_old_images))
                return true;
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        LotCategory::updateOneToMany($this->id, $this->_old_categories, $this->new_categories);
        if ($this->areThereAnyNewImages())
            $this->trigger(self::EVENT_NEW_PICTURE);
    }

    /**
     * {@inheritdoc}
     */
    public function afterDelete()
    {
        parent::afterDelete();

        LotCategory::updateOneToMany($this->id, $this->_old_categories, []);
        foreach($this->observers as $observer)
            $observer->delete();
        foreach($this->views as $view)
            $view->delete();
        foreach($this->prices as $price)
            $price->delete();
        foreach($this->documents as $document)
            $document->delete();
    }
    
    /**
     * Decode JSON info.
     *
     * @return object
     */
    public function getInfo()
    {
        return json_decode($this->info, true);
    }
}
