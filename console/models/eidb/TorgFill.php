<?php
namespace console\models\eidb;

use Yii;
use yii\base\Module;

use console\traits\Keeper;

use common\models\db\Torg;
use common\models\db\TorgDebtor;
use common\models\db\TorgPledge;
use common\models\db\TorgDrawish;

class TorgFill extends Module
{
    const TABLE = '{{%torg}}';
    const OLD_TABLE = 'torgs';

    private static $offer_convertor = [
        "Конкурс" => 4,
        "Открытый конкурс" => 5,
        "открытый аукцион" => 3,
        "публичное предложение" => 1,
        "Аукцион" => 2,
    ];
    
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
        
        $torgs = [];
        $links = ['debtor' => [], 'pledge' => [], 'drawish' => []];

        foreach($rows as $row)
        {
            $torg_id = $row['id'];

            $created_at = strtotime($row['createdAt']);
            $updated_at = strtotime($row['updatedAt']);
            $property   = $row['typeId'];
            
            // Torg
            $t = [
                'id'           => $torg_id,
                'property'     => $property,
                'description'  => $row['description'],
                'started_at'   => ($row['startDate'] ? (strtotime($row['startDate'])? strtotime($row['startDate']) : null) : null),
                'end_at'       => ($row['endDate'] ? (strtotime($row['endDate'])? strtotime($row['endDate']) : null) : null),
                'completed_at' => ($row['completeDate'] ? (strtotime($row['completeDate'])? strtotime($row['completeDate']) : null) : null),
                'published_at' => ($row['publishedDate'] ? (strtotime($row['publishedDate'])? strtotime($row['publishedDate']) : null) : null),
                'offer'        => (isset(self::$offer_convertor[$row['tradeType']]) ? self::$offer_convertor[$row['tradeType']] : Torg::OFFER_PUBLIC),

                'created_at'   => $created_at,
                'updated_at'   => $updated_at,
            ];
            $torg = new Torg($t);
            
            if (Keeper::validateAndKeep($torg, $torgs, $t)) {
                if ($property == Torg::PROPERTY_BANKRUPT) {
                    if ($row['bankruptId'] || $row['publisherId']) {
                        $td = [
                            'torg_id'     => $torg_id,
                            'etp_id'      => $row['etpId'],
                            'bankrupt_id' => $row['bankruptId'],
                            'manager_id'  => $row['publisherId'],
                            'case_id'     => $row['caseId'],
                        ];
                        $torg_debtor = new TorgDebtor($td);
                        Keeper::validateAndKeep($torg_debtor, $links['debtor'], $td);
                    }
                } elseif ($property == Torg::PROPERTY_ZALOG) {
                    if ($row['ownerId'] || $row['publisherId']) {
                        $tp = [
                            'torg_id'  => $torg_id,
                            'owner_id' => $row['ownerId'],
                            'user_id'  => $row['publisherId'],
                        ];
                        $torg_pledge = new TorgPledge($tp);
                        Keeper::validateAndKeep($torg_pledge, $links['pledge'], $tp);
                    }
                } else {
                    if ($row['publisherId']) {
                        $tdw = [
                            'torg_id'    => $torg_id,
                            'manager_id' => $row['publisherId'],
                        ];
                        $torg_drawish = new TorgDrawish($tdw);
                        Keeper::validateAndKeep($torg_drawish, $links['drawish'], $tdw);
                    }
                }
            }
        }

        $result = [];

        $result['torg'] = Yii::$app->db->createCommand()->batchInsert(self::TABLE, ['id', 'property', 'description', 'started_at', 'end_at', 'completed_at', 'published_at', 'offer', 'created_at', 'updated_at'], $torgs)->execute();
        if ($links['debtor'])
            $result['torg_debtor']  = Yii::$app->db->createCommand()->batchInsert('{{%torg_debtor}}', ['torg_id', 'etp_id', 'bankrupt_id', 'manager_id', 'case_id'], $links['debtor'])->execute();
        if ($links['pledge'])
            $result['torg_pledge']  = Yii::$app->db->createCommand()->batchInsert('{{%torg_pledge}}', ['torg_id', 'owner_id', 'user_id'], $links['pledge'])->execute();
        if ($links['drawish'])
            $result['torg_drawish'] = Yii::$app->db->createCommand()->batchInsert('{{%torg_drawish}}', ['torg_id', 'manager_id'], $links['drawish'])->execute();
            
        return $result;
    }

    public function remove()
    {
        $db = \Yii::$app->db;
        $result = [];

        if (Yii::$app->db->driverName === 'mysql') {
            $db->createCommand('SET FOREIGN_KEY_CHECKS = 0')-> execute();
            $result['torg_drawish'] = $db->createCommand('TRUNCATE TABLE {{%torg_drawish}}')->execute();
            $result['torg_pledge']  = $db->createCommand('TRUNCATE TABLE {{%torg_pledge}}')->execute();
            $result['torg_debtor']  = $db->createCommand('TRUNCATE TABLE {{%torg_debtor}}')->execute();
            $result['torg']         = $db->createCommand('TRUNCATE TABLE '. self::TABLE)->execute();
            $db->createCommand('SET FOREIGN_KEY_CHECKS = 1')-> execute();
        } else {
            $result['torg_drawish'] = $db->createCommand('TRUNCATE TABLE {{%torg_drawish}}')->execute();
            $result['torg_pledge']  = $db->createCommand('TRUNCATE TABLE {{%torg_pledge}}')->execute();
            $result['torg_debtor']  = $db->createCommand('TRUNCATE TABLE {{%torg_debtor}}')->execute();
            $result['torg']         = $db->createCommand('TRUNCATE TABLE '. self::TABLE)->execute();
        }

        return $result;
    }
}