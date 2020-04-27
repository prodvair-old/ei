<?php

use yii\db\Migration;

use common\models\db\Category;

/**
 * Class m200427_131959_category_fill
 */
class m200427_131959_category_fill extends Migration
{
    const TABLE = '{{%category}}';

    public function safeUp()
    {
        $db = \Yii::$app->db;
        $select = $db->createCommand(
            'SELECT * FROM site."lotsCategory" WHERE id > ' . Category::ROOT
        );
        $category = new Category(['id' => Category::ROOT, 'name' => 'Все категории', 'slug' => 'lot-list']);
        $category->makeRoot(); 
        foreach($select->queryAll() as $row) {
            $model = new Category(['id' => $row['id'], 'name' => $row['name'], 'slug' => $row['translit_name']]);
            $model->appendTo($category);
        }
    }

    public function safeDown()
    {
        $db = \Yii::$app->db;
        $select = $db->createCommand('TRUNCATE TABLE '. self::TABLE .' CASCADE')->execute();
    }
}
