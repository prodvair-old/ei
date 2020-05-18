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
    const LIMIT = 100;

    public function safeUp()
    {
        $db = isset(\Yii::$app->dbremote) ? \Yii::$app->dbremote : \Yii::$app->db;
        
        $select = $db->createCommand(
            'SELECT count(id) FROM "eiLot".cases'
        );
        $result = $select->queryAll();
        
        $offset = 0;
   
        // добавление информации по банкротным делам
        while ($offset < $result[0]['count']) {

            $this->insertPoole($db, $offset);

            $offset = $offset + self::LIMIT;

            $sleep = rand(1, 3);
            sleep($sleep);
        }
    }

    private function insertPoole($db, $offset)
    {

        $cases = [];

        $query = $db->createCommand(
            'SELECT * FROM "eiLot".cases ORDER BY "cases".id ASC LIMIT ' . self::LIMIT . ' OFFSET ' . $offset
        );

        $rows = $query->queryAll();
    
        foreach($rows as $row)
        {
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

    public function safeDown()
    {
        $db = \Yii::$app->db;
<<<<<<< HEAD
        $db->createCommand('SET FOREIGN_KEY_CHECKS = 0')-> execute();
        $db->createCommand('TRUNCATE TABLE '. self::TABLE .' CASCADE')->execute();
        $db->createCommand('SET FOREIGN_KEY_CHECKS = 1')->execute();
=======
        if ($this->db->driverName === 'mysql') {
            $db->createCommand('SET FOREIGN_KEY_CHECKS = 0')-> execute();
            $db->createCommand('TRUNCATE TABLE '. self::TABLE)->execute();
            $db->createCommand('SET FOREIGN_KEY_CHECKS = 1')->execute();
        } else
            $db->createCommand('TRUNCATE TABLE '. self::TABLE .' CASCADE')->execute();
>>>>>>> ebec2832a1010f5773b8814137ec37553a6cc4e2
    }
}
