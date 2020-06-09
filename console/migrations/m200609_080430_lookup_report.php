<?php

use yii\db\Migration;

/**
 * Class m200609_080430_lookup_report
 */
class m200609_080430_lookup_report extends Migration
{
    const TABLE_LOOKUP   = '{{%lookup}}';
    const TABLE_PROPERTY = '{{%property}}';
    
    const REPORT_STATUS = 13;

    public function safeUp()
    {
        $this->insert(self::TABLE_PROPERTY, ['id' => self::REPORT_STATUS, 'name' => 'ReportStatus']);
        // статус отчета эксперта
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Активен', 'code' => 1, 'property_id' => self::REPORT_STATUS, 'position' => 1]);
        $this->insert(self::TABLE_LOOKUP, ['name' => 'Архив',   'code' => 2, 'property_id' => self::REPORT_STATUS, 'position' => 2]);
    }

    public function safeDown()
    {
        $this->delete(self::TABLE_LOOKUP, 'property_id=' . self::REPORT_STATUS);
        $this->delete(self::TABLE_PROPERTY, 'id=' . self::REPORT_STATUS);
    }
}
