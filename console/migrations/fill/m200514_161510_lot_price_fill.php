<?php

use yii\db\Migration;
use common\models\db\LotPrice;
use console\traits\Keeper;

/**
 * Class m200514_161510_lot_price_fill
 */
class m200514_161510_lot_price_fill extends Migration
{
    use Keeper;
    
    const TABLE = '{{%lot_price}}';
    const LIMIT = 20000;

    public function safeUp()
    {
        $db = isset(\Yii::$app->dbremote) ? \Yii::$app->dbremote : \Yii::$app->db;
        
        $select = $db->createCommand(
            'SELECT count(id) FROM "eiLot"."lotPriceHistorys"'
        );
        $result = $select->queryAll();
        
        $offset = 0;
   
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
        $lot_prices = [];

        $query = $db->createCommand(
            'SELECT * FROM "eiLot"."lotPriceHistorys" WHERE "lotId" NOTNULL ORDER BY "lotPriceHistorys".id ASC LIMIT ' . self::LIMIT . ' OFFSET ' . $offset
        );

        $rows = $query->queryAll();

        foreach($rows as $row)
        {
            $lot_id = $row['lotId'];

            $created_at = strtotime($row['createdAt']);
            $updated_at = strtotime($row['updatedAt']);
            
            // Lot's price history
            $lp = [
                'lot_id'     => $lot_id,
                'price'      => $row['price'],
                'started_at' => strtotime($row['intervalBegin']),
                'end_at'     => strtotime($row['intervalEnd']),
                'created_at' => $created_at,
                'updated_at' => $updated_at,
            ];
            $lot_price = new LotPrice($lp);
            
            $this->validateAndKeep($lot_price, $lot_prices, $lp);
        }
        
        $this->batchInsert(self::TABLE, ['lot_id', 'price', 'started_at', 'end_at', 'created_at', 'updated_at'], $lot_prices);
    }
}
