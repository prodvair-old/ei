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
    const LIMIT = 1000;
    
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
            'SELECT count(id) FROM "eiLot".torgs'
        );
        $result = $select->queryAll();
        
        $offset = 0;
        
        // добавление информации о торгах
        while ($offset < $result[0]['count']) {

            $this->insertPoole($db, $offset);

            $offset = $offset + self::LIMIT;

            $sleep = rand(1, 3);
            sleep($sleep);
        }
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

    private function insertPoole($db, $offset)
    {

        $torgs = [];
        $links = ['debtor' => [], 'pledge' => [], 'drawish' => []];

        $query = $db->createCommand(
            'SELECT * FROM "eiLot".torgs ORDER BY "torgs".id ASC LIMIT ' . self::LIMIT .' OFFSET ' . $offset
        );

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
                'msg_id'       => ($row['msgId'] ?:  'u/' . $torg_id . '/' . date('dmy', $created_at)),
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
                        $this->validateAndKeep($torg_debtor, $links['debtor'], $td);
                    }
                } elseif ($property == Torg::PROPERTY_ZALOG) {
                    if ($row['ownerId'] || $row['publisherId']) {
                        $tp = [
                            'torg_id'  => $torg_id,
                            'owner_id' => $row['ownerId'],
                            'user_id'  => $row['publisherId'],
                        ];
                        $torg_pledge = new TorgPledge($tp);
                        $torg_pledge->scenario = TorgPledge::SCENARIO_MIGRATION;
                        $this->validateAndKeep($torg_pledge, $links['pledge'], $tp);
                    }
                } else {
                    if ($row['publisherId']) {
                        $tdw = [
                            'torg_id'    => $torg_id,
                            'manager_id' => $row['publisherId'],
                        ];
                        $torg_drawish = new TorgDrawish($tdw);
                        $this->validateAndKeep($torg_drawish, $links['drawish'], $tdw);
                    }
                }
            }
        }

        $this->batchInsert(self::TABLE, ['id', 'msg_id', 'property', 'description', 'started_at', 'end_at', 'completed_at', 'published_at', 'offer', 'created_at', 'updated_at'], $torgs);
        if ($links['debtor'])
            $this->batchInsert('{{%torg_debtor}}', ['torg_id', 'etp_id', 'bankrupt_id', 'manager_id', 'case_id'], $links['debtor']);
        if ($links['pledge'])
            $this->batchInsert('{{%torg_pledge}}', ['torg_id', 'owner_id', 'user_id'], $links['pledge']);
        if ($links['drawish'])
            $this->batchInsert('{{%torg_drawish}}', ['torg_id', 'manager_id'], $links['drawish']);
    }
}
