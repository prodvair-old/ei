<?php

use yii\db\Migration;

/**
 * Class m200410_083045_profile
 * 
 * Профиль Пользователя, Менеджера вообще любой персоны.
 */
class m200410_083045_profile extends Migration
{
    const TABLE = '{{%profile}}';

    public function up()
    {
        $this->createTable(self::TABLE, [
            'id'           => $this->bigPrimaryKey(),
            'model'        => $this->smallInteger()->notNull(),
            'parent_id'    => $this->bigInteger()->notNull(),
            
            'inn'          => $this->string(12),
            'activity'     => $this->smallInteger()->notNull(),
            'gender'       => $this->smallInteger()->defaultValue(null),
            'birthday'     => $this->integer()->defaultValue(null),
            'phone'        => $this->string()->notNull(),
            'first_name'   => $this->string()->notNull(),
            'last_name'    => $this->string()->notNull(),
            'middle_name'  => $this->string()->notNull(),

            'created_at'   => $this->integer()->notNull(),
            'updated_at'   => $this->integer()->notNull(),
        ]);
        
        $this->createIndex('idx-profile-model-parent_id', self::TABLE, ['model', 'parent_id'], true);

		$this->addCommentOnColumn(self::TABLE, 'model', 'Код модели, например User::INT_CODE');
		$this->addCommentOnColumn(self::TABLE, 'parent_id', 'ID в соответствующей модели, например в User, Manager');
        
		$this->addCommentOnColumn(self::TABLE, 'inn', 'ИНН');
		$this->addCommentOnColumn(self::TABLE, 'activity', 'Поле деятельности персоны');
		$this->addCommentOnColumn(self::TABLE, 'gender', 'Пол: 1 - мужской, 2 - женский');
		$this->addCommentOnColumn(self::TABLE, 'birthday', 'День рождения');
		$this->addCommentOnColumn(self::TABLE, 'first_name', 'Имя');
		$this->addCommentOnColumn(self::TABLE, 'last_name', 'Фамилия');
		$this->addCommentOnColumn(self::TABLE, 'middle_name', 'Отчество');
    }

    public function down()
    {
        $this->dropTable(self::TABLE);
    }
}
