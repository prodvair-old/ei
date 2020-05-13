<?php

use yii\db\Migration;
use common\models\db\Torg;
use common\models\db\TorgDebtor;
use common\models\db\TorgPledge;
use common\models\db\TorgDrawish;
use console\traits\Keeper;

/**
 * Class m200507_121529_torg_fill
 */
class m200507_121529_torg_fill extends Migration
{
    use Keeper;
    
    const TABLE  = '{{%torg}}';
    const POOLE = 2000;
    
    private static $offer_convertor = [
        "Конкурс" => 4,
        "Открытый конкурс" => 5,
        "открытый аукцион" => 3,
        "публичное предложение" => 1,
        "Аукцион" => 2,
    ];

    public function safeUp()
    {
        // получение данных о торгах
        $db = isset(\Yii::$app->dbremote) ? \Yii::$app->dbremote : \Yii::$app->db;
        $select = $db->createCommand(
            'SELECT id FROM "eiLot".torgs ORDER BY "torgs".id'
        );
        $ids = $select->queryAll();
        
        $poole = [];
        
        // добавление информации о торгах
        foreach($ids as $id)
        {
            
            if (count($poole) < self::POOLE) {
                $poole[] = $id['id'];
                continue;
            }

            $this->insertPoole($db, $poole);

            $sleep = rand(1, 3);
            sleep($sleep);

        }
        
        if (count($poole) > 0 ) {
            $this->insertPoole($db, $poole);
        }
        
        $db = \Yii::$app->db;
        $db->createCommand('update torg set started_at = null where started_at = 0')->execute();
        $db->createCommand('update torg set end_at = null where end_at = 0')->execute();
        $db->createCommand('update torg set completed_at = null where completed_at = 0')->execute();
        $db->createCommand('update torg set published_at = null where published_at = 0')->execute();
    }

    public function safeDown()
    {
        $db = \Yii::$app->db;
        if ($this->db->driverName === 'mysql') {
            $db->createCommand('SET FOREIGN_KEY_CHECKS = 0')-> execute();
            $db->createCommand('TRUNCATE TABLE {{%torg_drawish}}')->execute();
            $db->createCommand('TRUNCATE TABLE {{%torg_pledge}}')->execute();
            $db->createCommand('TRUNCATE TABLE {{%torg_debtor}}')->execute();
            $db->createCommand('TRUNCATE TABLE '. self::TABLE)->execute();
            $db->createCommand('SET FOREIGN_KEY_CHECKS = 1')-> execute();
        } else {
            $db->createCommand('TRUNCATE TABLE {{%torg_drawish}} CASCADE')->execute();
            $db->createCommand('TRUNCATE TABLE {{%torg_pledge}} CASCADE')->execute();
            $db->createCommand('TRUNCATE TABLE {{%torg_debtor}} CASCADE')->execute();
            $db->createCommand('TRUNCATE TABLE '. self::TABLE .' CASCADE')->execute();
        }
    }

    private function insertPoole($db, &$poole)
    {

        $torgs = [];
        $torgs_debtor = [];
        $torgs_pledge = [];
        $torgs_drawish = [];

        $query = $db->createCommand(
            'SELECT * FROM "eiLot".torgs WHERE id IN (' . implode(',', $poole) . ')'
        );

        $poole = [];
        
        $rows = $query->queryAll();

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
                'started_at'   => ($row['startDate'] ? strtotime($row['startDate']) : 0),
                'end_at'       => ($row['endDate'] ? strtotime($row['endDate']) : 0),
                'completed_at' => ($row['completeDate'] ? strtotime($row['completeDate']) : 0),
                'published_at' => ($row['publishedDate'] ? strtotime($row['publishedDate']) : 0),
                'offer'        => self::$offer_convertor[$row['tradeType']],

                'created_at'   => $created_at,
                'updated_at'   => $updated_at,
            ];
            $torg = new Torg($t);
            
            if ($this->validateAndKeep($torg, $torgs, $t)) {
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
                        $this->validateAndKeep($torg_debtor, $torgs_debtor, $td);
                    }
                } elseif ($property == Torg::PROPERTY_ZALOG) {
                    if ($row['bankruptId'] || $row['publisherId']) {
                        $tp = [
                            'torg_id'  => $torg_id,
                            'owner_id' => $row['bankruptId'],
                            'user_id'  => $row['publisherId'],
                        ];
                        $torg_pledge = new TorgPledge($tp);
                        $this->validateAndKeep($torg_pledge, $torgs_pledge, $tp);
                    }
                } else {
                    if ($row['publisherId']) {
                        $tdw = [
                            'torg_id'    => $torg_id,
                            'manager_id' => $row['publisherId'],
                        ];
                        $torg_drawish = new TorgDrawish($tdw);
                        $this->validateAndKeep($torg_drawish, $torgs_drawish, $tdw);
                    }
                }
            }
        }

        $this->batchInsert(self::TABLE, ['id', 'property', 'description', 'started_at', 'end_at', 'completed_at', 'published_at', 'offer', 'created_at', 'updated_at'], $torgs);
        $this->batchInsert('{{%torg_debtor}}', ['torg_id', 'etp_id', 'bankrupt_id', 'manager_id', 'case_id'], $torgs_debtor);
        $this->batchInsert('{{%torg_pledge}}', ['torg_id', 'owner_id', 'user_id'], $torgs_pledge);
        $this->batchInsert('{{%torg_drawish}}', ['torg_id', 'manager_id'], $torgs_drawish);
    }
}
