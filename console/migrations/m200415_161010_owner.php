<?php

use yii\db\Migration;

/**
 * Class m200415_161010_owner
 */
class m200415_161010_owner extends Migration
{
    const TABLE = '{{%owner}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'id'            => $this->bigPrimaryKey(),
            'slug'          => $this->string(),
            'description'   => $this->text(),
            'created_at'    => $this->integer()->notNull(),
            'updated_at'    => $this->integer()->notNull(),
        ]);

		$this->addCommentOnColumn(self::TABLE, 'slug', 'Символьный ID');
		$this->addCommentOnColumn(self::TABLE, 'description', 'Краткое описание');
   }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }

}
