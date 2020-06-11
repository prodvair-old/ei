<?php

use yii\db\Migration;

/**
 * Class m200611_104013_param
 */
class m200611_104013_param extends Migration
{
    const TABLE = '{{%param}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'sid'  => $this->string(),
            'defs' => $this->text()->notNull(),
        ]);

        $this->addPrimaryKey('pk-param-sid', self::TABLE, 'sid');

		$this->addCommentOnColumn(self::TABLE, 'sid', 'Имя пула параметров');
		$this->addCommentOnColumn(self::TABLE, 'defs', 'JSON массив с любыми параметрами');

        $statistic = [
            'Auctions'  => ['color' => 'aqua',   'icon' => 'gavel', 'value' => 123681],
            'Lots'      => ['color' => 'red',    'icon' => 'money', 'value' => 432470 ],
            'Documents' => ['color' => 'green',  'icon' => 'file-word-o', 'value' => 1245760],
            'Users'     => ['color' => 'yellow', 'icon' => 'users', 'value' => 815],
        ];
        $this->batchInsert(self::TABLE, ['sid', 'defs'], [['statistic', json_encode($statistic)]]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }

}
