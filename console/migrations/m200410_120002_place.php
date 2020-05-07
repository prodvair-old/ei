<?php

use yii\db\Migration;

/**
 * Class m200410_120002_place
 * Адрес частного лица, организации, объекта
 * 
 */
class m200410_120002_place extends Migration
{
    const TABLE = '{{%place}}';

    public function up()
    {
        $this->createTable(self::TABLE, [
            'id'         => $this->bigPrimaryKey(),
            'model'      => $this->smallInteger()->notNull(),
            'parent_id'  => $this->bigInteger()->notNull(),
            
            'city'       => $this->string()->notNull(),
            'region_id'  => $this->integer(),
            'district'   => $this->string()->notNull(),
            'address'    => $this->text()->notNull(),
            'geo_lat'    => $this->string(),
            'geo_lon'    => $this->string(),

            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);
        
        $this->createIndex('idx-place-model-parent_id', self::TABLE, ['model', 'parent_id'], true);

		$this->addCommentOnColumn(self::TABLE, 'model', 'Код модели, например Organization::INT_CODE');
		$this->addCommentOnColumn(self::TABLE, 'parent_id', 'ID в соответствующей модели, например в Organization');
        
		$this->addCommentOnColumn(self::TABLE, 'city', 'Город');
		$this->addCommentOnColumn(self::TABLE, 'region_id', 'Код региона');
		$this->addCommentOnColumn(self::TABLE, 'district', 'Округ');
		$this->addCommentOnColumn(self::TABLE, 'address', 'Полный адрес');
		$this->addCommentOnColumn(self::TABLE, 'geo_lat', 'Широта');
		$this->addCommentOnColumn(self::TABLE, 'geo_lon', 'Долгота');
    }

    public function down()
    {
        $this->dropTable(self::TABLE);
    }
}
