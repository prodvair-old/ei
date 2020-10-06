<?php

use yii\db\Migration;

/**
 * Class m201005_140612_messages
 */
class m201005_140612_messages extends Migration
{
    const TABLE = '{{%messages}}';

    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'id'            => $this->bigPrimaryKey(),
            'msg_id'        => $this->bigInteger()->notNull(),
            'status'        => $this->smallInteger()->notNull(),
            'name'          => $this->text()->notNull(),
            'message'       => $this->text()->notNull(),
            'message_json'  => $this->json(),
            'created_at'    => $this->integer()->notNull(),
            'updated_at'    => $this->integer()->notNull(),
        ]);

		$this->addCommentOnColumn(self::TABLE, 'msg_id', 'Номер сообщения');
		$this->addCommentOnColumn(self::TABLE, 'status', 'Статус лога (или Код модели)');
        $this->addCommentOnColumn(self::TABLE, 'name', 'Название лога');
        $this->addCommentOnColumn(self::TABLE, 'message', 'Описание лога');
        $this->addCommentOnColumn(self::TABLE, 'message_json', 'Данные лога в json');
    }

    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
