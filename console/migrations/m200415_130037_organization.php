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

            'type'         => $this->smallInteger()->notNull(),
            'ownership'    => $this->smallInteger()->notNull(),
            
            'title'        => $this->string()->notNull(),
            'full_title'   => $this->string(),
            'inn'          => $this->string(10),
            'ogrn'         => $this->string(13),
            'reg_number'   => $this->string(),
            'email'        => $this->string(),
            'phone'        => $this->string(10),
            'website'      => $this->string(),
            'status'       => $this->smallInteger()->notNull(),

            'created_at'   => $this->integer()->notNull(),
            'updated_at'   => $this->integer()->notNull(),
        ]);
        
        $this->createIndex('idx-organization-id-type', self::TABLE, ['id', 'type'], true);
        $this->createIndex('idx-organization-type', self::TABLE, 'type');

		$this->addCommentOnColumn(self::TABLE, 'type', 'Тип организации - OWNER, ETP, BANKRUPT');
		$this->addCommentOnColumn(self::TABLE, 'ownership', 'Форма собственности предприятия');
        
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
