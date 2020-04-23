<?php

use yii\db\Migration;

/**
 * Class m200423_194649_lot_price
 * История снижения цены Лота.
 */
class m200423_194649_lot_price extends Migration
{
    const TABLE = '{{%lot_price}}';

    public function up()
    {
        $this->createTable(self::TABLE, [
            'id'         => $this->bigPrimaryKey(),
            'lot_id'     => $this->bigInteger()->notNull(),
            
            'start_at'   => $this->integer()->notNull(),
            'end_at'     => $this->integer()->notNull(),

            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);
        
		$this->addForeignKey('fk-lot_price-lot', self::TABLE, 'lot_id', '{{%lot}}', 'id', 'restrict', 'restrict');

		$this->addCommentOnColumn(self::TABLE, 'lot_id', 'Лот');
		$this->addCommentOnColumn(self::TABLE, 'start_at', 'Дата начала действия цены');
		$this->addCommentOnColumn(self::TABLE, 'end_at', 'Дата окончания действия цены');
    }

    public function down()
    {
		$this->dropForeignKey('fk-lot_price-lot', self::TABLE);
        $this->dropTable(self::TABLE);
    }
}
