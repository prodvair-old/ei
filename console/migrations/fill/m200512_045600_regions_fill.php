<?php

use yii\db\Migration;
use common\models\db\Regions;
use console\traits\Keeper;

/**
 * Class m200512_045600_region_fill
 */
class m200512_045600_region_fill extends Migration
{
    use Keeper;
    
    const TABLE = '{{%region}}';

    public function safeUp()
    {
        // получение списка регионов
        $db = isset(\Yii::$app->dbremote) ? \Yii::$app->dbremote : \Yii::$app->db;
        $select = $db->createCommand(
            'SELECT * FROM "site".regions ORDER BY "regions".id'
        );
        $rows = $select->queryAll();
        
        $regions = [];
        
        // добавление регионов
        foreach($rows as $row) {

            $region_id  = $row['id'];
            $created_at = strtotime(date("Y-m-d H:i:s"));
            $updated_at = strtotime(date("Y-m-d H:i:s"));
            $obj = json_decode($row['info']);
            
            // Regions
            $r = [
                'id'         => $region_id,
                'name'       => $row['name'],
                'slug'       => $row['name_translit'],
                'created_at' => $created_at,
                'updated_at' => $updated_at,
            ];
            $region = new Region($r);

            $this->validateAndKeep($region, $regions, $r);
        }

        $this->batchInsert(self::TABLE, ['id', 'name', 'slug', 'created_at', 'updated_at'], $regions);
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
