<?php

use yii\db\Migration;
use yii\helpers\ArrayHelper;
use common\models\db\Category;
use common\models\db\LotCategory;
use console\traits\Keeper;

/**
 * Class m200514_103900_lot_category_fill
 */
class m200514_103900_lot_category_fill extends Migration
{
    use Keeper;
    
    private $categories;
    
    const TABLE = '{{%lot_category}}';
    const LIMIT = 1;

    public function safeUp()
    {
        // загрузить справочник категорий в массив, в качестве ключа использовать slug, значение - ID категории
        $categories = Category::find()->select(['slug', 'id'])->all();
        $a = ArrayHelper::toArray($categories, [
            'common\models\db\Category' => [
                'slug',
                'id',
            ],
        ]);
        $this->categories = ArrayHelper::map($a, 'slug', 'id');
               
        $db = isset(\Yii::$app->dbremote) ? \Yii::$app->dbremote : \Yii::$app->db;
        
        $select = $db->createCommand(
            'SELECT count(id) FROM "eiLot"."lotCategorys"'
        );
        $result = $select->queryAll();
        
        $offset = 421200;
        
        // добавление информации по лотам
        while ($offset < $result[0]['count']) {

            $this->insertPoole($db, $offset);

            $offset = $offset + self::LIMIT;

            $sleep = rand(1, 3);
            sleep($sleep);
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

    private function insertPoole($db, $offset)
    {
        $all_lot_category = [];

        $query = $db->createCommand(
            'SELECT * FROM "eiLot"."lotCategorys" ORDER BY "lotCategorys".id ASC LIMIT ' . self::LIMIT . ' OFFSET ' . $offset
        );

        $rows = $query->queryAll();

        foreach($rows as $row)
        {
            if (isset($this->categories[$row['nameTranslit']])) {
                $lot_id = $row['lotId'];

                $created_at = strtotime($row['createdAt']);
                
                // Link lot & category
                $lc = [
                    'lot_id'      => $lot_id,
                    'category_id' => $this->categories[$row['nameTranslit']],

                    'created_at'  => $created_at,
                ];
                $lot_category = new LotCategory($lc);
                
                $this->validateAndKeep($lot_category, $all_lot_category, $lc);
            }
        }
        
        $this->batchInsert(self::TABLE, ['lot_id', 'category_id', 'created_at'], $all_lot_category);
    }
}
