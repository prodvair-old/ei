<?php

namespace common\models\db;


/**
 * Bankrupt model
 * Банкрот, персона или организация.
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
class Bankrupt extends BaseAgent
{
    // внутренний код модели используемый в составном ключе
    const INT_CODE = 4;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%bankrupt}}';
    }

    /**
     * @return \yii\db\ActiveQuery|\yii\db\ActiveRecord
     */
    public function getProfileRel()
    {
        return $this->hasOne(Profile::className(), ['parent_id' => 'id'])
            ->andOnCondition(['profile.model' => static::INT_CODE]);
    }

    /**
     * @return \yii\db\ActiveQuery|\yii\db\ActiveRecord
     */
    public function getOrganizationRel()
    {
        return $this->hasOne(Organization::className(), ['parent_id' => 'id'])
            ->andOnCondition(['organization.model' => static::INT_CODE]);
    }

    /**
     * @return \yii\db\ActiveQuery
     * @throws \yii\base\InvalidConfigException
     */
    public function getTorg()
    {
        return $this->hasMany(Torg::className(), ['id' => 'torg_id'])
            ->viaTable(TorgDebtor::tableName(), ['bankrupt_id' => 'id']);
    }
}
