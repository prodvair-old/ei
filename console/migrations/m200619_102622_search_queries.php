<?php

use yii\db\Migration;

/**
 * Class m200619_102622_search_queries
 */
class m200619_102622_search_queries extends Migration
{
    const TABLE = '{{%search_queries}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'id'                => $this->bigPrimaryKey(),
            'user_id'           => $this->bigInteger()->notNull(),
            'title'             => $this->string()->notNull(),
            'description'       => $this->text()->notNull(),
            'url'               => $this->text()->notNull(),
            'url_query'         => $this->text()->notNull(),
            'search_date'       => $this->integer()->notNull(),
            'search_lot_count'  => $this->integer()->notNull()->defaultValue(0),
            'send_email'        => $this->boolean()->defaultValue(true),
            'created_at'        => $this->integer()->notNull(),
            'updated_at'        => $this->integer()->notNull(),
        ]);

        $this->createIndex('idx-search_queries-user_id',   self::TABLE, 'user_id', true);
        
        $this->addForeignKey('fk-search_queries-user', self::TABLE, 'user_id', '{{%user}}', 'id', 'restrict', 'restrict');

        $this->addCommentOnColumn(self::TABLE, 'user_id', 'Пользователь');
        $this->addCommentOnColumn(self::TABLE, 'title', 'Название');
        $this->addCommentOnColumn(self::TABLE, 'title', 'Описание');
        $this->addCommentOnColumn(self::TABLE, 'url', 'Полная ссылка');
        $this->addCommentOnColumn(self::TABLE, 'url_query', 'GET запрос');
		$this->addCommentOnColumn(self::TABLE, 'search_date', 'Дата последнего поиска');
		$this->addCommentOnColumn(self::TABLE, 'search_lot_count', 'Количество найденных лотов');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-search_queries-user', self::TABLE);
        $this->dropTable(self::TABLE);
    }
}
