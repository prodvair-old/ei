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
    const POOLE = 100;
    
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
        $db = \Yii::$app->db;
        $select = $db->createCommand(
            'SELECT id FROM "eiLot".torgs ORDER BY "torgs".id'
        );
        $ids = $select->queryAll();
        
        $poole = [];
        
        $torgs = [];
        $torgs_debtor = [];
        $torgs_pledge = [];
        $torgs_drawish = [];
   
        // добавление информации о торгах
        foreach($ids as $id)
        {
            
            if (count($poole) < self::POOLE) {
                $poole[] = $id['id'];
                continue;
            }

            $q = $db->createCommand(
                'SELECT * FROM "eiLot".torgs WHERE id IN (' . implode(',', $poole) . ')'
            );

            $rows = $q->queryAll();
            $poole = [];

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
                    'started_at'   => ($row['startDate'] ? strtotime($row['startDate']) : null),
                    'end_at'       => ($row['endDate'] ? strtotime($row['endDate']) : null),
                    'completed_at' => ($row['completeDate'] ? strtotime($row['completeDate']) : null),
                    'published_at' => ($row['publishedDate'] ? strtotime($row['publishedDate']) : null),
                    'offer'        => self::$offer_convertor[$row['tradeType']],

                    'created_at'   => $created_at,
                    'updated_at'   => $updated_at,
                ];
                $torg = new Torg($t);
                
                if ($this->validateAndKeep($torg, $torgs, $t)) {
                    if ($property == Torg::PROPERTY_BANKRUPT) {
                        $td = [
                            'torg_id'     => $torg_id,
                            'etp_id'      => $row['etpId'],
                            'bankrupt_id' => $row['bankruptId'],
                            'manager_id'  => $row['publisherId'],
                            'case_id'     => $row['caseId'],
                        ];
                        $torg_debtor = new TorgDebtor($td);
                        $this->validateAndKeep($torg_debtor, $torgs_debtor, $td);
                    } elseif ($property == Torg::PROPERTY_ZALOG) {
                        $tp = [
                            'torg_id'  => $torg_id,
                            'user_id'  => $row['publisherId'],
                            'owner_id' => $row['bankruptId'],
                        ];
                        $torg_pledge = new TorgPledge($tp);
                        $this->validateAndKeep($torg_pledge, $torgs_pledge, $tp);
                    } else {
                        $td = [
                            'torg_id'    => $torg_id,
                            'manager_id' => $row['publisherId'],
                        ];
                        $torg_drawish = new TorgDrawish($td);
                        $this->validateAndKeep($torg_drawish, $torgs_drowish, $td);
                    }
                }
            }

            $this->batchInsert(self::TABLE, ['id', 'property', 'description', 'started_at', 'end_at', 'completed_at', 'published_at', 'offer', 'created_at', 'updated_at'], $torgs);
            $this->batchInsert('{{%torg_debtor}}', ['torg_id', 'etp_id', 'bankrupt_id', 'manager_id', 'case_id'], $torgs_debtor);
            $this->batchInsert('{{%torg_pledge}}', ['torg_id', 'owner_id', 'user_id'], $torgs_pledge);
            $this->batchInsert('{{%torg_drawish}}', ['torg_id', 'manager_id'], $torgs_drawish);

            $torgs = [];
            $torgs_debtor = [];
            $torgs_pledge = [];
            $torgs_drawish = [];
        }
    }

    public function safeDown()
    {
        $db = \Yii::$app->db;
        $db->createCommand('TRUNCATE TABLE {{%torg_drawish}} CASCADE')->execute();
        $db->createCommand('TRUNCATE TABLE {{%torg_pledge}} CASCADE')->execute();
        $db->createCommand('TRUNCATE TABLE {{%torg_debtor}} CASCADE')->execute();
        $db->createCommand('TRUNCATE TABLE '. self::TABLE .' CASCADE')->execute();
    }
}
