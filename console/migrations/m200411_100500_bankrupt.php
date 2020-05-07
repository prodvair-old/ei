<?php

use yii\db\Migration;

/**
 * Class m200411_100500_bankrupt
 * Частное лицо или организация банкрот
 */
class m200411_100500_bankrupt extends Migration
{
    const TABLE = '{{%bankrupt}}';

    public function up()
    {
        $this->createTable(self::TABLE, [
            'id'           => $this->bigPrimaryKey(),
            'agent'        => $this->smallInteger()->notNull(),
            'created_at'   => $this->integer()->notNull(),
            'updated_at'   => $this->integer()->notNull(),
        ]);
        
		$this->addCommentOnColumn(self::TABLE, 'agent', 'Агент - персона или организация');
    }

    public function down()
    {
        $this->dropTable(self::TABLE);
    }
}
