<?php

use yii\db\Migration;

/**
 * Class m200415_131046_lookup_organization
 */
class m200415_131046_lookup_organization extends Migration
{
    const TABLE_LOOKUP   = '{{%lookup}}';
    const TABLE_PROPERTY = '{{%property}}';
    
    const ORGANIZATION_STATUS    = 4;
    const ORGANIZATION_OWNERSHIP = 5;
    const ORGANIZATION_TYPE      = 6;

    public function safeUp()
    {
        $this->insert(self::TABLE_PROPERTY, ['id' => self::ORGANIZATION__STATUS, 'name' => 'OrganizationStatus']);
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Ожидание',  'code' => 1, 'property_id' => self::ORGANIZATION_STATUS, 'position' => 1]);
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Проверено', 'code' => 2, 'property_id' => self::ORGANIZATION_STATUS, 'position' => 2]);

        $this->insert(self::TABLE_PROPERTY, ['id' => self::ORGANIZATION_TYPE, 'name' => 'OrganizationType']);
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Не важно', 'code' => 1, 'property_id' => self::ORGANIZATION_TYPE, 'position' => 1]);
        $this->insert(self::TABLE_LOOKUP, ['name' => 'СРО',      'code' => 2, 'property_id' => self::ORGANIZATION_TYPE, 'position' => 2]);
        $this->insert(self::TABLE_LOOKUP, ['name' => 'ЕТП',      'code' => 3, 'property_id' => self::ORGANIZATION_TYPE, 'position' => 3]);
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Владелец', 'code' => 4, 'property_id' => self::ORGANIZATION_TYPE, 'position' => 4]);
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Банкрот',  'code' => 5, 'property_id' => self::ORGANIZATION_TYPE, 'position' => 5]);
        
        $this->insert(self::TABLE_PROPERTY, ['id' => self::ORGANIZATION_OWNERSHIP, 'name' => 'OrganizationOwnership']);
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Обычная организация',         'code' => 11, 'property_id' => self::ORGANIZATION_OWNERSHIP, 'position' => 1]);
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Предприниматель',             'code' => 13, 'property_id' => self::ORGANIZATION_OWNERSHIP, 'position' => 2]);
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Ликвидированная организация', 'code' => 6,  'property_id' => self::ORGANIZATION_OWNERSHIP, 'position' => 3]);
    }

    public function safeDown()
    {
        $this->delete(self::TABLE_LOOKUP, 'property_id=' . self::ORGANIZATION_STATUS);
        $this->delete(self::TABLE_PROPERTY, 'id=' . self::ORGANIZATION_STATUS);
        $this->delete(self::TABLE_LOOKUP, 'property_id=' . self::ORGANIZATION_TYPE);
        $this->delete(self::TABLE_PROPERTY, 'id=' . self::ORGANIZATION_TYPE);
        $this->delete(self::TABLE_LOOKUP, 'property_id=' . self::ORGANIZATION_OWNERSHIP);
        $this->delete(self::TABLE_PROPERTY, 'id=' . self::ORGANIZATION_OWNERSHIP);
    }
}
