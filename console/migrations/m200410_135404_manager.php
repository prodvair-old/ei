<?php

use yii\db\Migration;

/**
 * Class m200410_135404_manager
 * Частное лицо, назначенное арбитражным управляющим
 */
class m200410_135404_manager extends Migration
{
    const TABLE = '{{%manager}}';

    public function up()
    {
        $this->createTable(self::TABLE, [
            'id'         => $this->bigPrimaryKey(),
            'who'        => $this->smallInteger()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);
        
		$this->addCommentOnColumn(self::TABLE, 'who', 'Кто или что - персона или организация');
    }

    public function down()
    {
        $this->dropTable(self::TABLE);
    }
}
