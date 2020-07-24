<?php

use yii\db\Migration;

/**
 * Class m200722_120830_lookup_invoice
 */
class m200722_120830_lookup_invoice extends Migration
{
    const TABLE_LOOKUP   = '{{%lookup}}';
    const TABLE_PROPERTY = '{{%property}}';
    
    const PRODUCT_TYPE = 14;

    public function safeUp()
    {
        $this->insert(self::TABLE_PROPERTY, ['id' => self::PRODUCT_TYPE, 'name' => 'ProductType']);
        // product kinds
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Подписка', 'code' => 1, 'property_id' => self::PRODUCT_TYPE, 'position' => 1]);
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Отчет',    'code' => 2, 'property_id' => self::PRODUCT_TYPE, 'position' => 2]);
    }

    public function safeDown()
    {
        $this->delete(self::TABLE_LOOKUP, 'property_id=' . self::PRODUCT_TYPE);
        $this->delete(self::TABLE_PROPERTY, 'id=' . self::PRODUCT_TYPE);
    }
}
