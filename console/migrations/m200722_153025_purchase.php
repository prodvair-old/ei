<?php

use yii\db\Migration;

/**
 * Class m200722_153025_purchase
 * The reports purchase.
 */
class m200722_153025_purchase extends Migration
{
    const TABLE = '{{%purchase}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'id'         => $this->bigPrimaryKey(),
            'user_id'    => $this->bigInteger()->notNull(),
            'report_id'  => $this->bigInteger()->notNull(),
            'invoice_id' => $this->bigInteger()->notNull(),
            
            'created_at' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey('fk-purchase-user', self::TABLE, 'user_id', '{{%user}}', 'id', 'restrict', 'restrict');
        $this->addForeignKey('fk-purchase-report', self::TABLE, 'report_id', '{{%report}}', 'id', 'restrict', 'restrict');
        $this->addForeignKey('fk-purchase-invoice', self::TABLE, 'invoice_id', '{{%invoice}}', 'id', 'restrict', 'restrict');

        $this->addCommentOnColumn(self::TABLE, 'user_id', 'Пользователь');
        $this->addCommentOnColumn(self::TABLE, 'report_id', 'Отчет');
        $this->addCommentOnColumn(self::TABLE, 'invoice_id', 'Счет, по которому произведена оплата');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-purchase-user', self::TABLE);
        $this->dropForeignKey('fk-purchase-report', self::TABLE);
        $this->dropForeignKey('fk-purchase-invoice', self::TABLE);
        $this->dropTable(self::TABLE);
    }

}
