<?php

use yii\db\Migration;

/**
 * Class m200507_061808_etp
 */
class m200507_061808_etp extends Migration
{
    const TABLE = '{{%etp}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'id'            => $this->bigPrimaryKey(),

            'number'        => $this->integer()->notNull(),
            'organizer_id'  => $this->bigInteger()->notNull(),

            'created_at'    => $this->integer()->notNull(),
            'updated_at'    => $this->integer()->notNull(),
        ]);

        $this->addForeignKey('fk-etp-organization', self::TABLE, 'organizer_id', '{{%organization}}', 'id', 'restrict', 'restrict');

		$this->addCommentOnColumn(self::TABLE, 'number', 'Номер торговой площадки');
		$this->addCommentOnColumn(self::TABLE, 'organizer_id', 'Компания торговой площадки');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-etp-organization', self::TABLE);
        $this->dropTable(self::TABLE);
    }
}
