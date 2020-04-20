<?php

use yii\db\Migration;

/**
 * Class m200415_130037_organization
 */
class m200415_130037_organization extends Migration
{
    const TABLE = '{{%organization}}';

    public function up()
    {
        $this->createTable(self::TABLE, [
            'id'           => $this->bigPrimaryKey(),
            'model'        => $this->smallInteger()->notNull(),
            'parent_id'    => $this->bigInteger()->notNull(),
            
            'title'        => $this->string()->notNull(),
            'inn'          => $this->string(10)->notNull(),
            'ogrn'         => $this->string(13)->notNull(),
            'reg_number'   => $this->string()->notNull(),

            'created_at'   => $this->integer()->notNull(),
            'updated_at'   => $this->integer()->notNull(),
        ]);
        
        $this->createIndex('idx-model-parent_id', self::TABLE, ['model', 'parent_id']);

		$this->addCommentOnColumn(self::TABLE, 'model', 'Код модели');
		$this->addCommentOnColumn(self::TABLE, 'parent_id', 'ID в соответствующей модели, например в Bankrupt');
        
		$this->addCommentOnColumn(self::TABLE, 'title', 'название');
		$this->addCommentOnColumn(self::TABLE, 'inn', 'ИНН');
		$this->addCommentOnColumn(self::TABLE, 'ogrn', 'ОГРН');
		$this->addCommentOnColumn(self::TABLE, 'reg_number', 'Регистрационный номер');
    }

    public function down()
    {
        $this->dropTable(self::TABLE);
    }
}
