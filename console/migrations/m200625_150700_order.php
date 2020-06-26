<?php

use yii\db\Migration;

/**
 * Class m200625_150700_order
 * Таблица заявок
 */
class m200625_150700_order extends Migration
{
    const TABLE = '{{%order}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'id'         => $this->bigPrimaryKey(),
            'lot_id'     => $this->bigInteger()->notNull(),
            'user_id'    => $this->bigInteger()->notNull(),

            'bid_price'  => $this->decimal(15, 2),

            'created_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex('idx-order-lot_id',   self::TABLE, 'lot_id');
        $this->createIndex('idx-order-user_id',  self::TABLE, 'user_id');

        $this->addForeignKey('fk-order-lot',  self::TABLE, 'lot_id',  '{{%lot}}',  'id', 'restrict', 'restrict');
        $this->addForeignKey('fk-order-user', self::TABLE, 'user_id', '{{%user}}', 'id', 'restrict', 'restrict');
        
		$this->addCommentOnColumn(self::TABLE, 'lot_id',  'Лот');
		$this->addCommentOnColumn(self::TABLE, 'user_id', 'Пользователь');
		$this->addCommentOnColumn(self::TABLE, 'bid_price', 'Своя цена');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-order-lot',  self::TABLE);
        $this->dropForeignKey('fk-order-user', self::TABLE);
        $this->dropTable(self::TABLE);
    }
}
