<?php
namespace common\models\db;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * Case model
 * Дело по Торгу.
 *
 * @var integer $id
 * @var string  $reg_number
 * @var integer $year
 * @var string  $judje
 * @var integer $created_at
 * @var integer $updated_at
 * 
 * @property sergmoro1\uploader\models\OneFile[] $files
 */
class Case extends ActiveRecord
{
    // внутренний код модели используемый в составном ключе
    const INT_CODE = 4;

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
			[
				'class' => HaveFileBehavior::className(),
				'file_path' => '/case/',
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
