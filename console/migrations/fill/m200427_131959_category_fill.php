<?php

use yii\db\Migration;

use common\models\db\Category;

/**
 * Class m200427_131959_category_fill
 * Заполнение нового справаочника категорий.
 */
class m200427_131959_category_fill extends Migration
{
    const TABLE = '{{%category}}';

    // индексом является шаг, который равен соответствующей константе в Category
    private $properties = [10000 => 'bankrupt', 20000 => 'arrest', 30000 => 'zalog'];

    public function safeUp()
    {
        // создание корневой категории
        $category = new Category(['id' => Category::ROOT, 'name' => 'Все категории', 'slug' => 'lot-list']);
        $category->makeRoot();
        
        // получение категорий из существующего справочника
        $db = isset(\Yii::$app->dbremote) ? \Yii::$app->dbremote : \Yii::$app->db;
        $select = $db->createCommand(
            'SELECT * FROM site."lotsCategory" WHERE id > ' . Category::ROOT
        );
        $rows = $select->queryAll();
        
        // добавление категорий в новый справочник
        foreach($rows as $row) {
            // первый уровень
            $node = new Category([
                'id'   => $row['id'],
                'name' => $row['name'],
                'slug' => $row['translit_name']],
            );
            $node->appendTo($category);
            // второй уровень
            foreach($this->properties as $step => $property) {
                if ($row[$property . '_categorys']) {
                    $objs = json_decode($row[$property . '_categorys']);
                    foreach($objs as $id => $obj) {
                        $id = (int) $id;
                        // увеличить ID категорий на соответствующую (типу имущества) константу
                        $id += $step;
                        // добавить подкатегорию
                        $leaf = new Category([
                            'id'   => $id, 
                            'name' => $obj->name, 
                            'slug' => $obj->translit,
                        ]);
                        $leaf->appendTo($node);
                    }
                }
            }
        }
    }

    public function safeDown()
    {
        $db = \Yii::$app->db;
        if ($this->db->driverName === 'mysql') {
            $db->createCommand('SET FOREIGN_KEY_CHECKS = 0')-> execute();
            $db->createCommand('TRUNCATE TABLE '. self::TABLE)->execute();
            $db->createCommand('SET FOREIGN_KEY_CHECKS = 1')-> execute();
        } else
            $db->createCommand('TRUNCATE TABLE '. self::TABLE .' CASCADE')->execute();
    }
}
