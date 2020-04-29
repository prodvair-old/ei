<?php
namespace common\models\db;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * Notification model
 * Уведомления, отмеченные пользователем.
 * 
 * @var integer $id
 * @var integer $user_id
 * @var boolean $new_picture
 * @var boolean $new_report
 * @var boolean $price_reduction
 * @var integer $created_at
 * @var integer $updated_at
 */
class Notification extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%notification}}';
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
            ['user_id', 'required'],
            [['new_picture', 'new_report', 'price_reduction'], 'boolean'],
            [['new_picture', 'new_report', 'price_reduction'], 'default', 'value' => false],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id'         => Yii::t('app', 'User'),
            'new_picture'     => Yii::t('app', 'New picture'),
            'new_report'      => Yii::t('app', 'New_report'),
            'price_reduction' => Yii::t('app', 'Price reduction'),
            'created_at'      => Yii::t('app', 'Created'),
            'updated_at'      => Yii::t('app', 'Modified'),
        ];
    }
}
