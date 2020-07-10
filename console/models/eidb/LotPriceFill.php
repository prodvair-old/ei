<?php
namespace console\models\eidb;

use Yii;
use yii\base\Module;

use console\traits\Keeper;

use common\models\db\LotPrice;
use common\models\db\Torg;
use common\models\db\Lot;

class LotPriceFill extends Module
{
    const TABLE = '{{%lot_price}}';
    const OLD_TABLE = 'offerreductions';

    public function getData($limit, $offset)
    {
        // получение менеджеров из существующего справочника
        $lots = Lot::find()
            ->joinWith(['torg', 'prices'])
            ->where([
                Torg::tableName().'.property' => Torg::PROPERTY_BANKRUPT, 
                Torg::tableName().'.offer' => Torg::OFFER_PUBLIC,
                LotPrice::tableName().'.price' => NULL,
                ])
            ->limit($limit)
            ->offset($offset)
            ->all();
            
        if (!isset($lots[0])) {
            return false;
        }
        
        $lot_prices = [];

        foreach($lots as $lot)
        {

            $db = isset(\Yii::$app->dbremote) ? \Yii::$app->dbremote : \Yii::$app->db;
            $select = $db->createCommand(
                'SELECT * FROM "bailiff"."'.self::OLD_TABLE.'" WHERE "ofrRdnNumberInEFRSB" = '.$lot->torg->msg_id.' AND "ofrRdnLotNumber" = '.$lot->ordinal_number.' ORDER BY "ofrRdnDateTimeBeginInterval" DESC'
            );
            $rows = $select->queryAll();

            if (isset($rows[0])) {
                foreach ($rows as $row) {
                    $created_at = strtotime($row['ofrRdnAddedDateTime'],);
                    $updated_at = strtotime($row['ofrRdnAddedDateTime'],);
                    // Lot's price history
                    $lp = [
                        'lot_id'     => $lot->id,
                        'price'      => $row['ofrRdnPriceInInterval'],
                        'started_at' => strtotime($row['ofrRdnDateTimeBeginInterval']),
                        'end_at'     => strtotime($row['ofrRdnDateTimeEndInterval']),
                        'created_at' => $created_at,
                        'updated_at' => $updated_at,
                    ];
                    $lot_price = new LotPrice($lp);
                    
                    Keeper::validateAndKeep($lot_price, $lot_prices, $lp);
                }
            }
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