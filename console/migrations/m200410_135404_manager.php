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
            'id'           => $this->bigPrimaryKey(),
            'type'         => $this->smallInteger()->notNull(),
            'organizer_id' => $this->bigInteger(),
            'created_at'   => $this->integer()->notNull(),
            'updated_at'   => $this->integer()->notNull(),
        ]);
        
		$this->addCommentOnColumn(self::TABLE, 'type', 'Тип - арбитр или организатор (персона или организация)');
		$this->addCommentOnColumn(self::TABLE, 'organizer_id', 'Компания, организатор торга или компания, чьи интересы представляет персона (СРО)');
    }

    public function down()
    {
        $this->dropTable(self::TABLE);
    }
}
