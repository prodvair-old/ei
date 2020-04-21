<?php

use yii\db\Migration;

/**
 * Class m200414_192646_lookup_fill
 */
class m200414_192646_lookup_fill extends Migration
{
    const TABLE_LOOKUP   = '{{%lookup}}';
    const TABLE_PROPERTY = '{{%property}}';
    
    const OWNER_STATUS = 4;

    public function safeUp()
    {
        $this->insert(self::TABLE_PROPERTY, ['id' => self::OWNER_STATUS, 'name' => 'OwnerStatus']);
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Ожидание', 'code' => 1, 'property_id' => self::OWNER_STATUS, 'position' => 1]);
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Проверен', 'code' => 2, 'property_id' => self::OWNER_STATUS, 'position' => 2]);
    }

    public function safeDown()
    {
        $this->delete(self::TABLE_LOOKUP, 'property_id=' . self::OWNER_STATUS);
        $this->delete(self::TABLE_PROPERTY, self::OWNER_STATUS);
    }
}
