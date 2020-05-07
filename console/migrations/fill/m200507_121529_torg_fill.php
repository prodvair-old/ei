<?php

use yii\db\Migration;
use common\models\db\Torg;
use console\traits\Keeper;

/**
 * Class m200507_121529_torg_fill
 */
class m200507_121529_torg_fill extends Migration
{
    use Keeper;
    
    const TABLE = '{{%torg}}';

    public function safeUp()
    {
        // получение данных о торгах
        $db = \Yii::$app->db;
        $select = $db->createCommand(
            'SELECT * FROM "eiLot".torg ORDER BY "torg".id'
        );
        $rows = $select->queryAll();
        
        $torgs = [];
        $torgs_debtor = [];
        $torgs_pledge = [];
        $torgs_drawish = [];
        
        // добавление информации о торгах
        foreach($rows as $row) {

            $torg_id    = $row['id'];
            $created_at = strtotime($row['createdAt']);
            $updated_at = strtotime($row['updatedAt']);

            $obj = json_decode($row['info']);
            
            // Torg
            $t = [
                'id'           => $torg_id,
                'etp_id'       => $row['etpId'],

                'property'     => $row['typeId'],
                'description'  => $row['description'],
                'started_at'   => strtotime($row['startDate']),
                'end_at'       => strtotime($row['endDate']),
                'completed_at' => strtotime($row['completedDate']),
                'published_at' => strtotime($row['publishedDate']),
                'offer'        => $row['tradeTypeId'],

                'created_at'   => $created_at,
                'updated_at'   => $updated_at,
            ];
            $torg = new Torg($t);
            
            if ($this->validateAndKeep($torg, $torgss, $t)) {
                if ($property == Torg::PROPERTY_BANKRUPT) {
                    $td = [
                        'torg_id'     => $torg_id,
                        'bankrupt_id' => $row['bankruptId'],
                        'manager_id'  => $row['publisherId'];
                        'case_id'     => $row['caseId'],
                    ];
                    $torg_debtor = new TorgDebtor($td);
                    $this->validateAndKeep($torg_debtor, $torgs_debtor, $td);
                } elseif ($property == Torg::PROPERTY_ZALOG) {
                    $tp = [
                        'torg_id'  => $torg_id,
                        'user_id'  => $row['publisherId'];
                        'owner_id' => $row['bankruptId'],
                    ];
                    $torg_pledge = new TorgPledge($tp);
                    $this->validateAndKeep($torg_pledge, $torgs_pledge, $tp);
                } else {
                    $td = [
                        'torg_id'    => $torg_id,
                        'manager_id' => $row['publisherId'];
                    ];
                    $torg_drawish = new TorgDrawish($tp);
                    $this->validateAndKeep($torg_drawish, $torgs_drowish, $td);
                }
            }
        }
        
        $this->batchInsert(self::TABLE, ['id', 'etp_id', 'property', 'description', 'started_at', 'end_at', 'completed_at', 'published_at', 'offer', 'created_at', 'updated_at'], $torgs);
        $this->batchInsert('{{%torg_debtor}}', ['torg_id', 'bankrupt_id', 'manager_id', 'case_id'], $torgs_debtor);
        $this->batchInsert('{{%torg_pledge}}', ['torg_id', 'owner_id', 'user_id'], $torgs_pledge);
        $this->batchInsert('{{%torg_drawish}}', ['torg_id', 'manager_id'], $torgs_drawish);
    }

    public function safeDown()
    {
        $db = \Yii::$app->db;
        $db->createCommand('TRUNCATE TABLE '. self::TABLE .' CASCADE')->execute();
    }
}
