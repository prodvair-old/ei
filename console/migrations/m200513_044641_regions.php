<?php

use yii\db\Migration;

/**
 * Class m200513_044641_regions
 */
class m200513_044641_regions extends Migration
{
    const TABLE = '{{%regions}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'id'            => $this->bigPrimaryKey(),
            'name'          => $this->text()->notNull(),
            'name_translit' => $this->text()->notNull(),
            'created_at'    => $this->integer()->notNull(),
            'updated_at'    => $this->integer()->notNull(),
        ]);

		$this->addCommentOnColumn(self::TABLE, 'id',  'Код региона');
		$this->addCommentOnColumn(self::TABLE, 'name',  'Наименование региона');
		$this->addCommentOnColumn(self::TABLE, 'name_translit',  'Наименование региона в транслитерации');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }

}
