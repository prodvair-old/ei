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
            'bankrupt_id'  => $this->bigInteger(),
            'created_at'   => $this->integer()->notNull(),
            'updated_at'   => $this->integer()->notNull(),
        ]);
        
		$this->addCommentOnColumn(self::TABLE, 'agent', 'Агент - персона или организация');
		$this->addCommentOnColumn(self::TABLE, 'bankrupt_id', 'ID должника из ефрсб');
    }

    public function down()
    {
        $this->dropTable(self::TABLE);
    }
}
