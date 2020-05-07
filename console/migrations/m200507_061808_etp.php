<?php

use yii\db\Migration;

/**
 * Class m200507_061808_etp
 */
class m200507_061808_etp extends Migration
{
    const TABLE = '{{%etp}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'id'            => $this->bigPrimaryKey(),

            'number'        => $this->integer()->notNull(),
            'organizer_id'  => $this->bigInteger(),

            'created_at'    => $this->integer()->notNull(),
            'updated_at'    => $this->integer()->notNull(),
        ]);

        $this->addForeignKey('fk-lot-torg', self::TABLE, 'torg_id', '{{%torg}}', 'id', 'restrict', 'restrict');

		$this->addCommentOnColumn(self::TABLE, 'torg_id', 'Торг, к которому принадлежит лот');
		$this->addCommentOnColumn(self::TABLE, 'title', 'Заголовок лота, как правило это первые слова Описания');
		$this->addCommentOnColumn(self::TABLE, 'description', 'Описание');
		$this->addCommentOnColumn(self::TABLE, 'start_price', 'Начальная цена');
		$this->addCommentOnColumn(self::TABLE, 'step', 'Шаг уменьшения цены');
		$this->addCommentOnColumn(self::TABLE, 'step_measure', 'Мера шага - сумма, %');
		$this->addCommentOnColumn(self::TABLE, 'deposite', 'Размер задатка за лот');
		$this->addCommentOnColumn(self::TABLE, 'deposite_measure', 'Мера задатка - сумма, %');
		$this->addCommentOnColumn(self::TABLE, 'status', 'Статус');
		$this->addCommentOnColumn(self::TABLE, 'reason', 'Причина статуса');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-lot-torg', self::TABLE);
        $this->dropTable(self::TABLE);
    }
}
