<?php

use yii\db\Migration;

/**
 * Class m200414_150942_owner
 */
class m200415_064356_bankrupt extends Migration
{
    const TABLE = '{{%bankrupt}}';

    public function up()
    {
        $this->createTable(self::TABLE, [
            'id'         => $this->bigPrimaryKey(),
            'who'        => $this->smallInteger()->notNull(),
            'type'       => $this->smallInteger()->notNull(),

            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);
        
		$this->addCommentOnColumn(self::TABLE, 'who', 'Кто или что - персона или организация');
		$this->addCommentOnColumn(self::TABLE, 'type', 'Более конкретно о типе персоны или организации');
    }

    public function down()
    {
        $this->dropTable(self::TABLE);
    }
}
