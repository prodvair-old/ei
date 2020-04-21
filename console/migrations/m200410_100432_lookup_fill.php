<?php

use yii\db\Migration;

/**
 * Class m200410_100432_lookup_fill
 * Профайл - пол
 */
class m200410_100432_lookup_fill extends Migration
{
    const TABLE_LOOKUP   = '{{%lookup}}';
    const TABLE_PROPERTY = '{{%property}}';
    
    const GENDER = 3;

    public function safeUp()
    {
        $this->insert(self::TABLE_PROPERTY, ['id' => self::GENDER, 'name' => 'Gender']);
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Мужской', 'code' => 1, 'property_id' => self::GENDER, 'position' => 1]);
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Женский', 'code' => 2, 'property_id' => self::GENDER, 'position' => 2]);
    }

    public function safeDown()
    {
        $this->delete(self::TABLE_LOOKUP, 'property_id=' . self::GENDER);
        $this->delete(self::TABLE_PROPERTY, self::GENDER);
    }
}
