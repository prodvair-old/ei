<?php

use yii\db\Migration;
use common\models\db\Lot;
use common\models\db\Place;
use console\traits\Keeper;
use console\traits\District;

/**
 * Class m200508_063129_lot_fill
 */
class m200508_063129_lot_fill extends Migration
{
    use Keeper;
    use District;
    
    const TABLE = '{{%lot}}';
    const LIMIT = 10000;

    private static $status_convertor = [
        "подводятся итоги (приостановлены) " => [Lot::STATUS_SUSPENDED, LOT::REASON_SUMMARIZING],
        "Определение участников " => [Lot::STATUS_IN_PROGRESS, LOT::REASON_APPLICATION],
        "Несостоявшиеся из-за отсутствия предложения повышения цены" => [Lot::STATUS_CANCELLED, LOT::REASON_PRICE],
        "Прием заявок завершен" => [Lot::STATUS_SUSPENDED, LOT::REASON_APPLICATION],
        "идёт приём заявок (приостановлены) " => [Lot::STATUS_SUSPENDED, LOT::REASON_APPLICATION],
        "Торги приостановлены" => [Lot::STATUS_SUSPENDED, LOT::REASON_NO_MATTER],
        "Приём заявок" => [Lot::STATUS_IN_PROGRESS, LOT::REASON_APPLICATION],
        "Несостоявшиеся из-за отказа заключения контракта" => [Lot::STATUS_CANCELLED, LOT::REASON_CONTRACT],
        "Прием заявок на интервале не активен" => [Lot::STATUS_SUSPENDED, LOT::REASON_APPLICATION],
        "Торги по лоту отменены" => [Lot::STATUS_CANCELLED, LOT::REASON_NO_MATTER],
        "Подписан договор" => [Lot::STATUS_COMPLETED, LOT::REASON_CONTRACT],
        "Объявлены торги" => [Lot::STATUS_IN_PROGRESS, LOT::REASON_NO_MATTER],
        "Торги отменены " => [Lot::STATUS_CANCELLED, LOT::REASON_NO_MATTER],
        "Торги завершены" => [Lot::STATUS_COMPLETED, LOT::REASON_NO_MATTER],
        "Торги по лоту проведены" => [Lot::STATUS_COMPLETED, LOT::REASON_NO_MATTER],
        "Торги не состоялись " => [Lot::STATUS_CANCELLED, LOT::REASON_NO_MATTER],
        "Торги объявлены" => [Lot::STATUS_ANNOUNCED, LOT::REASON_NO_MATTER],
        "Опубликован" => [Lot::STATUS_ANNOUNCED, LOT::REASON_NO_MATTER],
        "Торги приостановлены " => [Lot::STATUS_SUSPENDED, LOT::REASON_NO_MATTER],
        "Действует" => [Lot::STATUS_IN_PROGRESS, LOT::REASON_NO_MATTER],
        "объявлены " => [Lot::STATUS_ANNOUNCED, LOT::REASON_NO_MATTER],
        "Несостоявшийся в связи с отсутствием допущенных участников" => [Lot::STATUS_CANCELLED, LOT::REASON_PARTICIPANT],
        "Торги не состоялись" => [Lot::STATUS_CANCELLED, LOT::REASON_NO_MATTER],
        "Продажа" => [Lot::STATUS_IN_PROGRESS, LOT::REASON_NO_MATTER],
        "Торги по лоту не состоялись" => [Lot::STATUS_CANCELLED, LOT::REASON_NO_MATTER],
        "Состоявшийся" => [Lot::STATUS_COMPLETED, LOT::REASON_NO_MATTER],
        "торги отменены " => [Lot::STATUS_CANCELLED, LOT::REASON_NO_MATTER],
        "Приостановлен" => [Lot::STATUS_SUSPENDED, LOT::REASON_NO_MATTER],
        "Прием заявок" => [Lot::STATUS_IN_PROGRESS, LOT::REASON_APPLICATION],
        "Подписан договор " => [Lot::STATUS_COMPLETED, LOT::REASON_CONTRACT],
        "Отменен/аннулирован" => [Lot::STATUS_CANCELLED, LOT::REASON_NO_MATTER],
        "идёт приём заявок " => [Lot::STATUS_IN_PROGRESS, LOT::REASON_APPLICATION],
        "Торги в стадии приема заявок" => [Lot::STATUS_IN_PROGRESS, LOT::REASON_APPLICATION],
        "Торги завершены " => [Lot::STATUS_CANCELLED, LOT::REASON_APPLICATION],
        "Определение участников" => [Lot::STATUS_IN_PROGRESS, LOT::REASON_APPLICATION],
        "Прием заявок " => [Lot::STATUS_IN_PROGRESS, LOT::REASON_APPLICATION],
        "Отменён организатором" => [Lot::STATUS_CANCELLED, LOT::REASON_NO_MATTER],
        "Текущий" => [Lot::STATUS_IN_PROGRESS, LOT::REASON_NO_MATTER],
        "Объявлен" => [Lot::STATUS_ANNOUNCED, LOT::REASON_NO_MATTER],
        "Определение участников торгов" => [Lot::STATUS_IN_PROGRESS, LOT::REASON_APPLICATION],
        "торги завершены " => [Lot::STATUS_COMPLETED, LOT::REASON_NO_MATTER],
        "подводятся итоги " => [Lot::STATUS_SUSPENDED, LOT::REASON_SUMMARIZING],
        "Идет прием заявок" => [Lot::STATUS_IN_PROGRESS, LOT::REASON_APPLICATION],
        "Торги объявлены " => [Lot::STATUS_ANNOUNCED, LOT::REASON_NO_MATTER],
        "Торги отменены" => [Lot::STATUS_CANCELLED, LOT::REASON_NO_MATTER],
        "Не состоялся" => [Lot::STATUS_CANCELLED, LOT::REASON_NO_MATTER],
        "Извещение опубликовано" => [Lot::STATUS_ANNOUNCED, LOT::REASON_NO_MATTER],
        "Объявленные торги" => [Lot::STATUS_IN_PROGRESS, LOT::REASON_NO_MATTER],
        "Окончен" => [Lot::STATUS_COMPLETED, LOT::REASON_NO_MATTER],
        "Подведение итогов" => [Lot::STATUS_SUSPENDED, LOT::REASON_SUMMARIZING],
        "Торги по лоту приостановлены" => [Lot::STATUS_SUSPENDED, LOT::REASON_NO_MATTER],
        "Подведение итогов Организатором" => [Lot::STATUS_SUSPENDED, LOT::REASON_SUMMARIZING],
        "Несостоявшийся с единственным участником" => [Lot::STATUS_CANCELLED, LOT::REASON_PARTICIPANT],
    ];
    
