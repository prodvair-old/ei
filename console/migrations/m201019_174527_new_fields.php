<?php

use yii\db\Migration;

/**
 * Class m201019_174527_new_fields
 */
class m201019_174527_new_fields extends Migration
{

    const TABLE_PROFILE = '{{%profile}}';
    const TABLE_BANKRUPT = '{{%bankrupt}}';
    const TABLE_TORG = '{{%torg}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Profile
        $this->addColumn(self::TABLE_PROFILE, 'snils', $this->string(11));
        $this->addColumn(self::TABLE_PROFILE, 'orgn_ip', $this->string(15));
        $this->addColumn(self::TABLE_PROFILE, 'birthplace', $this->text());
        // Bankrupt
        $this->addColumn(self::TABLE_BANKRUPT, 'bankrupt_id', $this->bigInteger());
        // Torg
        $this->addColumn(self::TABLE_TORG, 'is_repeat', $this->smallInteger());
        $this->addColumn(self::TABLE_TORG, 'price_type', $this->smallInteger());
        $this->addColumn(self::TABLE_TORG, 'additional_text', $this->text());
        $this->addColumn(self::TABLE_TORG, 'rules', $this->text());

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Profile
        $this->dropColumn(self::TABLE_PROFILE, 'snils');
        $this->dropColumn(self::TABLE_PROFILE, 'orgn_ip');
        $this->dropColumn(self::TABLE_PROFILE, 'birthplace');
        // Bankrupt
        $this->dropColumn(self::TABLE_PROFILE, 'bankrupt_id');
        // Torg
        $this->dropColumn(self::TABLE_PROFILE, 'is_repeat');
        $this->dropColumn(self::TABLE_PROFILE, 'price_type');
        $this->dropColumn(self::TABLE_PROFILE, 'additional_text');
        $this->dropColumn(self::TABLE_PROFILE, 'rules');
    }
}
