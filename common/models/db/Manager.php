<?php

namespace common\models\db;

use Yii;
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
     * 
     * @param string  $search a part of item
     * @param integer $selected item
     * @return array of [id: integer, text: string]
     */
	public static function getItems($search = '', $selected = 0)
	{
        $query = self::find()
            ->select(['manager.id', 'inn', 'full_name' => "CONCAT_WS(' ', last_name, first_name, middle_name)"])
            ->innerJoin('{{%profile}}', 'manager.id=profile.parent_id AND model='. IntCode::MANAGER)
            ->where(['manager.agent' => self::AGENT_PERSON])
            ->andWhere(['not like', 'first_name', '-'])
            ->orderBy('full_name');
        if ($search)
            $query->andFilterWhere(['or',
                ['like', 'first_name', $search],
                ['like', 'middle_name', $search],
                ['like', 'last_name', $search],
                ['like', 'inn', $search]
            ]);
        else
            $query->limit(10);
        
        $managers = $query->asArray()->all();
        
        $a = [];
        $a[] = ['id' => 0, 'text' => Yii::t('app', 'Select')];
        foreach($managers as $manager)
            $a[] = [
                'id' => $manager['id'], 
                'text' => ($manager['full_name'] . ' ' . $manager['inn']),
                'selected' => ($manager['id'] == $selected),
            ];
        return $a;
	}

    /**
     * @return \yii\db\ActiveQuery|\yii\db\ActiveRecord
     */
    public function getPlaceRel()
    {
        return $this->hasOne(Place::className(), ['parent_id' => 'id'])
            ->andOnCondition(['place.model' => static::INT_CODE]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfileRel()
    {
        return $this->hasOne(Profile::className(), ['parent_id' => 'id'])
            ->andOnCondition(['profile.model' => static::INT_CODE]);
    }

    /**
     * @return \yii\db\ActiveQuery
     * @throws \yii\base\InvalidConfigException
     */
    public function getTorg()
    {
        return $this->hasMany(Torg::className(), ['id' => 'torg_id'])
            ->viaTable(TorgDebtor::tableName(), ['manager_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArbitrator() {
        return $this->hasOne(Arbitrator::className(), ['manager_id' => 'id']);
    }
}

