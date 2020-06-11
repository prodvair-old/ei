<?php

namespace common\models\db;

use Yii;

/**
 * This is the model class for table "manager_sro".
 *
 * @property int $manager_id Менеджер
 * @property int $sro_id Саморегулируемая организация
 */
class ManagerSro extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%manager_sro}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['manager_id', 'sro_id'], 'required'],
            [['manager_id', 'sro_id'], 'default', 'value' => null],
            [['manager_id', 'sro_id'], 'integer'],
            [['manager_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'manager_id' => 'Менеджер',
            'sro_id' => 'Саморегулируемая организация',
        ];
    }
}
