<?php

use yii\db\Migration;
use common\models\db\LotTrace;

/**
 * Class m200715_090500_lot_trace_fill
 * 
 * User tracking of lots viewing.
 */
class m200715_090500_lot_trace_fill extends Migration
{
    const TABLE = '{{%lot_trace}}';

    public function safeUp()
    {
        $db = \Yii::$app->db;
        
        $command = $db->createCommand(
            'INSERT INTO ' . self::TABLE . ' (lot__id, ip, created_at) '.
            'SELECT 
                 page_id AS lot_id,
                 ip_address AS ip,
                 CAST(EXTRACT(EPOCH FROM "created_at") AS INTEGER) as cteated_at,
             FROM site."pageViews"'
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
