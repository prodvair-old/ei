<?php

use yii\db\Migration;

/**
 * Class m200410_100432_lookup_profile
 * Профайл - пол
 */
class m200410_100432_lookup_profile extends Migration
{
    const TABLE_LOOKUP   = '{{%lookup}}';
    const TABLE_PROPERTY = '{{%property}}';
    
    const GENDER = 3;
    const PERSON_ACTIVITY = 4;

    public function safeUp()
    {
        $this->insert(self::TABLE_PROPERTY, ['id' => self::GENDER, 'name' => 'Gender']);
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Мужской', 'code' => 1, 'property_id' => self::GENDER, 'position' => 1]);
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Женский', 'code' => 2, 'property_id' => self::GENDER, 'position' => 2]);

        $this->insert(self::TABLE_PROPERTY, ['id' => self::PERSON_ACTIVITY, 'name' => 'PersonActivity']);
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Предприниматель', 'code' => 13, 'property_id' => self::PERSON_ACTIVITY, 'position' => 1]);
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Фермер',          'code' => 14, 'property_id' => self::PERSON_ACTIVITY, 'position' => 2]);
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Обычный человек', 'code' => 15, 'property_id' => self::PERSON_ACTIVITY, 'position' => 3]);
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Другое',          'code' => 16, 'property_id' => self::PERSON_ACTIVITY, 'position' => 4]);
    }

    public function safeDown()
    {
        $this->delete(self::TABLE_LOOKUP, 'property_id=' . self::GENDER);
        $this->delete(self::TABLE_PROPERTY, 'id=' . self::GENDER);
        $this->delete(self::TABLE_LOOKUP, 'property_id=' . self::PERSON_ACTIVITY);
        $this->delete(self::TABLE_PROPERTY, 'id=' . self::PERSON_ACTIVITY);
    }
}
