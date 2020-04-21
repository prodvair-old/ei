<?php

use yii\db\Migration;

/**
 * Class m200421_050742_wish_list
 * Таблица избранных лотов
 */
class m200421_050742_wish_list extends Migration
{
    const TABLE = '{{%wish_list}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'lot_id'     => $this->bigInteger()->notNull(),
            'user_id'    => $this->bigInteger()->notNull(),

            'created_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex('idx-post_id',   self::TABLE, 'lot_id');
        $this->createIndex('idx-author_id', self::TABLE, 'user_id');

        $this->addForeignKey('fk-wish_list-lot',  self::TABLE, 'lot_id',  '{{%lot}}',  'id', 'restrict', 'restrict');
        $this->addForeignKey('fk-wish_list-user', self::TABLE, 'user_id', '{{%user}}', 'id', 'restrict', 'restrict');
        
		$this->addCommentOnColumn(self::TABLE, 'lot_id',  'Лот');
		$this->addCommentOnColumn(self::TABLE, 'user_id', 'Пользователь');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-wish_list-lot',  self::TABLE);
        $this->dropForeignKey('fk-wish_list-user', self::TABLE);
        $this->dropTable(self::TABLE);
    }
}
