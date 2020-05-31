<?php

namespace common\models\db;

/**
 * Manager model
 * Управляющий, ответственный за ведение дел по банкротному имуществу.
 *
 * @var integer $id
 * @var integer $agent
 * @var integer $created_at
 * @var integer $updated_at
 *
 * @property Place $place
 * @property Profile $profile
 * @property Organization $organization
 */
class Manager extends BaseAgent
{
    // внутренний код модели используемый в составном ключе
    const INT_CODE = 3;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%manager}}';
    }

//    /**
//     * Получить СРО
//     * @return ActiveQuery
//     */
//    public function getSro()
//    {
//        return $this->hasOne(Organization::className(), ['model' => Organization::TYPE_SRO, 'parent_id' => 'sro_id'])
//            ->viaTable(ManagerSro::tableName(), ['manager_id' => 'id']);
//    }

    /**
     * Получить СРО
     * @return \yii\db\ActiveQuery
     * @throws \yii\base\InvalidConfigException
     */
    public function getSro()
    {
        return $this->hasOne(Organization::className(), ['parent_id' => 'sro_id'])
            ->andOnCondition(['=', Organization::tableName() . '.model', Organization::TYPE_SRO])
            ->viaTable(ManagerSro::tableName(), ['manager_id' => 'id']);
    }
}

