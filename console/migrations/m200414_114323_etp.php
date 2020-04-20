<?php

use yii\db\Migration;

/**
 * Class m200414_114323_etp
 * 
 * Электронная торговая площадка
 */
class m200414_114323_etp extends Migration
{
    const TABLE = '{{%etp}}';

    public function up()
    {
        $this->createTable(self::TABLE, [
            'id'         => $this->bigPrimaryKey(),
            'name'       => $this->string()->notNull(),
            'title'      => $this->string()->notNull(),
            'link'       => $this->string()->notNull(),
            'inn'        => $this->string(10)->notNull(),

            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);
        
		$this->addCommentOnColumn(self::TABLE, 'name', 'Название');
		$this->addCommentOnColumn(self::TABLE, 'title', 'Полное название');
		$this->addCommentOnColumn(self::TABLE, 'link', 'Сайт');
		$this->addCommentOnColumn(self::TABLE, 'inn', 'ИНН');
    }

    public function down()
    {
        $this->dropTable(self::TABLE);
    }
}
