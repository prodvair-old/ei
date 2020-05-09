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
            'case_id'     => $this->bigInteger()->notNull(),
            'etp_id'      => $this->bigInteger(),
            'manager_id'  => $this->bigInteger(),
        ]);
        
        $this->createIndex('idx-torg_debtor-torg', self::TABLE, 'torg_id', true);

        $this->addForeignKey('fk-torg_debtor-torg',  self::TABLE, 'torg_id',  '{{%torg}}',  'id', 'restrict', 'restrict');
        $this->addForeignKey('fk-torg_debtor-bankrupt', self::TABLE, 'bankrupt_id', '{{%bankrupt}}', 'id', 'restrict', 'restrict');
        $this->addForeignKey('fk-torg_debtor-case', self::TABLE, 'case_id', '{{%casefile}}', 'id', 'restrict', 'restrict');

		$this->addCommentOnColumn(self::TABLE, 'torg_id', 'Торг');
		$this->addCommentOnColumn(self::TABLE, 'bankrupt_id', 'Банкрот');
		$this->addCommentOnColumn(self::TABLE, 'case_id', 'Дело Банкрота');
		$this->addCommentOnColumn(self::TABLE, 'etp_id', 'Электронная торговая площадка');
		$this->addCommentOnColumn(self::TABLE, 'manager_id', 'Менеджер, назначенный управлять имеществом Банкрота');
    }

    public function down()
    {
        $this->dropForeignKey('fk-torg_debtor-torg',  self::TABLE);
        $this->dropForeignKey('fk-torg_debtor-bankrupt', self::TABLE);
        $this->dropForeignKey('fk-torg_debtor-case', self::TABLE);
        $this->dropTable(self::TABLE);
    }
}
