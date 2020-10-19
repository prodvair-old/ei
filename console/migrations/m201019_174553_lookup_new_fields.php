<?php

use yii\db\Migration;

/**
 * Class m201019_174553_lookup_new_fields
 */
class m201019_174553_lookup_new_fields extends Migration
{
    const TABLE_LOOKUP   = '{{%lookup}}';

    const ORGANIZATION_ACTIVITY = 7;
    const PERSON_ACTIVITY = 4;

    public function safeUp()
    {
        // ORGANIZATION_ACTIVITY
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Юридическое лицо', 'code' => 17, 'property_id' => self::ORGANIZATION_ACTIVITY, 'position' => 17]);
        $this->insert(self::TABLE_LOOKUP, ['name' => 'СРО АУ', 'code' => 18, 'property_id' => self::ORGANIZATION_ACTIVITY, 'position' => 18]);
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Компания организатора торгов', 'code' => 19, 'property_id' => self::ORGANIZATION_ACTIVITY, 'position' => 19]);
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Центральный Банк РФ', 'code' => 20, 'property_id' => self::ORGANIZATION_ACTIVITY, 'position' => 20]);
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Агенств по страхованию вкладов', 'code' => 21, 'property_id' => self::ORGANIZATION_ACTIVITY, 'position' => 21]);
        $this->insert(self::TABLE_LOOKUP, ['name' => 'ФНС', 'code' => 22, 'property_id' => self::ORGANIZATION_ACTIVITY, 'position' => 22]);
        $this->insert(self::TABLE_LOOKUP, ['name' => 'ЕФРСБ', 'code' => 23, 'property_id' => self::ORGANIZATION_ACTIVITY, 'position' => 23]);
        $this->insert(self::TABLE_LOOKUP, ['name' => 'МФС', 'code' => 24, 'property_id' => self::ORGANIZATION_ACTIVITY, 'position' => 24]);

        // PERSON_ACTIVITY
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Физическое лицо', 'code' => 25, 'property_id' => self::PERSON_ACTIVITY, 'position' => 25]);
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Организатор торгов', 'code' => 26, 'property_id' => self::PERSON_ACTIVITY, 'position' => 26]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m201019_174553_lookup_new_fields cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201019_174553_lookup_new_fields cannot be reverted.\n";

        return false;
    }
    */
}
