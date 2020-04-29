<?php

use yii\db\Migration;

/**
 * Class m200331_053852_notification
 * 
 * Таблица выбранных уведомлений для юзера.
 */
class m200331_053852_notification extends Migration
{
    const TABLE = '{{%notification}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'id'              => $this->bigPrimaryKey(),
            'user_id'         => $this->bigInteger()->notNull(),
            'new_picture'     => $this->boolean()->defaultValue(false),
            'new_report'      => $this->boolean()->defaultValue(false),
            'price_reduction' => $this->boolean()->defaultValue(false),
            'created_at'      => $this->integer()->notNull(),
            'updated_at'      => $this->integer()->notNull(),
        ]);

        $this->createIndex('idx-notification-user_id',   self::TABLE, 'user_id', true);
        
        $this->addForeignKey ('fk-notification-user', self::TABLE, 'user_id', '{{%user}}', 'id', 'restrict', 'restrict');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-notification-user', self::TABLE);
        $this->dropTable(self::TABLE);
    }
}
