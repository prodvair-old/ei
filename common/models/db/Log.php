<?php
namespace common\models\db;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * Parser Log model
 *
 * @var integer $id
 * @var integer $model
 * @var integer $parent_id
 * @var integer $status
 * @var text    $name
 * @var text    $message
 * @var json    $message_json
 * @var integer $created_at
 * @var integer $updated_at
 * 
 */
class Log extends ActiveRecord
{

    const STATUS_SUCCESS    = 1;
    const STATUS_WARNING    = 2;
    const STATUS_ERROR      = 3;
    const STATUS_IN_A_QUEUE = 4;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%log}}';
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
            'status'        => Yii::t('app', 'Статус лога (или Код модели)'),
            'name'          => Yii::t('app', 'Название лога'),
            'message'       => Yii::t('app', 'Описание лога'),
            'message_json'  => Yii::t('app', 'Данные лога в json'),
        ]);
    }
}
