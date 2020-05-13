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
    const POOLE = 10000;

    public function safeUp()
    {
        // получение дел по банкротным делам
        $db = isset(\Yii::$app->dbremote) ? \Yii::$app->dbremote : \Yii::$app->db;
        
        $select = $db->createCommand(
            'SELECT id FROM "eiLot".cases ORDER BY "cases".id'
        );
        $ids = $select->queryAll();

        $poole = [];
   
        // добавление информации о делах по банкротным делам
        foreach($ids as $id)
        {
            
            if (count($poole) < self::POOLE) {
                $poole[] = $id['id'];
                continue;
            }

            $this->insertPoole($db, $poole);

            $sleep = rand(1, 3);
            sleep($sleep);
        }
        if (count($poole) > 0 ) {
            $this->insertPoole($db, $poole);
        }
    }

    private function insertPoole($db, &$poole)
    {

        $cases = [];

        $query = $db->createCommand(
            'SELECT * FROM "eiLot".cases WHERE id IN (' . implode(',', $poole) . ')'
        );

        $poole = [];
        
        $rows = $query->queryAll();
    
        // получение дел по банкротным делам
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
        if ($this->db->driverName === 'mysql') {
            $db->createCommand('SET FOREIGN_KEY_CHECKS = 0')-> execute();
            $db->createCommand('TRUNCATE TABLE '. self::TABLE)->execute();
            $db->createCommand('SET FOREIGN_KEY_CHECKS = 1')->execute();
        } else
            $db->createCommand('TRUNCATE TABLE '. self::TABLE .' CASCADE')->execute();
    }
}
