<?php

use yii\db\Migration;

/**
 * Class m200722_140010_subscription
 * Subscription based on the tariff.
 */
class m200722_140010_subscription extends Migration
{
    const TABLE = '{{%subscription}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'id'         => $this->bigPrimaryKey(),
            'user_id'    => $this->bigInteger()->notNull(),
            'tariff_id'  => $this->integer()->notNull(),
            'invoice_id' => $this->bigInteger()->notNull(),

            'from_at'    => $this->integer()->notNull(),
            'till_at'    => $this->integer()->notNull(),
            
            'created_at' => $this->integer()->notNull(),
        ]);

		$this->addForeignKey('fk-subscription-user', self::TABLE, 'user_id', '{{%user}}', 'id', 'restrict', 'restrict');
		$this->addForeignKey('fk-subscription-tariff', self::TABLE, 'tariff_id', '{{%tariff}}', 'id', 'restrict', 'restrict');
		$this->addForeignKey('fk-subscription-invoice', self::TABLE, 'invoice_id', '{{%invoice}}', 'id', 'restrict', 'restrict');

		$this->addCommentOnColumn(self::TABLE, 'user_id', 'Пользователь');
		$this->addCommentOnColumn(self::TABLE, 'tariff_id', 'Тариф');
		$this->addCommentOnColumn(self::TABLE, 'invoice_id', 'Счет, по которому произведена оплата');
		$this->addCommentOnColumn(self::TABLE, 'from_at', 'Дата, с которой действует тариф');
		$this->addCommentOnColumn(self::TABLE, 'till_at', 'Дата, по которую действует тариф');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-subscription-user', self::TABLE);
        $this->dropForeignKey('fk-subscription-tariff', self::TABLE);
        $this->dropForeignKey('fk-subscription-invoice', self::TABLE);
        $this->dropTable(self::TABLE);
    }

}
