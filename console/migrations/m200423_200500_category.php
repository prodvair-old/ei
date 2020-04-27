<?php

use yii\db\Migration;

/**
 * Class m200423_200500_category
 * Category table for NestedSet structure.
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

        $this->createTable(self::TABLE, [
            'id'         => $this->primaryKey(),
            'lft'        => $this->integer()->notNull(),
            'rgt'        => $this->integer()->notNull(),
            'depth'      => $this->integer()->notNull(),
            'name'       => $this->string()->notNull(),
            'slug'       => $this->string()->notNull(),

            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex('idx-lft',   self::TABLE, 'lft');
        $this->createIndex('idx-rgt',   self::TABLE, 'rgt');
        $this->createIndex('idx-depth', self::TABLE, 'depth');

		$this->addCommentOnColumn(self::TABLE, 'lft',   'Left');
		$this->addCommentOnColumn(self::TABLE, 'rgt',   'Right');
		$this->addCommentOnColumn(self::TABLE, 'depth', 'depth or level of a tree');
		$this->addCommentOnColumn(self::TABLE, 'name',  'Node name');
		$this->addCommentOnColumn(self::TABLE, 'slug',  'Slug');
    }

    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }
}
