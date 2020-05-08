<?php

use yii\db\Migration;

/**
 * Class m200508_063129_lot_fill
 */
class m200508_063129_lot_fill extends Migration
{
    use Keeper;
    
    const TABLE = '{{%lot}}';

    public function safeUp()
    {
        // получение данных о лотах
        $db = \Yii::$app->db;
        $select = $db->createCommand(
            'SELECT * FROM "eiLot".lots ORDER BY "lots".id'
        );
        $rows = $select->queryAll();
        
        $lots = [];
        
        // добавление информации о лотах
        foreach($rows as $row) {

            $lot_id    = $row['id'];
            $created_at = strtotime($row['createdAt']);
            $updated_at = strtotime($row['updatedAt']);

            $obj = json_decode($row['info']);
            
            // Lot
            $l = [
                'id'               => $lot_id,
                'torg_id'          => $row['torgId'],

                'title'            => $row['title'],
                'description'      => $row['description'],
                'start_price'      => $row['startPrice'],
                'step'             => $row['step'],
                'step_measure'     => $row[''],
                'deposite'         => $row['deposite'],
                'deposite_measure' => $row[''],
                'status'           => $row[''],
                'reason'           => $row[''],

                'created_at'   => $created_at,
                'updated_at'   => $updated_at,
            ];
            $lot = new Lot($l);
            
            $this->validateAndKeep($lot, $lots, $l);
        }
        
        $this->batchInsert(self::TABLE, ['id', 'torg_id', 'title', 'description', 'start_price', 'step', 'step_measure', 'deposite', 'deposite_measure', 'status', 'reason', 'created_at', 'updated_at'], $lots);
    }

    public function safeDown()
    {
        $db = \Yii::$app->db;
        $db->createCommand('TRUNCATE TABLE '. self::TABLE .' CASCADE')->execute();
    }
}
