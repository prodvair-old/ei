<?php

use yii\db\Migration;

/**
 * Class m200513_044641_region
 */
class m200513_044641_region extends Migration
{
    const TABLE = '{{%region}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'id'         => $this->bigPrimaryKey(),
            'name'       => $this->string()->notNull(),
            'slug'       => $this->string()->notNull(),
            'created_at' => $this->integer()->notNull(),
        ]);

		$this->addCommentOnColumn(self::TABLE, 'id', 'Код региона');
		$this->addCommentOnColumn(self::TABLE, 'name', 'Наименование региона');
		$this->addCommentOnColumn(self::TABLE, 'slug', 'Наименование региона в транслитерации');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }

}
