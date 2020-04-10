<?php

use yii\db\Migration;

/**
 * Class m200410_135404_manager
 * Частное лицо, назначенное арбитражным управляющим
 */
class m200410_135404_manager extends Migration
{
    const TABLE = '{{%manager}}';

    public function up()
    {
        $this->createTable(self::TABLE, [
            'id'         => $this->bigPrimaryKey(),
            'sro_id'     => $this->bigInteger()->notNull(),
            'inn'        => $this->string(12)->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);
        
		$this->addCommentOnColumn(self::TABLE, 'sro_id', 'Организация, назначившая управляющего');
		$this->addCommentOnColumn(self::TABLE, 'inn', 'ИНН');
    }

    public function down()
    {
        $this->dropTable(self::TABLE);
    }
}
