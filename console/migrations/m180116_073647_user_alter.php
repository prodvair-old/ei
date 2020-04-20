<?php

use yii\db\Migration;

/**
 * Добавление поля группы
 */
class m180116_073647_user_alter extends Migration
{
    const TABLE = '{{%user}}';

    public function safeUp()
    {
        $this->addColumn(self::TABLE, 'group', $this->smallInteger());
    }

    public function safeDown()
    {
        $this->dropColumn(self::TABLE, 'group');
    }
}
