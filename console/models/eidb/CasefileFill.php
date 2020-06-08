<?php
namespace console\models\eidb;

use Yii;
use yii\base\Module;

use console\traits\Keeper;

use common\models\db\Casefile;

class CasefileFill extends Module
{
    const TABLE = '{{%casefile}}';
    const OLD_TABLE = 'cases';
    
    public function getData($limit, $offset)
    {
        // получение менеджеров из существующего справочника
        $db = isset(\Yii::$app->dbremote) ? \Yii::$app->dbremote : \Yii::$app->db;
        $select = $db->createCommand(
            'SELECT * FROM "eiLot".'.self::OLD_TABLE.' ORDER BY "'.self::OLD_TABLE.'".id ASC LIMIT '.$limit.' OFfSET '.$offset
        );
        $rows = $select->queryAll();

        if (!isset($rows[0])) {
            return false;
        }
        
        $cases = [];
    
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
        
            Keeper::validateAndKeep($case, $cases, $c);
        }

        return [
            'casefile' => Yii::$app->db->createCommand()->batchInsert(self::TABLE, ['id', 'reg_number', 'year', 'judge', 'created_at', 'updated_at'], $cases)->execute(),
        ];
    }

    public function remove()
    {
        $db = \Yii::$app->db;
        $result = [];

        if (Yii::$app->db->driverName === 'mysql') {
            $db->createCommand('SET FOREIGN_KEY_CHECKS = 0')-> execute();
            $result['casefile'] = $db->createCommand('TRUNCATE TABLE '. self::TABLE)->execute();
            $db->createCommand('SET FOREIGN_KEY_CHECKS = 1')-> execute();
        } else {
            $result['casefile'] = $db->createCommand('TRUNCATE TABLE '. self::TABLE .' CASCADE')->execute();
        }

        return $result;
    }
}