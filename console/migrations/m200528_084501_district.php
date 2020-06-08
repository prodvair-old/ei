<?php

use yii\db\Migration;

/**
 * Class m200528_084501_district
 */
class m200528_084501_district extends Migration
{
    const TABLE = '{{%district}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'id'         => $this->bigPrimaryKey(),
            'name'       => $this->string()->notNull(),
            'created_at' => $this->integer()->notNull(),
        ]);

		$this->addCommentOnColumn(self::TABLE, 'id', 'Код округа');
		$this->addCommentOnColumn(self::TABLE, 'name', 'Наименование округа');

        $districts = [
            [1, 'Центральный', time()],
            [2, 'Северо-Западный', time()],
            [3, 'Южный', time()],
            [4, 'Северо-Кавказский', time()],
            [5, 'Приволжский', time()],
            [6, 'Уральский', time()],
            [7, 'Сибирский', time()],
            [8, 'Дальневосточный', time()],
        ];

        $this->batchInsert(self::TABLE, ['id', 'name', 'created_at'], $districts);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }

}
