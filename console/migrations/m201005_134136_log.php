<?php

use yii\db\Migration;

/**
 * Class m201005_134136_log
 */
class m201005_134136_log extends Migration
{
    const TABLE = '{{%log}}';

    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'id'            => $this->bigPrimaryKey(),
            'model'         => $this->smallInteger()->notNull(),
            'parent_id'     => $this->bigInteger(),

            'status'        => $this->smallInteger()->notNull(),
            'name'          => $this->text()->notNull(),
            'message'       => $this->text()->notNull(),
            'message_json'  => $this->json(),

            'created_at'    => $this->integer()->notNull(),
            'updated_at'    => $this->integer()->notNull(),
        ]);

		$this->addCommentOnColumn(self::TABLE, 'model', 'Код модели');
		$this->addCommentOnColumn(self::TABLE, 'parent_id', 'ID родительской модели');
		$this->addCommentOnColumn(self::TABLE, 'status', 'Статус лога');
        $this->addCommentOnColumn(self::TABLE, 'name', 'Название лога');
        $this->addCommentOnColumn(self::TABLE, 'message', 'Описание лога');
        $this->addCommentOnColumn(self::TABLE, 'message_json', 'Данные лога в json');
    }

    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
