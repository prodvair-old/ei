<?php
namespace common\models\db;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use common\interfaces\ProfileInterface;

/**
 * Organization model
 * Данные организации.
 *
 * @property integer $id
 * @property integer $model
 * @property integer $parent_id
 * @property string  $title
 * @property string  $inn
 * @property string  $ogrn
 * @property string  $reg_number
 * @property integer $created_at
 * @property integer $updated_at
 */
class Organization extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%organization}}';
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
            [['model', 'parent_id', 'title', 'inn'], 'required'],
            [['model', 'parent_id'], 'integer'],
            ['inn', 'match', 'pattern' => '/\d{10}/'],
            ['ogrn', 'match', 'pattern' => '/\d{13}/'],
            ['reg_number', 'string', 'max' => 255],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'title'       => Yii::t('app', 'Title'),
            'inn'         => Yii::t('app', 'INN'),
            'ogrn'        => Yii::t('app', 'OGRN'),
            'reg_number'  => Yii::t('app', 'Reg number'),
            'created_at'  => Yii::t('app', 'Created'),
            'updated_at'  => Yii::t('app', 'Modified'),
        ];
    }
}
