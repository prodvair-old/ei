<?php

use yii\db\Migration;

/**
 * Class m200423_202539_lot_category
 * Связь Лотов и Категорий. 
 */
class m200423_202539_lot_category extends Migration
{
    const TABLE = '{{%lot_category}}';

    public function up()
    {
        $this->createTable(self::TABLE, [
            'lot_id'      => $this->bigInteger()->notNull(),
            'category_id' => $this->integer()->notNull(),
            'created_at'  => $this->integer()->notNull(),
        ]);
        
        // $this->addPrimaryKey('pkey-lot_category', self::TABLE, ['lot_id', 'category_id']);
        $this->createIndex('idx-lot_category-lot', self::TABLE, 'lot_id');

		$this->addForeignKey('fk-lot_category-lot',      self::TABLE, 'lot_id',      '{{%lot}}',      'id', 'restrict', 'restrict');
		$this->addForeignKey('fk-lot_category-category', self::TABLE, 'category_id', '{{%category}}', 'id', 'restrict', 'restrict');

		$this->addCommentOnColumn(self::TABLE, 'lot_id', 'Лот');
		$this->addCommentOnColumn(self::TABLE, 'category_id', 'Категория');
    }

    public function down()
    {
		$this->dropForeignKey('fk-lot_category-lot',      self::TABLE);
		$this->dropForeignKey('fk-lot_category-category', self::TABLE);
        $this->dropTable(self::TABLE);
    }
}
