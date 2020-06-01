<?php

use yii\db\Migration;

/**
 * Class m200415_172026_torg
 */
class m200415_172026_torg extends Migration
{
    const TABLE = '{{%torg}}';

    public function up()
    {
        $this->createTable(self::TABLE, [
            'id'           => $this->bigPrimaryKey(),
            'msg_id'       => $this->string()->notNull(),
            
            'property'     => $this->smallInteger()->notNull(),
            'description'  => $this->text(),
            
            'started_at'   => $this->integer(),
            'end_at'       => $this->integer(),
            'completed_at' => $this->integer(),
            'published_at' => $this->integer(),
            
            'offer'        => $this->smallInteger()->notNull(),

            'created_at'   => $this->integer()->notNull(),
            'updated_at'   => $this->integer()->notNull(),
        ]);
        
        $this->createIndex('idx-torg-published_at', self::TABLE, 'published_at');


		$this->addCommentOnColumn(self::TABLE, 'property', 'Тип имущества - должник, залог, арестованное, муниципальное');
		$this->addCommentOnColumn(self::TABLE, 'description', 'Описание');
		$this->addCommentOnColumn(self::TABLE, 'started_at', 'Назначенная дата начала торга');
		$this->addCommentOnColumn(self::TABLE, 'end_at', 'Назначенная дата окончания торга');
		$this->addCommentOnColumn(self::TABLE, 'completed_at', 'Дата завершения торга');
		$this->addCommentOnColumn(self::TABLE, 'published_at', 'Дата публикации информации о торге');
		$this->addCommentOnColumn(self::TABLE, 'offer', 'Тип предложения - публичное, аукцион, конкурс');
    }

    public function down()
    {
        $this->dropTable(self::TABLE);
    }
}
