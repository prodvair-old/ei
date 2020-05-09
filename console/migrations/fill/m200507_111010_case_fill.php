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

    public function safeUp()
    {
        // получение дел по банкротным делам
        $db = \Yii::$app->db;
        $select = $db->createCommand(
            'SELECT * FROM "eiLot".cases ORDER BY "cases".id'
        );
        $rows = $select->queryAll();
        
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

    public function safeDown()
    {
        $db = \Yii::$app->db;
        $db->createCommand('TRUNCATE TABLE '. self::TABLE .' CASCADE')->execute();
    }
}
