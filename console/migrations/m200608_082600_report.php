<?php

use yii\db\Migration;

/**
 * Class m200608_082600_report
 */
class m200608_082600_report extends Migration
{
    const TABLE = '{{%report}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'id'         => $this->bigPrimaryKey(),
            'user_id'    => $this->bigInteger()->notNull(),
            'lot_id'     => $this->bigInteger()->notNull(),

            'title'      => $this->text()->notNull(),
            'content'    => $this->text(),
            'cost'       => $this->decimal(10, 2)->notNull(),
            'status'     => $this->smallInteger(),
            'attraction' => $this->smallInteger()->notNull(),
            'risk'       => $this->smallInteger()->notNull(),
            
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex('idx-report-user_id', self::TABLE, 'user_id');
        $this->createIndex('idx-report-lot_id', self::TABLE, 'lot_id');

		$this->addForeignKey('fk-report-user', self::TABLE, 'user_id', '{{%user}}', 'id', 'restrict', 'restrict');

		$this->addCommentOnColumn(self::TABLE, 'user_id', 'Юзер, создавший Отчет');
		$this->addCommentOnColumn(self::TABLE, 'lot_id', 'Лот, для которого создан Jтчет');
        
		$this->addCommentOnColumn(self::TABLE, 'title', 'Заголовок');
		$this->addCommentOnColumn(self::TABLE, 'content', 'Содержание');
		$this->addCommentOnColumn(self::TABLE, 'cost', 'Стоимость');
		$this->addCommentOnColumn(self::TABLE, 'status', 'Статус');
		$this->addCommentOnColumn(self::TABLE, 'attraction', 'Оценка привлекательности актива');
		$this->addCommentOnColumn(self::TABLE, 'risk', 'Оценка риска приобретения актива');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-report-user', self::TABLE);
        $this->dropTable(self::TABLE);
    }

}
