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
            'etp_id'       => $this->bigInteger()->notNull(),
            
            'property'     => $this->smallInteger()->notNull(),
            'description'  => $this->text()->notNull(),
            
            'started_at'   => $this->integer(),
            'end_at'       => $this->integer(),
            'completed_at' => $this->integer(),
            'published_at' => $this->integer(),
            
            'offer'        => $this->smallInteger()->notNull(),

            'created_at'   => $this->integer()->notNull(),
            'updated_at'   => $this->integer()->notNull(),
        ]);
        
		$this->addForeignKey('fk-torg-etp', self::TABLE, 'etp_id', '{{%etp}}', 'id', 'restrict', 'restrict');

		$this->addCommentOnColumn(self::TABLE, 'etp_id', 'Электронная торговая площадка');
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
		$this->dropForeignKey('fk-torg-etp', self::TABLE);
        $this->dropTable(self::TABLE);
    }
}
