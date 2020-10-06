<?php

use yii\db\Migration;

/**
 * Class m201005_140726_lookup_messages
 */
class m201005_140726_lookup_messages extends Migration
{
    const TABLE_LOOKUP   = '{{%lookup}}';
    const TABLE_PROPERTY = '{{%property}}';
    
    const MESSAGES_STATUS = 17;
    const MESSAGES_TYPE = 18;

    public function safeUp()
    {
        $this->insert(self::TABLE_PROPERTY, ['id' => self::MESSAGES_STATUS, 'name' => 'messagesStatus']);
        
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Добавлено', 'code' => 1, 'property_id' => self::MESSAGES_STATUS, 'position' => 1]);
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Готово', 'code' => 2, 'property_id' => self::MESSAGES_STATUS, 'position' => 2]);
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Ошибка', 'code' => 3, 'property_id' => self::MESSAGES_STATUS, 'position' => 3]);
        $this->insert(self::TABLE_LOOKUP, ['name' => 'В очереди', 'code' => 4, 'property_id' => self::MESSAGES_STATUS, 'position' => 4]);
    }

    public function safeDown()
    {
        $this->delete(self::TABLE_LOOKUP, 'property_id=' . self::MESSAGES_STATUS);
        $this->delete(self::TABLE_PROPERTY, 'id=' . self::MESSAGES_STATUS);
    }
}
