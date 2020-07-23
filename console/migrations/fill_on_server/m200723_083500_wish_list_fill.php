<?php

use yii\db\Migration;
use common\models\db\LotTrace;

/**
 * Class m200723_083500_wish_list_fill
 * 
 * User wish list of Lots.
 */
class m200723_083500_wish_list_fill extends Migration
{
    const TABLE = '{{%wish_list}}';

    public function safeUp()
    {
        $db = \Yii::$app->db;
        
        $command = $db->createCommand(
            'INSERT INTO ' . self::TABLE . ' (lot_id, user_id, created_at) '.
            'SELECT 
                 "lotId" AS lot_id,
                 "userId" AS user_id,
                 CAST(EXTRACT(EPOCH FROM "wishList"."createdAt") AS INTEGER) AS cteated_at
             FROM site."wishList"
             INNER JOIN eidb.lot ON ("wishList"."lotId"=lot.id)'
        );
        $result = $command->execute();
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
