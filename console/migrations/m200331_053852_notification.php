<?php

use yii\db\Migration;

/**
 * Class m200331_053852_notification
 * 
 * Таблица уведомлений для юзера.
 * Пока значения сохраняются в json массиве $user->info, а данная миграция создана для примера.
 */
class m200331_053852_notification extends Migration
{
    const TABLE = '{{%site.notification}}';
    const TABLE_USER = '{{%site.user}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'id'              => $this->primaryKey(),
            'user_id'         => $this->integer()->notNull(),
            'new_picture'     => $this->boolean()->defaultValue(1),
            'new_report'      => $this->boolean()->defaultValue(1),
            'price_reduction' => $this->boolean()->defaultValue(1),
            'created_at'      => $this->integer()->notNull(),
            'updated_at'      => $this->integer()->notNull(),
        ]);
        $this->addForeignKey ('fk-notification-user', self::TABLE, 'user_id', self::TABLE_USER, 'id', 'CASCADE');
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
