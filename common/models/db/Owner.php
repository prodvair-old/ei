<?php

namespace common\models\db;

/**
 * Owner model
 * Владельцы лотов
 *
 * @var integer $id
 * @var string $description
 * @var string $link
 * @var string $logo
 * @var string $bg
 * @var string $color_btn
 * @var string $color_1
 * @var string $color_2
 * @var string $color_3
 * @var string $color_4
 * @var string $color_5
 * @var string $color_6
 * @var integer $created_at
 * @var integer $updated_at
 * 
 * @property Organization $organization
 */
class Owner extends BaseAgent
{
    // внутренний код модели используемый в составном ключе
    const INT_CODE = 8;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%owner}}';
    }

    /**
     * Получить Банк
     * @return ActiveQuery
     */
    public function getBank() {
        return $this->hasOne(Organization::className(), ['model' => Organization::TYPE_BANK, 'parent_id' => 'organizer_id']);
    }
    /**
     * Получить Владельца
     * @return ActiveQuery
     */
    public function getOrganization() {
        return $this->hasOne(Organization::className(), ['model' => Organization::TYPE_OWNER, 'parent_id' => 'organizer_id']);
    }
}
