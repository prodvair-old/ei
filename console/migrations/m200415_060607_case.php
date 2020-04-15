<?php

use yii\db\Migration;

/**
 * Class m200415_060607_case
 */
class m200415_060607_case extends Migration
{
    const TABLE = '{{%case}}';

    public function up()
    {
        $this->createTable(self::TABLE, [
            'id'          => $this->bigPrimaryKey(),
            'reg_number'  => $this->string()->notNull(),
            'year'        => $this->integer()->notNull(),
            'judge'       => $this->string()->notNull(),

            'created_at'  => $this->integer()->notNull(),
            'updated_at'  => $this->integer()->notNull(),
        ]);
        
		$this->addCommentOnColumn(self::TABLE, 'reg_number', 'Регистрационный номер');
		$this->addCommentOnColumn(self::TABLE, 'year', 'Год');
		$this->addCommentOnColumn(self::TABLE, 'judje', 'Судья');
    }

    public function down()
    {
        $this->dropTable(self::TABLE);
    }
}
