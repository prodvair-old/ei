<?php

namespace common\models\db;

use common\components\IntCode;

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

    /**
     * Getting manager items for dropdown list.
     * $search string a part of Manager full name
     * @return array of [id: integer, text: string]
     */
	public static function jsonItems($selected)
	{
        $managers = self::find()
            ->select(['manager.id', 'inn', 'full_name' => 'CONCAT_WS(" ", first_name, middle_name, last_name)'])
            ->innerJoin('{{%profile}}', 'manager.id=profile.parent_id AND model='. IntCode::MANAGER)
            ->where(['manager.agent' => self::AGENT_PERSON])
            ->orderBy('full_name')
            ->asArray()
            ->all();
        $a = [];
        foreach($managers as $manager)
            $a[] = [
                'id' => $manager['id'], 
                'text' => ($manager['full_name'] . ' ' . $manager['inn']),
                'selected' => in_array($manager['id'], $selected),
            ];
        return json_encode($a);
	}
}

