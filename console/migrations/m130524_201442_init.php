<?php

use yii\db\Migration;

/**
 * Class m130524_201442_init
 * User
 */
class m130524_201442_init extends Migration
{
    const TABLE = '{{%user}}';

    public function up()
    {
        $this->createTable(self::TABLE, [
            'id' => $this->bigPrimaryKey(),
            'username' => $this->string()->notNull()->unique(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'email' => $this->string()->notNull()->unique(),

            'status' => $this->smallInteger()->notNull()->defaultValue(1),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);
    }

    public function down()
    {
        $this->dropTable(self::TABLE);
    }
}
