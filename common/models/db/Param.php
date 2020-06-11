<?php
namespace common\models\db;

use Yii;
use yii\helpers\Json;
use yii\db\ActiveRecord;

/**
 * Param model
 * Application parameters are stored in a JSON array with the specified names.
 * Default values must be defined, in case,
 * if the {{%param}} table does not contain the corresponding name.
 * 
 * @var string $sid название массива
 * @var text   $defs JSON определение массива
 */
class Param extends ActiveRecord
{
    // default arrays definition
    private static $defaults = [
        'statistic' => [
            'torgs'     => ['color' => 'aqua', 'icon' => 'gavel', 'value' => 'n/a'],
            'lots'      => ['color' => 'red', 'icon' => 'money', 'value' => 'n/a'],
            'documents' => ['color' => 'green', 'icon' => 'file-word-o', 'value' => 'n/a'],
            'users'     => ['color' => 'yellow', 'icon' => 'users', 'value' => 'n/a'],
        ],
    ];
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%param}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sid', 'defs'], 'required'],
            [['sid'], 'string', 'max' => 255],
            [['defs'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'sid'  => Yii::t('app', 'Name'),
            'defs' => Yii::t('app', 'Definitions'),
        ];
    }

    /**
     * Get array by name.
     * @param string $name
     * @return array with structure and values as defaults.
     */
    public function getDefs($name)
    {
        $param = self::findOne(['sid' => $name]);
        return $param ? Json::decode($param->defs) : self::$defaults[$name];
    }
}
