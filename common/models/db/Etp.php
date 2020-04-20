<?php
namespace common\models\db;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use common\interfaces\ProfileInterface;

/**
 * Etp model
 * Электронная торговая площадка.
 *
 * @property integer $id
 * @property string  $name
 * @property string  $title
 * @property string  $link
 * @property string  $inn
 * @property integer $created_at
 * @property integer $updated_at
 */
class Etp extends ActiveRecord implements ProfileInterface
{
    // внутренний код модели используемый в составном ключе
    const INT_CODE = 3;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%etp}}';
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
            [['name', 'link'], 'required'],
            ['inn', 'match', 'pattern' => '/\d{10}/'],
            [['name', 'title', 'link'], 'string', 'max' => 255],
            ['link', 'url', 'defaultScheme' => 'http'],
            [['created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name'       => Yii::t('app', 'Name'),
            'title'      => Yii::t('app', 'Full name'),
            'link'       => Yii::t('app', 'Link'),
            'inn'        => Yii::t('app', 'INN'),
            'created_at' => Yii::t('app', 'Created'),
            'updated_at' => Yii::t('app', 'Modified'),
        ];
    }

    /**
     * Get place manager connected with
     * @return yii\db\ActiveRecord
     */
    public function getPlace()
    {
        return Place::findOne(['model' => self::INT_CODE, 'parent_id' => $this->id]);
    }

    /**
     * @inheritdoc
     */
    public function getFullName()
    {
        return $this->title;
    };
}
