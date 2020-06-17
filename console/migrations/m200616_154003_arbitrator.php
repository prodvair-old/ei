<?php

use yii\db\Migration;

/**
 * Class m200616_154003_arbitrator
 */
class m200616_154003_arbitrator extends Migration
{
    const TABLE = '{{%arbitrator}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'user_id'    => $this->bigPrimaryKey(),
            'manager_id' => $this->bigInteger()->notNull(),
        ]);

		$this->addForeignKey('fk-user_manager-user', self::TABLE, 'user_id', '{{%user}}', 'id', 'restrict', 'restrict');
		$this->addForeignKey('fk-user_manager-manager', self::TABLE, 'manager_id', '{{%manager}}', 'id', 'restrict', 'restrict');

		$this->addCommentOnColumn(self::TABLE, 'user_id', 'Пользователь');
		$this->addCommentOnColumn(self::TABLE, 'manager_id', 'Арбитражный управляющий');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-user_manager-user', self::TABLE);
        $this->dropForeignKey('fk-user_manager-manager', self::TABLE);
        $this->dropTable(self::TABLE);
    }
}
