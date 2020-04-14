<?php

use yii\db\Migration;

/**
 * Class m200414_192646_lookup_fill
 */
class m200414_192646_lookup_fill extends Migration
{
    private const TABLE_LOOKUP   = '{{%lookup}}';
    private const TABLE_PROPERTY = '{{%property}}';
    
    const OWNER_STATUS = 4;

    public function safeUp()
    {
        $this->insert(static::TABLE_PROPERTY, ['id' => self::OWNER_STATUS, 'name' => 'OwnerStatus']);
        $this->insert(static::TABLE_LOOKUP, ['name' => 'Ожидание', 'code' => 1, 'property_id' => self::OWNER_STATUS, 'position' => 1]);
        $this->insert(static::TABLE_LOOKUP, ['name' => 'Проверен', 'code' => 2, 'property_id' => self::OWNER_STATUS, 'position' => 2]);
    }

    public function safeDown()
    {
        $this->delete(static::TABLE_LOOKUP, 'property_id=' . self::OWNER_STATUS);
        $this->delete(static::TABLE_PROPERTY, self::OWNER_STATUS);
    }
}
