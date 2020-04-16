<?php

use yii\db\Migration;

/**
 * Class m200416_063504_torg_pledge
 */
class m200416_063504_torg_pledge extends Migration
{
    const TABLE = '{{%torg_pledge}}';

    public function up()
    {
        $this->createTable(self::TABLE, [
            'torg_id'  => $this->bigInteger()->notNull(),
            'owner_id' => $this->bigInteger()->notNull(),
            'user_id'  => $this->bigInteger()->notNull(),
        ]);
        
        $this->createIndex('idx-torg-owner', self::TABLE, ['torg_id', 'owner_id']);
        $this->createIndex('idx-torg-user',  self::TABLE, ['torg_id', 'user_id']);

		$this->addCommentOnColumn(self::TABLE, 'torg_id', 'Торг');
		$this->addCommentOnColumn(self::TABLE, 'bankrupt_id', 'Залогодержатель');
		$this->addCommentOnColumn(self::TABLE, 'manager_id', 'Персона, оставившая залог');
    }

    public function down()
    {
        $this->dropTable(self::TABLE);
    }
}
