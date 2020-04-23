<?php

use yii\db\Migration;

/**
 * Category table for NestedSet structure
 */
class m200423_200500_category extends Migration
{
    const TABLE = '{{%category}}';
    
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(static::TABLE, [
            'id'         => $this->primaryKey(),
            'lft'        => $this->integer()->notNull(),
            'rgt'        => $this->integer()->notNull(),
            'level'      => $this->integer()->notNull(),
            'name'       => $this->string()->notNull(),
            'slug'       => $this->string()->notNull(),

            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex('idx-lft',   static::TABLE, 'lft');
        $this->createIndex('idx-rgt',   static::TABLE, 'rgt');
        $this->createIndex('idx-level', static::TABLE, 'level');

		$this->addCommentOnColumn(static::TABLE, 'lft',   'Left');
		$this->addCommentOnColumn(static::TABLE, 'rgt',   'Right');
		$this->addCommentOnColumn(static::TABLE, 'level', 'Level from the root');
		$this->addCommentOnColumn(static::TABLE, 'name',  'Node name');
		$this->addCommentOnColumn(static::TABLE, 'slug',  'Symbolic ID');

        $this->insert(static::TABLE, [
            'id'         => 1,
            'lft'        => 1, 
            'rgt'        => 2, 
            'level'      => 1, 
            'name'       => 'Категории', 
            'slug'       => 'root', 
            'show'       => false, 
            'created_at' => time(), 
            'updated_at' => time()
        ]);
    }

    public function safeDown()
    {
        $this->dropTable(static::TABLE);
    }
}
