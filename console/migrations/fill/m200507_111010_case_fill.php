<?php

use yii\db\Migration;
use common\models\db\Casefile;
use console\traits\Keeper;

/**
 * Class m200507_111010_case_fill
 */
class m200507_111010_case_fill extends Migration
{
    use Keeper;
    
    const TABLE = '{{%casefile}}';
    const LIMIT = 1000;

    public function safeUp()
    {
        // получение дел по банкротным делам
        $db = isset(\Yii::$app->dbremote) ? \Yii::$app->dbremote : \Yii::$app->db;
        $select = $db->createCommand(
            'SELECT count(id) FROM "eiLot".cases'
        );
        $result = $select->queryAll();
        
        $offset = 0;
   
        // добавление информации о делах по банкротным делам
        while ($offset < $result[0]['count']) {

            // получение дел по банкротным делам
            $q = $db->createCommand(
                'SELECT * FROM "eiLot".cases ORDER BY "cases".id ASC LIMIT '.self::LIMIT.' OFFSET '.$offset
            );

            $offset = $offset + self::LIMIT;

            $rows = $q->queryAll();
        
            $cases = [];
        
            // добавление дел
            foreach($rows as $row) {

                $case_id = $row['id'];
                $created_at = strtotime($row['createdAt']);
                $updated_at = strtotime($row['updatedAt']);
                $obj = json_decode($row['info']);
            
                // Case
                $c = [
                    'id'          => $case_id,
                    'reg_number'  => $row['regnum'],
                    'year'        => $obj->regYear,
                    'judge'       => $row['judge'],
                    'created_at'  => $created_at,
                    'updated_at'  => $updated_at,
                ];
                $case = new Casefile($c);
            
                $this->validateAndKeep($case, $cases, $c);
            }

            $this->batchInsert(self::TABLE, ['id', 'reg_number', 'year', 'judge', 'created_at', 'updated_at'], $cases);
        }
    }

    public function safeDown()
    {
        $db = \Yii::$app->db;
        // $db->createCommand('SET FOREIGN_KEY_CHECKS = 0')-> execute();
        $db->createCommand('TRUNCATE TABLE '. self::TABLE .' CASCADE')->execute();
        // $db->createCommand('SET FOREIGN_KEY_CHECKS = 1')->execute();
    }
}
