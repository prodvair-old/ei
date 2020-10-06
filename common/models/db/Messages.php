<?php
namespace common\models\db;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * Messages model
 *
 * @var integer $id
 * @var integer $model
 * @var integer $parent_id
 * @var integer $msg_id
 * @var text    $msg_guid
 * @var integer $msg_old_id
 * @var integer $type
 * @var integer $status
 * @var text    $message
 * @var integer $created_at
 * @var integer $updated_at
 * 
 */
class Messages extends ActiveRecord
{
    // internal model code used in the composite key
    const INT_CODE = 13;

    // Status
    const STATUS_ADDED      = 1;
    const STATUS_IN_A_QUEUE = 2;
    const STATUS_SUCCESS    = 2;
    const STATUS_ERROR      = 4;

    // Type

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%messages}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['msg_id', 'msg_guid', 'type', 'message'], 'required'],
            [['msg_id', 'msg_guid'], 'unique'],
            [['model', 'parent_id', 'msg_old_id'], 'integer'],
            ['status', 'in', 'range' => self::getStatuses()],
            ['status', 'default', 'value' => self::STATUS_ADDED],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * Get status keys.
     * 
     * @return array
     */
    public static function getStatuses() {
        return [
            self::STATUS_ADDED,
            self::STATUS_IN_A_QUEUE,
            self::STATUS_SUCCESS,
            self::STATUS_ERROR,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'updatedAtAttribute' => false,
            ],
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'model'         => Yii::t('app', 'Код модели'),
            'parent_id'     => Yii::t('app', 'ID родительской модели'),
            'msg_id'        => Yii::t('app', 'Номер сообщения'),
            'msg_guid'      => Yii::t('app', 'Код сообщения'),
            'type'          => Yii::t('app', 'Тип сообщения'),
            'status'        => Yii::t('app', 'Стату сообщения'),
            'message'       => Yii::t('app', 'Сообщение'),
        ]);
    }
}
