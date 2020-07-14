<?php

use yii\db\Migration;

/**
 * Class m200714_112030_lot_trace
 * User trace with IP and date of the Lot viewed.
 */
class m200714_112030_lot_trace extends Migration
{
    const TABLE = '{{%lot_trace}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'id'         => $this->bigPrimaryKey(),
            'lot_id'     => $this->bigInteger()->notNull(),

            'ip'         => $this->string(),

            'created_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex('idx-lot_trace-lot_id', self::TABLE, 'lot_id'], true);
        $this->createIndex('idx-lot_trace-ip', self::TABLE, 'ip'], true);

        $this->addForeignKey('fk-lot_trace-lot_id',  self::TABLE, 'lot_id',  '{{%lot}}',  'id', 'restrict', 'restrict');

		$this->addCommentOnColumn(self::TABLE, 'lot_id', 'Лот');
		$this->addCommentOnColumn(self::TABLE, 'ip', 'IP адрес посетителя');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-lot_trace-lot',  self::TABLE);
        $this->dropTable(self::TABLE);
    }
}
