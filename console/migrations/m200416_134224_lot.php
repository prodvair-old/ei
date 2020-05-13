<?php

use yii\db\Migration;

/**
 * Class m200416_134224_lot
 */
class m200416_134224_lot extends Migration
{
    const TABLE = '{{%lot}}';

    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'id'               => $this->bigPrimaryKey(),
            'torg_id'          => $this->bigInteger()->notNull(),
            
            'title'            => $this->text()->notNull(),
            'description'      => $this->text()->notNull(),
            
            'start_price'      => $this->decimal(15, 2)->notNull(),
            'step'             => $this->decimal(12, 2)->notNull(),
            'step_measure'     => $this->smallInteger()->notNull(),
            'deposit'          => $this->decimal(15, 2)->notNull(),
            'deposit_measure'  => $this->smallInteger()->notNull(),
            
            'status'           => $this->smallInteger()->notNull(),
            'reason'           => $this->smallInteger()->notNull(),

            'created_at'       => $this->integer()->notNull(),
            'updated_at'       => $this->integer()->notNull(),
        ]);
        
		$this->addForeignKey('fk-lot-torg', self::TABLE, 'torg_id', '{{%torg}}', 'id', 'restrict', 'restrict');

		$this->addCommentOnColumn(self::TABLE, 'torg_id', 'Торг, к которому принадлежит лот');
		$this->addCommentOnColumn(self::TABLE, 'title', 'Заголовок лота, как правило это первые слова Описания');
		$this->addCommentOnColumn(self::TABLE, 'description', 'Описание');
		$this->addCommentOnColumn(self::TABLE, 'start_price', 'Начальная цена');
		$this->addCommentOnColumn(self::TABLE, 'step', 'Шаг уменьшения цены');
		$this->addCommentOnColumn(self::TABLE, 'step_measure', 'Мера шага - сумма, %');
		$this->addCommentOnColumn(self::TABLE, 'deposit', 'Размер задатка за лот');
		$this->addCommentOnColumn(self::TABLE, 'deposit_measure', 'Мера задатка - сумма, %');
		$this->addCommentOnColumn(self::TABLE, 'status', 'Статус');
		$this->addCommentOnColumn(self::TABLE, 'reason', 'Причина статуса');
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-lot-torg', self::TABLE);
        $this->dropTable(self::TABLE);
    }
}
