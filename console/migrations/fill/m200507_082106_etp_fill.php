<?php

use yii\db\Migration;
use common\models\db\Etp;
use common\models\db\Organization;
use common\models\db\Place;
use console\traits\Keeper;

/**
 * Class m200507_082106_etp_fill
 */
class m200507_082106_etp_fill extends Migration
{
    use Keeper;
    
    const TABLE = '{{%etp}}';

    public function safeUp()
    {
        // получение менеджеров из существующего справочника
        $db = \Yii::$app->db;
        $select = $db->createCommand(
            'SELECT * FROM "eiLot".etp ORDER BY "etp".id'
        );
        $rows = $select->queryAll();
        
        $etps = [];
        $organizations = [];
        $places = [];
        
        // добавление торговой компании
        foreach($rows as $row) {

            $etp_id     = $row['id'];
            $created_at = strtotime($row['createdAt']);
            $updated_at = strtotime($row['updatedAt']);
            $obj = json_decode($row['info']);
            
            // Etp
            $e = [
                'id'         => $etp_id,
                'number'     => $convertor[$agent],
                'created_at' => $created_at,
                'updated_at' => $updated_at,
            ];
            $etp = new Etp($e);
            
            if ($this->validateAndKeep($etp, $etps, $e)) {
                
                $city     = isset($row['city']) && $row['city'] ? $row['city'] : '';
                $district = isset($row['district']) && $row['district'] ? $row['district'] : '';
                $address  = isset($row['address']) && $row['address'] ? $row['address'] : '';
                
                // Organization
                $o = [
                    'model'      => Etp::INT_CODE,
                    'parent_id'  => $etp_id,
                    'activity'   => Organization::ACTIVITY_SIMPLE,
                    'title'      => $row['title'],
                    'full_title' => (isset($obj->fullTitle) ? $obj->fullTitle : ''),
                    'inn'        => $row['inn'],
                    'ogrn'       => null,
                    'reg_number' => null,
                    'email'      => $row['email'],
                    'phone'      => $row['phone'],
                    'website'    => $row['url'],
                    'status'     => Organization::STATUS_CHECKED,
                    'created_at' => $created_at,
                    'updated_at' => $updated_at,
                ];
                $organization = new Organization($o);
                
                $this->validateAndKeep($organization, $organizations, $o);

                // Place
                $p = [
                    'model'      => Etp::INT_CODE,
                    'parent_id'  => $etp_id,
                    'city'       => $city,
                    'region_id'  => $row['regionId'],
                    'district'   => $district,
                    'address'    => $address,
                    'geo_lat'    => null,
                    'geo_lon'    => null,
                    'created_at' => $created_at,
                    'updated_at' => $updated_at,
                ];
                $place = new Place($p);
                
                $this->validateAndKeep($place, $places, $p);
            }
        }
        $this->batchInsert(self::TABLE, ['id', 'agent', 'created_at', 'updated_at'], $etps);
        $this->batchInsert('{{%organization}}', ['model', 'parent_id', 'activity', 'title', 'full_title', 'inn', 'ogrn', 'reg_number', 'email', 'phone', 'website', 'status', 'created_at', 'updated_at'], $organizations);
        $this->batchInsert('{{%place}}', ['model', 'parent_id', 'city', 'region_id', 'district', 'address', 'geo_lat', 'geo_lon', 'created_at', 'updated_at'], $places);
    }

    public function safeDown()
    {
        $db = \Yii::$app->db;
        $db->createCommand('DELETE FROM {{%place}} WHERE model=' . Bankrupt::INT_CODE)->execute();
        $db->createCommand('DELETE FROM {{%organization}} WHERE model=' . Bankrupt::INT_CODE)->execute();
        $db->createCommand('TRUNCATE TABLE '. self::TABLE .' CASCADE')->execute();
    }
}
