<?php

use yii\db\Migration;

/**
 * Class m200414_150942_owner
 */
class m200414_150942_owner extends Migration
{
    const TABLE = '{{%owner}}';

    public function up()
    {
        $this->createTable(self::TABLE, [
            'id'          => $this->bigPrimaryKey(),
            'title'       => $this->string()->notNull(),
            'link'        => $this->string()->notNull(),
            'logo'        => $this->string()->notNull(),
            'description' => $this->text()->notNull(),
            'email'       => $this->string()->notNull(),
            'phone'       => $this->string(10)->notNull(),
            'status'      => $this->smallInteger()->notNull(),

            'created_at'  => $this->integer()->notNull(),
            'updated_at'  => $this->integer()->notNull(),
        ]);
        
		$this->addCommentOnColumn(self::TABLE, 'title', 'Название');
		$this->addCommentOnColumn(self::TABLE, 'link', 'Сайт');
		$this->addCommentOnColumn(self::TABLE, 'logo', 'Лого');
		$this->addCommentOnColumn(self::TABLE, 'description', 'Описание');
		$this->addCommentOnColumn(self::TABLE, 'email', 'Email');
		$this->addCommentOnColumn(self::TABLE, 'phone', 'Телефон');
		$this->addCommentOnColumn(self::TABLE, 'status', 'Статус');
    }

    public function down()
    {
        $this->dropTable(self::TABLE);
    }
}
