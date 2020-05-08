<?php

use yii\db\Migration;

/**
 * Class m200415_151010_etp
 */
class m200415_151010_etp extends Migration
{
    const TABLE = '{{%etp}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'id'            => $this->bigPrimaryKey(),
            'efrsb_id'      => $this->bigInteger(),
            'created_at'    => $this->integer()->notNull(),
            'updated_at'    => $this->integer()->notNull(),
        ]);

		$this->addCommentOnColumn(self::TABLE, 'efrsb_id', 'Внешний ID');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }

}
