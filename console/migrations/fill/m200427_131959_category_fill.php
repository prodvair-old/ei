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

    private $properties = [10000 => 'bankrupt', 20000 => 'arrest', 30000 => 'zalog'];

    public function safeUp()
    {
        // создание корневой категории
        $category = new Category(['id' => Category::ROOT, 'name' => 'Все категории', 'slug' => 'lot-list']);
        // $category->makeRoot();

        // получение категорий из существующего справочника
        $db = \Yii::$app->db;
        $select = $db->createCommand(
            'SELECT * FROM site."lotsCategory" WHERE id > ' . Category::ROOT
        );
        $rows = $select->queryAll();
        
        // сохранение ID всех категорий первого уровня, чтобы не пытаться добавить категорию с таким же ID
        $all_ids = [];
        foreach($rows as $row) {
            $all_ids[] = $row['id'];
        }

        // добавление категорий в новый справочник
        foreach($rows as $row) {
            // первый уровень
            $model = new Category(['id' => $row['id'], 'name' => $row['name'], 'slug' => $row['translit_name']]);
            $model->appendTo($category);
            // второй уровень
            foreach($this->properties as $step => $property) {
                if ($row[$property . '_categorys']) {
                    $objs = json_decode($row[$property . '_categorys']);
                    foreach($objs as $id => $obj) {
                        $id = (int) $id;
                        // увеличиваем ID категорий, которые уже существуют
                        if (in_array($id, $all_ids)) {
                            $id += $step;
                        }
                        $m = new Category(['id' => $id, 'name' => $obj->name, 'slug' => $obj->translit]);
                        $m->appendTo($model);
                        $all_ids[] = $id;
                    }
                }
            }
        }
    }

    public function safeDown()
    {
        $db = \Yii::$app->db;
        $db->createCommand('TRUNCATE TABLE '. self::TABLE .' CASCADE')->execute();
    }
}
