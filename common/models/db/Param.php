<?php
namespace common\models\db;

use Yii;
use yii\helpers\Json;
use yii\db\ActiveRecord;

/**
 * Param model
 * Параметры приложения.
 * 
 * @var string $ыid
 * @var text   $defs
 */
class Param extends ActiveRecord
{
    private static $defaults = [
        'statistic' => [
            'torg'   => ['color' => 'aqua', 'icon' => 'gavel', 'value' => 'n/a'],
            'lot'    => ['color' => 'red', 'icon' => 'money', 'value' => 'n/a'],
            'report' => ['color' => 'green', 'icon' => 'book', 'value' => 'n/a'],
            'user'   => ['color' => 'yellow', 'icon' => 'user', 'value' => 'n/a'],
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
     * {@inheritdoc}
     */
    public function getDefs($name)
    {
        $param = self::findOne(['sid' => $name]);
        return $param ? Json::decode($param->defs) : self::$defaults[$name];
    }
}
