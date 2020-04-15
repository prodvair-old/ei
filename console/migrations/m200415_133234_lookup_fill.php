<?php

use yii\db\Migration;

/**
 * Class m200415_133234_lookup_fill
 */
class m200415_133234_lookup_fill extends Migration
{
    private const TABLE_LOOKUP   = '{{%lookup}}';
    private const TABLE_PROPERTY = '{{%property}}';
    
    const TORG_PROPERTY = 7;
    const TORG_AUCTION  = 8;

    public function safeUp()
    {
        $this->insert(static::TABLE_PROPERTY, ['id' => self::TORG_PROPERTY, 'name' => 'TorgProperty']);
        $this->insert(static::TABLE_LOOKUP, ['name' => 'Банкротное', 'code' => 1, 'property_id' => self::TORG_PROPERTY, 'position' => 1]);
        $this->insert(static::TABLE_LOOKUP, ['name' => 'Залоговое', 'code' => 2, 'property_id' => self::TORG_PROPERTY, 'position' => 2]);

        $this->insert(static::TABLE_PROPERTY, ['id' => self::TORG_AUCTION, 'name' => 'TorgAuction']);
        $this->insert(static::TABLE_LOOKUP, ['name' => 'Открытые', 'code' => 1, 'property_id' => self::TORG_AUCTION, 'position' => 1]);
        $this->insert(static::TABLE_LOOKUP, ['name' => 'Публичные', 'code' => 2, 'property_id' => self::TORG_AUCTION, 'position' => 2]);
    }

    public function safeDown()
    {
        $this->delete(static::TABLE_LOOKUP, 'property_id=' . self::TORG_PROPERTY);
        $this->delete(static::TABLE_PROPERTY, self::TORG_PROPERTY);
        $this->delete(static::TABLE_LOOKUP, 'property_id=' . self::TORG_AUCTION);
        $this->delete(static::TABLE_PROPERTY, self::TORG_AUCTION);
    }
}
