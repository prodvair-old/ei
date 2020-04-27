<?php

use yii\db\Migration;

class m200427_093910_onefile extends Migration
{
    const TABLE = '{{%onefile}}';
    
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(self::TABLE, [
            'id'            => $this->primaryKey(),
            'model'         => $this->string()->notNull(),
            'parent_id'     => $this->integer()->notNull(),
            'original'      => $this->string()->notNull(),
            'name'          => $this->string(32)->notNull(),
            'subdir'        => $this->string()->notNull(),
            'type'          => $this->string()->notNull(),
            'size'          => $this->integer()->notNull(),
            'defs'          => $this->text(),
            
            'created_at'    => $this->integer(),
            'updated_at'    => $this->integer(),
        ], $tableOptions);

        $this->createIndex('idx-model-parent', self::TABLE, ['model', 'parent_id']);

        $this->addCommentOnColumn(self::TABLE, 'model',     'Model namespace');
        $this->addCommentOnColumn(self::TABLE, 'parent_id', 'Model ID');
        $this->addCommentOnColumn(self::TABLE, 'original',  'Translited file name');
        $this->addCommentOnColumn(self::TABLE, 'type',      'Mime type');
        $this->addCommentOnColumn(self::TABLE, 'size',      'Size');

        $this->addCommentOnColumn(self::TABLE, 'name',      'Generated unique file name');
        $this->addCommentOnColumn(self::TABLE, 'subdir',    'Subdirectory in a model directory, may be various from model to model or the same');
        $this->addCommentOnColumn(self::TABLE, 'defs',      'Additional variables linked with file are saved as json array');
    }

    public function down()
    {
        $this->dropTable(self::TABLE);
    }
}
