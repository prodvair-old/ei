<?php

use yii\db\Migration;

/**
 * Class m200410_135404_manager
 * Частное лицо или организация назначенное арбитражным управляющим
 */
class m200410_135404_manager extends Migration
{
    const TABLE = '{{%manager}}';

    public function up()
    {
        $this->createTable(self::TABLE, [
            'id'         => $this->bigPrimaryKey(),
            'agent'      => $this->smallInteger()->notNull(),
            'reg_number' => $this->string(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);
        
		$this->addCommentOnColumn(self::TABLE, 'agent', 'Агент - персона или организация');
    }

    public function down()
    {
        $this->dropTable(self::TABLE);
    }
}