    public function safeUp()
    {

        $db = isset(\Yii::$app->dbremote) ? \Yii::$app->dbremote : \Yii::$app->db;
        
        $select = $db->createCommand(
            'SELECT count(id) FROM "eiLot".lots'
        );
        $result = $select->queryAll();
        
        $offset = 0;
   
        // добавление информации по лотам
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
        $db->createCommand('DELETE FROM {{%place}} WHERE model=' . Lot::INT_CODE)->execute();
        if ($this->db->driverName === 'mysql') {
            $db->createCommand('SET FOREIGN_KEY_CHECKS = 0')-> execute();
            $db->createCommand('TRUNCATE TABLE '. self::TABLE)->execute();
            $db->createCommand('SET FOREIGN_KEY_CHECKS = 1')-> execute();
        } else
            $db->createCommand('TRUNCATE TABLE '. self::TABLE .' CASCADE')->execute();
    }

    private function insertPoole($db, $offset)
    {
        $lots = [];
        $places = [];

        $query = $db->createCommand(
            'SELECT * FROM "eiLot".lots ORDER BY "lots".id ASC LIMIT ' . self::LIMIT . ' OFFSET ' . $offset
        );

        $rows = $query->queryAll();

        foreach($rows as $row)
        {
            $lot_id = $row['id'];

            $created_at = strtotime($row['createdAt']);
            $updated_at = strtotime($row['updatedAt']);
            $obj = json_decode($row['info']);
            $a = $this->convert($row['status']);
            
            // Lot
            $l = [
                'id'                => $lot_id,
                'torg_id'           => $row['torgId'],
                'ordinal_number'    => ($row['lotNumber'] ? $row['lotNumber'] : 1),

                'title'             => $row['title'],
                'description'       => $row['description'],
                'start_price'       => $row['startPrice'],
                'step'              => round(($row['step'] ? : 0), 4),
                'step_measure'      => ($row['stepTypeId'] ?: Lot::MEASURE_PERCENT),
                'deposit'           => round(($row['deposit'] ?: 0), 4),
                'deposit_measure'   => ($row['depositTypeId'] ?: Lot::MEASURE_PERCENT),
                'status'            => $a[0],
                'status_changed_at' => $updated_at,
                'reason'            => $a[1],
                'url'               => (isset($obj->etpLotUrl) ? $obj->etpLotUrl : null),
                'info'              => json_encode(isset($obj->vin) ? ['vin' => $obj->vin] : []),

                'created_at'        => $created_at,
                'updated_at'        => $updated_at,
            ];
            $lot = new Lot($l);
            $lot->scenario = Lot::SCENARIO_MIGRATION;
            
            if ($this->validateAndKeep($lot, $lots, $l) && $row['regionId']) {

                $city     = isset($row['city']) && $row['city'] ? $row['city'] : '';
                $district = $this->districtConvertor($row['district']);
                $address = (isset($obj->address) ? $obj->address->city . ', ' . $obj->address->region . ', ' . $obj->address->street : '');
                $address  = isset($row['address']) && $row['address'] ? $row['address'] : $address;
                $geo_lat  = (isset($obg->address->geo_lat) && $obg->address->geo_lat ? $obg->address->geo_lat : null);
                $geo_lon  = (isset($obg->address->geo_lon) && $obg->address->geo_lon ? $obg->address->geo_lon : null);

                // Place
                $p = [
                    'model'       => Lot::INT_CODE,
                    'parent_id'   => $lot_id,
                    'city'        => $city,
                    'region_id'   => $row['regionId'],
                    'district_id' => $district,
                    'address'     => $address,
                    'geo_lat'     => $geo_lat,
                    'geo_lon'     => $geo_lon,
                    'created_at'  => $created_at,
                    'updated_at'  => $updated_at,
                ];
                $place = new Place($p);

                $this->validateAndKeep($place, $places, $p);
            }
        }
        
        $this->batchInsert(self::TABLE, ['id', 'torg_id', 'ordinal_number', 'title', 'description', 'start_price', 'step', 'step_measure', 'deposit', 'deposit_measure', 'status', 'status_changed_at', 'reason', 'url', 'info', 'created_at', 'updated_at'], $lots);
        $this->batchInsert('{{%place}}', ['model', 'parent_id', 'city', 'region_id', 'district_id', 'address', 'geo_lat', 'geo_lon', 'created_at', 'updated_at'], $places);
    }

    private function convert($status)
    {
        return isset(self::$status_convertor[$status])
            ? self::$status_convertor[$status]
            : [Lot::STATUS_NOT_DEFINED, Lot::REASON_NO_MATTER];
    }
}


// php yii migrate --migrationPath=@console/migrations/fill
// php yii migrate/update --migrationPath=@console/migration/fill
// php yii migrate/down --migrationPath=@console/migrations/fill
