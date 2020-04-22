<?php

use yii\db\Migration;

/**
 * Class m200422_084545_document
 * Документы по Торгу, Лоту или Делу
 */
class m200422_084545_document extends Migration
{
    const TABLE = '{{%document}}';

    public function up()
    {
        $this->createTable(self::TABLE, [
            'id'           => $this->bigPrimaryKey(),
            'model'        => $this->smallInteger()->notNull(),
            'parent_id'    => $this->bigInteger()->notNull(),
            
            'name'         => $this->string()->notNull(),
            'url'          => $this->string()->notNull(),
            'mime_type'    => $this->string()->notNull(),
            'hash'         => $this->text()->defaultValue(null),

            'created_at'   => $this->integer()->notNull(),
            'updated_at'   => $this->integer()->notNull(),
        ]);
        
        $this->createIndex('idx-document-model-parent_id', self::TABLE, ['model', 'parent_id'], true);

		$this->addCommentOnColumn(self::TABLE, 'model', 'Код модели - Torg::INT_CODE, Lot::INT_CODE, Case::INT_CODE');
		$this->addCommentOnColumn(self::TABLE, 'parent_id', 'ID в соответствующей модели');
        
		$this->addCommentOnColumn(self::TABLE, 'name', 'Имя файла');
		$this->addCommentOnColumn(self::TABLE, 'url', 'Url первоисточника');
		$this->addCommentOnColumn(self::TABLE, 'mime_type', 'Тип файла - расширение или mime-тип');
		$this->addCommentOnColumn(self::TABLE, 'hash', 'Hash файла');
    }

    public function down()
    {
        $this->dropTable(self::TABLE);
    }
}
