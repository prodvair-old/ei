<?php

use yii\db\Migration;

/**
 * Class m200416_182713_lookup_fill
 */
class m200416_182713_lookup_fill extends Migration
{
    private const TABLE_LOOKUP   = '{{%lookup}}';
    private const TABLE_PROPERTY = '{{%property}}';
    
    const SUM_MEASURE = 9;
    const LOT_STATUS  = 10;
    const LOT_REASON  = 11;

    public function safeUp()
    {
        $this->insert(static::TABLE_PROPERTY, ['id' => self::SUM_MEASURE, 'name' => 'SumMeasure']);
        // в чем измеряется шаг торгов и депозит
        $this->insert(static::TABLE_LOOKUP, ['name' => 'Процент', 'code' => 1, 'property_id' => self::SUM_MEASURE, 'position' => 1]);
        $this->insert(static::TABLE_LOOKUP, ['name' => 'Сумма',   'code' => 2, 'property_id' => self::SUM_MEASURE, 'position' => 2]);

        $this->insert(static::TABLE_PROPERTY, ['id' => self::LOT_STATUS, 'name' => 'LotStatus']);
        // торги
        $this->insert(static::TABLE_LOOKUP, ['name' => 'Действуют',    'code' => 1, 'property_id' => self::LOT_STATUS, 'position' => 1]);
        $this->insert(static::TABLE_LOOKUP, ['name' => 'Объявлены',    'code' => 2, 'property_id' => self::LOT_STATUS, 'position' => 2]);
        $this->insert(static::TABLE_LOOKUP, ['name' => 'Прием заявок', 'code' => 3, 'property_id' => self::LOT_STATUS, 'position' => 3]);
        $this->insert(static::TABLE_LOOKUP, ['name' => 'Отменены',     'code' => 4, 'property_id' => self::LOT_STATUS, 'position' => 4]);
        $this->insert(static::TABLE_LOOKUP, ['name' => 'Завершены',    'code' => 5, 'property_id' => self::LOT_STATUS, 'position' => 5]);
        $this->insert(static::TABLE_LOOKUP, ['name' => 'Отложены',     'code' => 6, 'property_id' => self::LOT_STATUS, 'position' => 6]);
        $this->insert(static::TABLE_LOOKUP, ['name' => 'Архив',        'code' => 7, 'property_id' => self::LOT_STATUS, 'position' => 7]);

        // причины
        $this->insert(static::TABLE_PROPERTY, ['id' => self::LOT_REASON, 'name' => 'TorgAuction']);
        $this->insert(static::TABLE_LOOKUP, ['name' => 'Не важно',             'code' => 1, 'property_id' => self::LOT_REASON, 'position' => 1]);
        $this->insert(static::TABLE_LOOKUP, ['name' => 'Цена не повышалась',   'code' => 2, 'property_id' => self::LOT_REASON, 'position' => 2]);
        $this->insert(static::TABLE_LOOKUP, ['name' => 'Контракт не подписан', 'code' => 3, 'property_id' => self::LOT_REASON, 'position' => 3]);
        $this->insert(static::TABLE_LOOKUP, ['name' => 'Мало участников',      'code' => 4, 'property_id' => self::LOT_REASON, 'position' => 4]);
        $this->insert(static::TABLE_LOOKUP, ['name' => 'Подведение итогов',    'code' => 5, 'property_id' => self::LOT_REASON, 'position' => 5]);
    }

    public function safeDown()
    {
        $this->delete(static::TABLE_LOOKUP, 'property_id=' . self::SUM_MEASURE);
        $this->delete(static::TABLE_PROPERTY, self::SUM_MEASURE);
        $this->delete(static::TABLE_LOOKUP, 'property_id=' . self::LOT_STATUS);
        $this->delete(static::TABLE_PROPERTY, self::LOT_STATUS);
        $this->delete(static::TABLE_LOOKUP, 'property_id=' . self::LOT_REASON);
        $this->delete(static::TABLE_PROPERTY, self::LOT_REASON);
    }
}
