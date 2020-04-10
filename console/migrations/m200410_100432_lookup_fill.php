<?php

use yii\db\Migration;

/**
 * Class m200410_100432_lookup_fill
 * Профайл - пол
 */
class m200410_100432_lookup_fill extends Migration
{
    private const TABLE_LOOKUP   = '{{%lookup}}';
    private const TABLE_PROPERTY = '{{%property}}';
    
    const GENDER = 3;

    public function safeUp()
    {
        $this->insert(static::TABLE_PROPERTY, ['id' => self::GENDER, 'name' => 'Gender']);
        $this->insert(static::TABLE_LOOKUP, ['name' => 'Мужской', 'code' => 1, 'property_id' => self::GENDER, 'position' => 1]);
        $this->insert(static::TABLE_LOOKUP, ['name' => 'Женский', 'code' => 2, 'property_id' => self::GENDER, 'position' => 2]);
    }

    public function safeDown()
    {
        $this->delete(static::TABLE_LOOKUP, 'property_id=' . self::GENDER);
        $this->delete(static::TABLE_PROPERTY, self::GENDER);
    }
}
