<?php

use yii\db\Migration;

/**
 * Class m200415_132026_torg
 */
class m200415_132026_torg extends Migration
{
    const TABLE = '{{%torg}}';

    public function up()
    {
        $this->createTable(self::TABLE, [
            'id'           => $this->bigPrimaryKey(),
            'etp_id'       => $this->bigInteger()->notNull(),
            'case_id'      => $this->bigInteger()->notNull(),
            
            'property'     => $this->smallInteger()->notNull(),
            'description'  => $this->text()->notNull(),
            
            'started_at'   => $this->integer()->notNull(),
            'end_at'       => $this->integer()->notNull(),
            'completed_at' => $this->integer()->notNull(),
            'published_at' => $this->integer()->notNull(),
            
            'auction'      => $this->smallInteger()->notNull(),

            'created_at'   => $this->integer()->notNull(),
            'updated_at'   => $this->integer()->notNull(),
        ]);
        
        $this->createIndex('idx-model-parent_id', self::TABLE, ['model', 'parent_id']);

		$this->addCommentOnColumn(self::TABLE, 'etp_id', 'Электронная торговая площадка');
		$this->addCommentOnColumn(self::TABLE, 'case_id', 'Дело');
        
		$this->addCommentOnColumn(self::TABLE, 'property', 'Тип имущества - должник, залог');
		$this->addCommentOnColumn(self::TABLE, 'description', 'Описание');
		$this->addCommentOnColumn(self::TABLE, 'started_at', 'Назначенная дата начала торга');
		$this->addCommentOnColumn(self::TABLE, 'end_at', 'Назначенная дата окончания торга');
		$this->addCommentOnColumn(self::TABLE, 'completed_at', 'Дата завершения торга');
		$this->addCommentOnColumn(self::TABLE, 'published_at', 'Дата публикации информации о торге');
		$this->addCommentOnColumn(self::TABLE, 'auction', 'Тип аукциона - открытый, публичный');
    }

    public function down()
    {
        $this->dropTable(self::TABLE);
    }
}
