<?php
namespace console\models\eidb;

use Yii;
use yii\base\Module;

use console\traits\Keeper;

use common\models\db\LotPrice;

class LotPriceFill extends Module
{
    const TABLE = '{{%lot_price}}';
    const OLD_TABLE = 'lotPriceHistorys';

    public function getData($limit, $offset)
    {
        // получение менеджеров из существующего справочника
        $db = isset(\Yii::$app->dbremote) ? \Yii::$app->dbremote : \Yii::$app->db;
        $select = $db->createCommand(
            'SELECT * FROM "eiLot"."'.self::OLD_TABLE.'" WHERE "lotId" NOTNULL ORDER BY "'.self::OLD_TABLE.'".id ASC LIMIT '.$limit.' OFfSET '.$offset
        );
        $rows = $select->queryAll();

        if (!isset($rows[0])) {
            return false;
        }
        
        $lot_prices = [];

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
            
            Keeper::validateAndKeep($lot_price, $lot_prices, $lp);
        }
        
        $result = [];

        $result['lot_price'] = Yii::$app->db->createCommand()->batchInsert(self::TABLE, ['lot_id', 'price', 'started_at', 'end_at', 'created_at', 'updated_at'], $lot_prices)->execute();
            
        return $result;
    }

    public function remove()
    {
        $db = \Yii::$app->db;
        $result = [];

        if (Yii::$app->db->driverName === 'mysql') {
            $db->createCommand('SET FOREIGN_KEY_CHECKS = 0')-> execute();
            $result['lot_price'] = $db->createCommand('TRUNCATE TABLE '. self::TABLE)->execute();
            $db->createCommand('SET FOREIGN_KEY_CHECKS = 1')-> execute();
        } else {
            $result['lot_price'] = $db->createCommand('TRUNCATE TABLE '. self::TABLE)->execute();
        }

        return $result;
    }
}