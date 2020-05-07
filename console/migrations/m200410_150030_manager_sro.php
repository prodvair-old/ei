<?php

use yii\db\Migration;

/**
 * Class m200410_150030_manager_sro
 * Связь менеджера (персоны) с организацией, нанявшим его.
 */
class m200410_150030_manager_sro extends Migration
{
    const TABLE = '{{%manager_sro}}';

    public function up()
    {
        $this->createTable(self::TABLE, [
            'manager_id' => $this->bigInteger()->notNull(),
            'sro_id'     => $this->bigInteger()->notNull(),
        ]);

        $this->createIndex('idx-manager_sro-manager_id', self::TABLE, 'manager_id', true);
        
        $this->addCommentOnColumn(self::TABLE, 'manager_id', 'Менеджер');
        $this->addCommentOnColumn(self::TABLE, 'sro_id', 'Саморегулируемая организация');
    }

    public function down()
    {
        $this->dropTable(self::TABLE);
    }
}
