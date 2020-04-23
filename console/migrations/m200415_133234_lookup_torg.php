<?php

use yii\db\Migration;

/**
 * Class m200415_133234_lookup_torg
 */
class m200415_133234_lookup_torg extends Migration
{
    const TABLE_LOOKUP   = '{{%lookup}}';
    const TABLE_PROPERTY = '{{%property}}';
    
    const TORG_PROPERTY = 8;
    const TORG_OFFER    = 9;

    public function safeUp()
    {
        $this->insert(self::TABLE_PROPERTY, ['id' => self::TORG_PROPERTY, 'name' => 'TorgProperty']);
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Банкротное',    'code' => 1, 'property_id' => self::TORG_PROPERTY, 'position' => 1]);
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Залоговое',     'code' => 2, 'property_id' => self::TORG_PROPERTY, 'position' => 2]);
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Арестованное',  'code' => 3, 'property_id' => self::TORG_PROPERTY, 'position' => 3]);
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Муниципальное', 'code' => 4, 'property_id' => self::TORG_PROPERTY, 'position' => 4]);

        $this->insert(self::TABLE_PROPERTY, ['id' => self::TORG_OFFER, 'name' => 'TorgAuction']);
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Публичное',        'code' => 1, 'property_id' => self::TORG_OFFER, 'position' => 1]);
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Аукцион',          'code' => 2, 'property_id' => self::TORG_OFFER, 'position' => 2]);
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Аукцион открытый', 'code' => 3, 'property_id' => self::TORG_OFFER, 'position' => 3]);
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Конкурс',          'code' => 4, 'property_id' => self::TORG_OFFER, 'position' => 4]);
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Конкурс открытый', 'code' => 5, 'property_id' => self::TORG_OFFER, 'position' => 5]);
    }

    public function safeDown()
    {
        $this->delete(self::TABLE_LOOKUP, 'property_id=' . self::TORG_PROPERTY);
        $this->delete(self::TABLE_PROPERTY, 'id=' . self::TORG_PROPERTY);
        $this->delete(self::TABLE_LOOKUP, 'property_id=' . self::TORG_OFFER);
        $this->delete(self::TABLE_PROPERTY, 'id=' . self::TORG_OFFER);
    }
}
