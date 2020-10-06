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
            'model'         => $this->smallInteger(),
            'parent_id'     => $this->bigInteger(),

            'msg_id'        => $this->bigInteger()->notNull(),
            'msg_guid'      => $this->text()->notNull(),
            'msg_old_id'    => $this->bigInteger(),

            'type'          => $this->smallInteger()->notNull(),
            'status'        => $this->smallInteger()->notNull(),
            'message'       => $this->text()->notNull(),

            'created_at'    => $this->integer()->notNull(),
            'updated_at'    => $this->integer()->notNull(),
        ]);

		$this->addCommentOnColumn(self::TABLE, 'msg_id', 'Номер сообщения');
        $this->addCommentOnColumn(self::TABLE, 'msg_guid', 'Код сообщения');
        $this->addCommentOnColumn(self::TABLE, 'msg_old_id', 'Старый id в сообщении');
        $this->addCommentOnColumn(self::TABLE, 'type', 'Тип сообщения');
        $this->addCommentOnColumn(self::TABLE, 'status', 'Стату сообщения');
        $this->addCommentOnColumn(self::TABLE, 'message', 'Сообщение');
    }

    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
