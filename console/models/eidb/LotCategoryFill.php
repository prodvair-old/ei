<?php
namespace console\models\eidb;

use Yii;
use yii\base\Module;
use yii\helpers\ArrayHelper;

use console\traits\Keeper;

use common\models\db\LotCategory;
use common\models\db\Category;

class LotCategoryFill extends Module
{
    const TABLE = '{{%lot_category}}';
    const OLD_TABLE = 'lotCategorys';

    private static $categories;

    public function getData($limit, $offset)
    {
        // загрузить справочник категорий в массив, в качестве ключа использовать slug, значение - ID категории
        $categories = Category::find()->select(['slug', 'id'])->all();
        $a = ArrayHelper::toArray($categories, [
            'common\models\db\Category' => [
                'slug',
                'id',
            ],
        ]);
        self::$categories = ArrayHelper::map($a, 'slug', 'id');

        // получение менеджеров из существующего справочника
        $db = isset(\Yii::$app->dbremote) ? \Yii::$app->dbremote : \Yii::$app->db;
        $select = $db->createCommand(
            'SELECT * FROM "eiLot"."'.self::OLD_TABLE.'" ORDER BY "'.self::OLD_TABLE.'".id ASC LIMIT '.$limit.' OFfSET '.$offset
        );
        $rows = $select->queryAll();

        if (!isset($rows[0])) {
            return false;
        }
        
        $all_lot_category = [];
        foreach($rows as $row)
        {
            if (isset(self::$categories[$row['nameTranslit']])) {
                $lot_id = $row['lotId'];

                $created_at = strtotime($row['createdAt']);
                
                // Link lot & category
                $lc = [
                    'lot_id'      => $lot_id,
                    'category_id' => self::$categories[$row['nameTranslit']],

                    'created_at'  => $created_at,
                ];
                $lot_category = new LotCategory($lc);
                
                Keeper::validateAndKeep($lot_category, $all_lot_category, $lc);
            }
        }
        
        
        $result = [];

        $result['lot_category'] = Yii::$app->db->createCommand()->batchInsert(self::TABLE, ['lot_id', 'category_id', 'created_at'], $all_lot_category)->execute();
            
        return $result;
    }

    public function remove()
    {
        $db = \Yii::$app->db;
        $result = [];

        if (Yii::$app->db->driverName === 'mysql') {
            $db->createCommand('SET FOREIGN_KEY_CHECKS = 0')-> execute();
            $result['lot_category'] = $db->createCommand('TRUNCATE TABLE '. self::TABLE)->execute();
            $db->createCommand('SET FOREIGN_KEY_CHECKS = 1')-> execute();
        } else {
            $result['lot_category'] = $db->createCommand('TRUNCATE TABLE '. self::TABLE)->execute();
        }

        return $result;
    }
}