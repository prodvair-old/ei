<?php
namespace common\models\db;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;
use sergmoro1\uploader\behaviors\HaveFileBehavior;
use sergmoro1\lookup\models\Lookup;

/**
 * Report for Lot.
 *
 * @var integer $id
 * @var integer $user_id
 * @var integer $lot_id
 * @var string  $title
 * @var text    $content
 * @var float   $cost
 * @var integer $attraction
 * @var integer $risk
 * @var integer $status
 * @var integer $created_at
 * @var integer $updated_at
 *
 * @property Lot $lot
 * @property sergmoro1\uploader\models\OneFile[] $files
 */
class Report extends ActiveRecord
{
    // internal model code used in the composite key
    const INT_CODE = 21;

    // values of numerated variables
    const STATUS_ACTIVE   = 1;
    const STATUS_ARCHIVED = 2;

    const SHORT_TITLE_LENGTH = 20;

    private $_isPaid = null;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%report}}';
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
				'file_path' => '/report/',
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
            [['user_id', 'lot_id', 'title', 'content', 'cost'], 'required'],
            ['title', 'string', 'max' => 255],
            ['cost', 'number', 'numberPattern' => '/^\s*[-+]?[0-9]*\.?\d{0,2}\s*$/'],
            ['status', 'in', 'range' => self::getStatuses()],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            [['attraction', 'risk'], 'integer', 'min' => 1, 'max' => 10],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id'    => Yii::t('app', 'Expert'),
            'lot_id'     => Yii::t('app', 'Lot'),
            'title'      => Yii::t('app', 'Title'),
            'content'    => Yii::t('app', 'Content'),
            'cost'       => Yii::t('app', 'Cost'),
            'status'     => Yii::t('app', 'Status'),
            'attraction' => Yii::t('app', 'Attraction'),
            'risk'       => Yii::t('app', 'Risk'),
            'created_at' => Yii::t('app', 'Created'),
            'updated_at' => Yii::t('app', 'Modified'),
        ];
    }

    /**
     * Get status keys
     * @return array
     */
    public static function getStatuses() {
        return [
            self::STATUS_ACTIVE,
            self::STATUS_ARCHIVED,
        ];
    }

    /**
     * Get short title
     * @return string
     */
    public function getShortTitle() {
        return $this->getShortPart(self::SHORT_TITLE_LENGTH, 'title');
    }


    /**
     * Get information about the lot that the report was created for.
     * @return yii\db\ActiveQuery
     */
    public function getLot()
    {
        return $this->hasOne(Lot::className(), ['id' => 'lot_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return bool|Purchase|null
     */
    public function isPaid() {

        if(Yii::$app->user->isGuest) {
            return false;
        }

        if($this->_isPaid !== null) {
            return $this->_isPaid;
        }

        $this->_isPaid = Purchase::findOne([
            'user_id' => Yii::$app->user->getId(),
            'report_id' => $this->id,
        ]);

        return $this->_isPaid;
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert,$changedAttributes)
    {
        parent::afterSave($insert,$changedAttributes);
        if ($insert) {
            $this->lot->trigger(Lot::EVENT_NEW_REPORT);
        }
    }
}
