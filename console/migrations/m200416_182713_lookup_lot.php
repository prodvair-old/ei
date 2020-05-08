<?php

use yii\db\Migration;

/**
 * Class m200416_182713_lookup_lot
 */
class m200416_182713_lookup_lot extends Migration
{
    const TABLE_LOOKUP   = '{{%lookup}}';
    const TABLE_PROPERTY = '{{%property}}';
    
    const SUM_MEASURE = 10;
    const LOT_STATUS  = 11;
    const LOT_REASON  = 12;

    public function safeUp()
    {
        $this->insert(self::TABLE_PROPERTY, ['id' => self::SUM_MEASURE, 'name' => 'SumMeasure']);
        // в чем измеряется шаг торгов и депозит
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Процент', 'code' => 1, 'property_id' => self::SUM_MEASURE, 'position' => 1]);
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Сумма',   'code' => 2, 'property_id' => self::SUM_MEASURE, 'position' => 2]);

        $this->insert(self::TABLE_PROPERTY, ['id' => self::LOT_STATUS, 'name' => 'LotStatus']);
        // торги
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Действуют',      'code' => 1, 'property_id' => self::LOT_STATUS, 'position' => 1]);
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Объявлены',      'code' => 2, 'property_id' => self::LOT_STATUS, 'position' => 2]);
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Приостановлены', 'code' => 3, 'property_id' => self::LOT_STATUS, 'position' => 3]);
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Отменены',       'code' => 4, 'property_id' => self::LOT_STATUS, 'position' => 4]);
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Завершены',      'code' => 5, 'property_id' => self::LOT_STATUS, 'position' => 5]);
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Архив',          'code' => 6, 'property_id' => self::LOT_STATUS, 'position' => 6]);

        // причины
        $this->insert(self::TABLE_PROPERTY, ['id' => self::LOT_REASON, 'name' => 'LotReason']);
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Не важно',             'code' => 1, 'property_id' => self::LOT_REASON, 'position' => 1]);
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Прием заявок',         'code' => 2, 'property_id' => self::LOT_REASON, 'position' => 2]);
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Цена не повышалась',   'code' => 3, 'property_id' => self::LOT_REASON, 'position' => 3]);
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Контракт',             'code' => 4, 'property_id' => self::LOT_REASON, 'position' => 4]);
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Мало участников',      'code' => 5, 'property_id' => self::LOT_REASON, 'position' => 5]);
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Подведение итогов',    'code' => 6, 'property_id' => self::LOT_REASON, 'position' => 6]);
    }

    public function safeDown()
    {
        $this->delete(self::TABLE_LOOKUP, 'property_id=' . self::SUM_MEASURE);
        $this->delete(self::TABLE_PROPERTY, 'id=' . self::SUM_MEASURE);
        $this->delete(self::TABLE_LOOKUP, 'property_id=' . self::LOT_STATUS);
        $this->delete(self::TABLE_PROPERTY, 'id=' . self::LOT_STATUS);
        $this->delete(self::TABLE_LOOKUP, 'property_id=' . self::LOT_REASON);
        $this->delete(self::TABLE_PROPERTY, 'id=' . self::LOT_REASON);
    }
}
