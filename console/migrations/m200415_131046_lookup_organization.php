<?php

use yii\db\Migration;

/**
 * Class m200415_131046_lookup_organization
 */
class m200415_131046_lookup_organization extends Migration
{
    const TABLE_LOOKUP   = '{{%lookup}}';
    const TABLE_PROPERTY = '{{%property}}';
    
    const ORGANIZATION_STATUS   = 5;
    const ORGANIZATION_TYPE     = 6;
    const ORGANIZATION_ACTIVITY = 7;

    public function safeUp()
    {
        $this->insert(self::TABLE_PROPERTY, ['id' => self::ORGANIZATION_STATUS, 'name' => 'OrganizationStatus']);
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Ожидание',  'code' => 1, 'property_id' => self::ORGANIZATION_STATUS, 'position' => 1]);
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Проверено', 'code' => 2, 'property_id' => self::ORGANIZATION_STATUS, 'position' => 2]);

        $this->insert(self::TABLE_PROPERTY, ['id' => self::ORGANIZATION_TYPE, 'name' => 'OrganizationType']);
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Не важно',    'code' => 11, 'property_id' => self::ORGANIZATION_TYPE, 'position' => 1]);
        $this->insert(self::TABLE_LOOKUP, ['name' => 'СРО',         'code' => 12, 'property_id' => self::ORGANIZATION_TYPE, 'position' => 2]);
        $this->insert(self::TABLE_LOOKUP, ['name' => 'ЕТП',         'code' => 13, 'property_id' => self::ORGANIZATION_TYPE, 'position' => 3]);
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Владелец',    'code' => 14, 'property_id' => self::ORGANIZATION_TYPE, 'position' => 4]);
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Банкрот',     'code' => 15, 'property_id' => self::ORGANIZATION_TYPE, 'position' => 5]);
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Управляющий', 'code' => 3,  'property_id' => self::ORGANIZATION_TYPE, 'position' => 6]);
        
        $this->insert(self::TABLE_PROPERTY, ['id' => self::ORGANIZATION_ACTIVITY, 'name' => 'OrganizationActivity']);
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Отсутствующий банкрот',            'code' => 1,  'property_id' => self::ORGANIZATION_ACTIVITY, 'position' => 1]);
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Сельскохозяйственное предприятие', 'code' => 2,  'property_id' => self::ORGANIZATION_ACTIVITY, 'position' => 2]);
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Городская организация',            'code' => 3,  'property_id' => self::ORGANIZATION_ACTIVITY, 'position' => 3]);
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Кредитная организация',            'code' => 4,  'property_id' => self::ORGANIZATION_ACTIVITY, 'position' => 4]);
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Недвижимость',                     'code' => 5,  'property_id' => self::ORGANIZATION_ACTIVITY, 'position' => 5]);
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Растворившийся банкрот',           'code' => 6,  'property_id' => self::ORGANIZATION_ACTIVITY, 'position' => 6]);
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Страхование',                      'code' => 7,  'property_id' => self::ORGANIZATION_ACTIVITY, 'position' => 7]);
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Монопольная организация',          'code' => 8,  'property_id' => self::ORGANIZATION_ACTIVITY, 'position' => 8]);
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Другая',                           'code' => 9,  'property_id' => self::ORGANIZATION_ACTIVITY, 'position' => 9]);
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Частный пенсионный фонд',          'code' => 10, 'property_id' => self::ORGANIZATION_ACTIVITY, 'position' => 10]);
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Простая организация',              'code' => 11, 'property_id' => self::ORGANIZATION_ACTIVITY, 'position' => 11]);
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Стратегическая организация',       'code' => 12, 'property_id' => self::ORGANIZATION_ACTIVITY, 'position' => 12]);
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Предприниматель',                  'code' => 13, 'property_id' => self::ORGANIZATION_ACTIVITY, 'position' => 13]);
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Фермер',                           'code' => 14, 'property_id' => self::ORGANIZATION_ACTIVITY, 'position' => 14]);
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Обычный человек',                  'code' => 15, 'property_id' => self::ORGANIZATION_ACTIVITY, 'position' => 15]);
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Некто',                            'code' => 16, 'property_id' => self::ORGANIZATION_ACTIVITY, 'position' => 16]);
    }

    public function safeDown()
    {
        $this->delete(self::TABLE_LOOKUP, 'property_id=' . self::ORGANIZATION_STATUS);
        $this->delete(self::TABLE_PROPERTY, 'id=' . self::ORGANIZATION_STATUS);
        $this->delete(self::TABLE_LOOKUP, 'property_id=' . self::ORGANIZATION_TYPE);
        $this->delete(self::TABLE_PROPERTY, 'id=' . self::ORGANIZATION_TYPE);
        $this->delete(self::TABLE_LOOKUP, 'property_id=' . self::ORGANIZATION_ACTIVITY);
        $this->delete(self::TABLE_PROPERTY, 'id=' . self::ORGANIZATION_ACTIVITY);
    }
}
