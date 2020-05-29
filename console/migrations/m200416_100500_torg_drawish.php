<?php

use yii\db\Migration;

/**
 * Class m200416_100500_torg_drawish
 */
class m200416_100500_torg_drawish extends Migration
{
    const TABLE = '{{%torg_drawish}}';

    public function up()
    {
        $this->createTable(self::TABLE, [
            'torg_id'    => $this->bigInteger()->notNull(),
            'manager_id' => $this->bigInteger()->notNull(),
        ]);
        
        $this->createIndex('idx-torg_drawish-torg', self::TABLE, 'torg_id', true);

        $this->addForeignKey('fk-torg_drawish-torg',  self::TABLE, 'torg_id',  '{{%torg}}',  'id', 'restrict', 'restrict');
        $this->addForeignKey('fk-torg_drawish-manager',  self::TABLE, 'manager_id', ' {{%manager}}',  'id', 'restrict', 'restrict');

		$this->addCommentOnColumn(self::TABLE, 'torg_id',  'Торг');
		$this->addCommentOnColumn(self::TABLE, 'manager_id', 'Организация или частное лицо, опубликовавшая лот по арестованному, муниципальному имуществу');
    }

    public function down()
    {
        $this->dropForeignKey('fk-torg_drawish-torg',  self::TABLE);
        $this->dropForeignKey('fk-torg_drawish-manager',  self::TABLE);
        $this->dropTable(self::TABLE);
    }
}
