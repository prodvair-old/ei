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

            'activity'     => $this->smallInteger()->notNull(),
            'title'        => $this->string()->notNull(),
            'full_title'   => $this->string()->notNull(),
            'inn'          => $this->string(12),
            'ogrn'         => $this->string(15),
            'reg_number'   => $this->string(),
            'email'        => $this->string(),
            'phone'        => $this->string(),
            'website'      => $this->string()->notNull(),
            'status'       => $this->smallInteger()->notNull(),

            'created_at'   => $this->integer()->notNull(),
            'updated_at'   => $this->integer()->notNull(),
        ]);
        
        $this->createIndex('idx-organization-model-parent_id', self::TABLE, ['model', 'parent_id'], true);
        $this->createIndex('idx-organization-model', self::TABLE, 'model');

		$this->addCommentOnColumn(self::TABLE, 'model', 'Код модели или иначе, тип организации');
		$this->addCommentOnColumn(self::TABLE, 'parent_id', 'ID в соответствующей модели');

		$this->addCommentOnColumn(self::TABLE, 'activity', 'Поле деятельности организации');
		$this->addCommentOnColumn(self::TABLE, 'title', 'название');
		$this->addCommentOnColumn(self::TABLE, 'inn', 'ИНН');
		$this->addCommentOnColumn(self::TABLE, 'ogrn', 'ОГРН');
		$this->addCommentOnColumn(self::TABLE, 'reg_number', 'Регистрационный номер');
		$this->addCommentOnColumn(self::TABLE, 'email', 'Email');
		$this->addCommentOnColumn(self::TABLE, 'phone', 'Телефон');
		$this->addCommentOnColumn(self::TABLE, 'website', 'Сайт');
		$this->addCommentOnColumn(self::TABLE, 'status', 'Статус - WAITING, CHECKED');
    }

    public function down()
    {
        $this->dropTable(self::TABLE);
    }
}
