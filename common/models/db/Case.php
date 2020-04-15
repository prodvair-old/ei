<?php
namespace common\models\db;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * Case model
 * Дела по Торгу.
 *
 * @property integer $id
 * @property string  $reg_number
 * @property integer $year
 * @property string  $judje
 * @property integer $created_at
 * @property integer $updated_at
 */
class Case extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%case}}';
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
            [['reg_number', 'year'], 'required'],
            [['reg_number', 'judge'], 'string', 'max' => 255],
            ['year', 'integer', 'min' => '1970'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'reg_number' => Yii::t('app', 'Reg number'),
            'year'       => Yii::t('app', 'Year'),
            'judge'      => Yii::t('app', 'Judge'),
            'created_at' => Yii::t('app', 'Created'),
            'updated_at' => Yii::t('app', 'Modified'),
        ];
    }
}
