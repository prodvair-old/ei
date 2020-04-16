<?php

use yii\db\Migration;

/**
 * Class m200416_134224_lot
 */
class m200416_134224_lot extends Migration
{
    const TABLE = '{{%lot}}';

    public function up()
    {
        $this->createTable(self::TABLE, [
            'id'               => $this->bigPrimaryKey(),
            'torg_id'          => $this->bigInteger()->notNull(),
            
            'title'            => $this->string()->notNull(),
            'description'      => $this->text()->notNull(),
            
            'start_price'      => $this->decimal(10, 2)->notNull(),
            'step'             => $this->decimal(10, 2)->notNull(),
            'step_measure'     => $this->smallInteger()->notNull(),
            'deposite'         => $this->decimal(10, 2)->notNull(),
            'deposite_measure' => $this->smallInteger()->notNull(),
            
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
		$this->addCommentOnColumn(self::TABLE, 'deposite', 'Размер задатка за лот');
		$this->addCommentOnColumn(self::TABLE, 'deposite_measure', 'Мера депозита - сумма, %');
		$this->addCommentOnColumn(self::TABLE, 'status', 'Статус');
		$this->addCommentOnColumn(self::TABLE, 'reason', 'Причина статуса');
    }

    public function down()
    {
        $this->dropForeignKey('fk-lot-torg', self::TABLE);
        $this->dropTable(self::TABLE);
    }
}
