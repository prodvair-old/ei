<?php

use yii\db\Migration;

/**
 * Class m200421_050742_wish_list
 * Таблица избранных лотов
 */
class m200421_050742_wish_list extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'id'               => $this->bigPrimaryKey(),

            'lot_id'           => $this->smallInteger()->notNull(),
            'user_id'          => $this->bigInteger()->notNull(),

            'created_at'       => $this->integer()->notNull()
        ]);

        $this->addForeignKey('fk-wish_list-lot',  self::TABLE, 'lot_id', '{{%lot}}',  'id', 'restrict', 'restrict');
        $this->addForeignKey('fk-user-lot',  self::TABLE, 'user_id', '{{%user}}',  'id', 'restrict', 'restrict');
        
		$this->addCommentOnColumn(self::TABLE, 'lot_id', 'ID лота');
		$this->addCommentOnColumn(self::TABLE, 'user_id', 'ID пользователя');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-wish_list-lot', self::TABLE);
        $this->dropForeignKey('fk-user-lot', self::TABLE);
        $this->dropTable(self::TABLE);
    }
}
