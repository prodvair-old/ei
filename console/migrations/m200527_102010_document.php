<?php

use yii\db\Migration;

/**
 * Class m200527_102010_document
 */
class m200527_102010_document extends Migration
{
    const TABLE = '{{%document}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(self::TABLE, [
            'id'         => $this->bigPrimaryKey(),
            'model'        => $this->smallInteger()->notNull(),
            'parent_id'    => $this->bigInteger()->notNull(),

            'name'       => $this->string()->notNull(),
            'ext'        => $this->string(),
            'url'        => $this->string()->notNull(),
            'hash'       => $this->string(),
            
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex('idx-document-model-parent_id', self::TABLE, ['model', 'parent_id'], true);

		$this->addCommentOnColumn(self::TABLE, 'model', 'Код модели, например Casefile::INT_CODE');
		$this->addCommentOnColumn(self::TABLE, 'parent_id', 'ID в соответствующей модели, например в Casefile, Torg, Lot');
        
		$this->addCommentOnColumn(self::TABLE, 'name', 'Наименование документа');
		$this->addCommentOnColumn(self::TABLE, 'ext', 'Расширение файла документа');
		$this->addCommentOnColumn(self::TABLE, 'url', 'Ссылка на документ');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(self::TABLE);
    }

}
