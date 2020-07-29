<?php

use yii\db\Migration;

/**
 * Class m200717_170100_stat
 */
class m200717_170100_stat extends Migration
{
    const TABLE = '{{%stat}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'sid'        => $this->string(),
            'defs'       => $this->text()->notNull(),
            'duration'   => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->addPrimaryKey('pk-param-ssid', self::TABLE, 'sid');

		$this->addCommentOnColumn(self::TABLE, 'sid', 'Имя пула статистических данных');
		$this->addCommentOnColumn(self::TABLE, 'defs', 'JSON массив с любыми параметрами');
		$this->addCommentOnColumn(self::TABLE, 'duration', 'Время действия текущих значений');
		$this->addCommentOnColumn(self::TABLE, 'updated_at', 'Дата последнего обновления');

        $common = [
            'auction'  => ['caption' => 'Auctions', 'color' => 'aqua', 'icon' => 'gavel', 'value' => 'n/a'],
            'lot'      => ['caption' => 'Lots', 'color' => 'red', 'icon' => 'money', 'value' => 'n/a'],
            'document' => ['caption' => 'Documents', 'color' => 'green', 'icon' => 'file-word-o', 'value' => 'n/a'],
            'user'     => ['caption' => 'Users', 'color' => 'yellow', 'icon' => 'users', 'value' => 'n/a'],
        ];
        $lot = [
            'trace'    => ['caption' => 'Views', 'icon' => 'eye', 'value' => 'n/a'],
            'wish'     => ['caption' => 'Favorites', 'icon' => 'star-o', 'value' => 'n/a'],
            'order'    => ['caption' => 'Orders', 'icon' => 'shopping-cart', 'value' => 'n/a'],
        ];
        
        $this->batchInsert(self::TABLE, ['sid', 'defs', 'duration', 'updated_at'], [
            ['common', json_encode($common), 60, time()],
            ['lot', json_encode($lot), 60, time()],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }

}