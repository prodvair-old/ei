<?php

use yii\db\Migration;

/**
 * Class m200527_175626_parser
 */
class m200527_175626_parser extends Migration
{
    const TABLE = '{{%parser}}';

    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'id'                => $this->bigPrimaryKey(),
            'tabel_to_id'       => $this->bigInteger()->notNull(),
            'tabel_to_name'     => $this->string()->notNull(),
            'tabel_from_id'     => $this->bigInteger()->notNull(),
            'tabel_from_name'   => $this->string()->notNull(),
            'status'            => $this->smallInteger()->notNull(),
            'error'             => $this->string(),
            'created_at'        => $this->integer()->notNull(),
        ]);

		$this->addCommentOnColumn(self::TABLE, 'tabel_to_id', 'ID первичной таблицы');
		$this->addCommentOnColumn(self::TABLE, 'tabel_to_name', 'Наименовение первичной таблицы');
		$this->addCommentOnColumn(self::TABLE, 'tabel_to_id', 'ID принемаемой таблицы');
		$this->addCommentOnColumn(self::TABLE, 'tabel_to_name', 'Наименовение принемаемой таблицы');
		$this->addCommentOnColumn(self::TABLE, 'status', 'Статус парсинга');
		$this->addCommentOnColumn(self::TABLE, 'error', 'Текст ошибки');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
