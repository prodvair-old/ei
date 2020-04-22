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
        
        $this->createIndex('idx-torg_debtor-torg', self::TABLE, 'torg_id', true);

        $this->addForeignKey('fk-torg_debtor-torg',  self::TABLE, 'torg_id',  '{{%torg}}',  'id', 'restrict', 'restrict');
        $this->addForeignKey('fk-torg_debtor-manager', self::TABLE, 'manager_id', '{{%manager}}', 'id', 'restrict', 'restrict');

		$this->addCommentOnColumn(self::TABLE, 'torg_id', 'Торг');
		$this->addCommentOnColumn(self::TABLE, 'bankrupt_id', 'Банкрот');
		$this->addCommentOnColumn(self::TABLE, 'manager_id', 'Менеджер, назначенный управлять имеществом Банкрота');
    }

    public function down()
    {
        $this->dropForeignKey('fk-torg_debtor-torg',  self::TABLE);
        $this->dropForeignKey('fk-torg_debtor-manager', self::TABLE);
        $this->dropTable(self::TABLE);
    }
}
