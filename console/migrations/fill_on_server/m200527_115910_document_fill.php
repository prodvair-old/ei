<?php

use yii\db\Migration;
use common\models\db\Document;

/**
 * Class m200527_115910_document_fill
 * 
 * Документов возможно перенести одним запросом, поэтому
 * миграция может выполняться только на сервере, когда и источник и цель в одной БД.
 */
class m200527_115910_document_fill extends Migration
{
    const TABLE = '{{%document}}';

    public function safeUp()
    {
        $db = \Yii::$app->db;
        
        $command = $db->createCommand(
            'INSERT INTO ' . self::TABLE . ' (model, parent_id, name, ext, url, hash, created_at, updated_at) '.
            'SELECT 
                 CASE
                     WHEN "tableTypeId" = 1 THEN 6 
                     WHEN "tableTypeId" = 2 THEN 7
                     ELSE 4
                 END as model, 
                 CAST("tableId" AS INTEGER) as parent_id,
                 name, format as ext, url, hash,
                 CAST(EXTRACT(EPOCH FROM "createdAt") AS INTEGER) as cteated_at,
                 CAST(EXTRACT(EPOCH FROM "updatedAt") AS INTEGER) as updated_at
             FROM "eiLot".documents
             WHERE "tableId" NOTNULL'
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
