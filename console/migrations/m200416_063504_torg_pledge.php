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
        
        $this->createIndex('idx-torg_pledge-torg', self::TABLE, 'torg_id', true);

        $this->addForeignKey('fk-torg_pledge-torg',  self::TABLE, 'torg_id',  '{{%torg}}',  'id', 'restrict', 'restrict');
        $this->addForeignKey('fk-torg_pledge-user',  self::TABLE, 'user_id', ' {{%user}}',  'id', 'restrict', 'restrict');

		$this->addCommentOnColumn(self::TABLE, 'torg_id',  'Торг');
		$this->addCommentOnColumn(self::TABLE, 'owner_id', 'Залогодержатель');
		$this->addCommentOnColumn(self::TABLE, 'user_id',  'Персона, оставившая залог');
    }

    public function down()
    {
        $this->dropForeignKey('fk-torg_pledge-torg',  self::TABLE);
        $this->dropForeignKey('fk-torg_pledge-user',  self::TABLE);
        $this->dropTable(self::TABLE);
    }
}
