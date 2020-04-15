<?php
namespace common\models\db;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use sergmoro1\lookup\models\Lookup;

/**
 * Bankrupt model
 * Описание банкрота.
 *
 * @property integer $id
 * @property integer $who
 * @property integer $type
 * @property integer $created_at
 * @property integer $updated_at
 */
class Bankrupt extends BaseAgent
{
    // внутренний код модели используемый в составном ключе
    const INT_CODE = 6;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%bankrupt}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            ['type', 'in', 'range' => array_keys(Lookup::items('AgentType'))],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'type'        => Yii::t('app', 'Type'),
        ]);
    }
}
