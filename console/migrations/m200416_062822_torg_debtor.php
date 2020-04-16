<?php

use yii\db\Migration;

/**
 * Class m200416_062822_torg_debtor
 */
class m200416_062822_torg_debtor extends Migration
{
    const TABLE = '{{%torg_debtor}}';

    public function up()
    {
        $this->createTable(self::TABLE, [
            'torg_id'     => $this->bigInteger()->notNull(),
            'bankrupt_id' => $this->bigInteger()->notNull(),
            'manager_id'  => $this->bigInteger()->notNull(),
        ]);
        
        $this->createIndex('idx-torg-bankrupt', self::TABLE, ['torg_id', 'bankrupt_id']);
        $this->createIndex('idx-torg-manager',  self::TABLE, ['torg_id', 'manager_id']);

		$this->addCommentOnColumn(self::TABLE, 'torg_id', 'Торг');
		$this->addCommentOnColumn(self::TABLE, 'bankrupt_id', 'Банкрот');
		$this->addCommentOnColumn(self::TABLE, 'manager_id', 'Менеджер, назначенный управлять имеществом Банкрота');
    }

    public function down()
    {
        $this->dropTable(self::TABLE);
    }
}
