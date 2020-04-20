<?php
namespace console\migrations;

use yii\db\Migration;

/**
 * Class m180116_073828_lookup_fill
 * Пользователь - роль, статус
 */
class m180116_073828_lookup_fill extends Migration
{
    private const TABLE_LOOKUP   = '{{%lookup}}';
    private const TABLE_PROPERTY = '{{%property}}';
    
    const USER_ROLE = 1;
    const USER_STATUS = 2;

    public function safeUp()
    {
        $this->insert(static::TABLE_PROPERTY, ['id' => self::USER_ROLE, 'name' => 'UserRole']);
        $this->insert(static::TABLE_LOOKUP, ['name' => 'Администратор', 'code' => 1, 'property_id' => self::USER_ROLE, 'position' => 1]);
        $this->insert(static::TABLE_LOOKUP, ['name' => 'Менеджер',      'code' => 2, 'property_id' => self::USER_ROLE, 'position' => 2]);
        $this->insert(static::TABLE_LOOKUP, ['name' => 'Пользователь',  'code' => 3, 'property_id' => self::USER_ROLE, 'position' => 3]);

        $this->insert(static::TABLE_PROPERTY, ['id' =>  self::USER_STATUS, 'name' => 'UserStatus']);
        $this->insert(static::TABLE_LOOKUP, ['name' => 'Активен',   'code' => 1, 'property_id' => self::USER_STATUS, 'position' => 1]);
        $this->insert(static::TABLE_LOOKUP, ['name' => 'Архив',     'code' => 2, 'property_id' => self::USER_STATUS, 'position' => 2]);
    }

    public function safeDown()
    {
        $this->delete(static::TABLE_LOOKUP, 'property_id=' . self::USER_ROLE);
        $this->delete(static::TABLE_LOOKUP, 'property_id=' . self::USER_STATUS);
        $this->delete(static::TABLE_PROPERTY, self::USER_ROLE);
        $this->delete(static::TABLE_PROPERTY, self::USER_STATUS);
    }
}
