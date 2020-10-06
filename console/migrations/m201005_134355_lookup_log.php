<?php

use yii\db\Migration;

/**
 * Class m201005_134355_lookup_log
 */
class m201005_134355_lookup_log extends Migration
{
    const TABLE_LOOKUP   = '{{%lookup}}';
    const TABLE_PROPERTY = '{{%property}}';
    
    const LOG_STATUS_TYPE = 15;

    public function safeUp()
    {
        $this->insert(self::TABLE_PROPERTY, ['id' => self::LOG_STATUS_TYPE, 'name' => 'logStatusType']);
        
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Успешно', 'code' => 1, 'property_id' => self::LOG_STATUS_TYPE, 'position' => 1]);
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Внимание', 'code' => 2, 'property_id' => self::LOG_STATUS_TYPE, 'position' => 2]);
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Ошибка', 'code' => 3, 'property_id' => self::LOG_STATUS_TYPE, 'position' => 3]);
        $this->insert(self::TABLE_LOOKUP, ['name' => 'В очереди', 'code' => 4, 'property_id' => self::LOG_STATUS_TYPE, 'position' => 4]);
    }

    public function safeDown()
    {
        $this->delete(self::TABLE_LOOKUP, 'property_id=' . self::LOG_STATUS_TYPE);
        $this->delete(self::TABLE_PROPERTY, 'id=' . self::LOG_STATUS_TYPE);
    }
}
