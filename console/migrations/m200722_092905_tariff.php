<?php

use yii\db\Migration;

/**
 * Class m200722_092905_tariff
 * Tariff definition.
 */
class m200722_092905_tariff extends Migration
{
    const TABLE = '{{%tariff}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'id'          => $this->primaryKey(),
            'name'        => $this->string()->notNull(),
            'description' => $this->text(),
            'fee'         => $this->integer()->notNull(),
            'created_at'  => $this->integer()->notNull(),
            'updated_at'  => $this->integer()->notNull(),
        ]);

		$this->addCommentOnColumn(self::TABLE, 'name', 'Название');
		$this->addCommentOnColumn(self::TABLE, 'description', 'Описание тарифа');
		$this->addCommentOnColumn(self::TABLE, 'fee', 'Сбор за подписку');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }

}
