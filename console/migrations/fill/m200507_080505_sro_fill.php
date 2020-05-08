<?php

use yii\db\Migration;
use common\models\db\Organization;
use common\models\db\Place;
use console\traits\Keeper;

/**
 * Class m200507_080505_organization_sro_fill
 */
class m200507_080505_organization_sro_fill extends Migration
{
    use Keeper;
    
    const TABLE = '{{%sro}}';

    public function safeUp()
    {
        // получение само-регулируемых организаций из существующего справочника
        $db = \Yii::$app->db;
        $select = $db->createCommand(
            'SELECT * FROM "eiLot".sro ORDER BY "sro".id'
        );
        $rows = $select->queryAll();
        
        $all_sro = [];
        $organizations = [];
        $places = [];
        
        // добавление торговой компании
        foreach($rows as $row) {

            $sro_id     = $row['id'];
            $created_at = strtotime($row['createdAt']);
            $updated_at = strtotime($row['updatedAt']);
            $obj = json_decode($row['info']);
            
            // Sro
            $o = [
                'model'      => Organization::TYPE_SRO,
                'parent_id'  => $sro_id,
                'activity'   => Organization::ACTIVITY_SIMPLE,
                'title'      => $row['title'],
                'full_title' => '',
                'inn'        => $row['inn'],
                'ogrn'       => $row['inn'],
                'reg_number' => $row['regnum'],
                'email'      => '',
                'phone'      => '',
                'website'    => '',
                'status'     => Organization::STATUS_CHECKED,
                'created_at' => $created_at,
                'updated_at' => $updated_at,
            ];
            $sro = new Organization($o);
            
            if ($this->validateAndKeep($sro, $all_sro, $o)) {
                
                $city     = isset($row['city']) && $row['city'] ? $row['city'] : '';
                $district = isset($row['district']) && $row['district'] ? $row['district'] : '';
                $address  = isset($row['address']) && $row['address'] ? $row['address'] : '-';
                
                // Place
                $p = [
                    'model'      => Organization::TYPE_ETP,
                    'parent_id'  => $etp_id,
                    'city'       => $city,
                    'region_id'  => $row['regionId'],
                    'district'   => $district,
                    'address'    => $address,
                    'geo_lat'    => (isset($obj->address->geo_lat) ? $obj->address->geo_lat : null),
                    'geo_lon'    => (isset($obj->address->geo_lon) ? $obj->address->geo_lon : null),
                    'created_at' => $created_at,
                    'updated_at' => $updated_at,
                ];
                $place = new Place($p);
                
                $this->validateAndKeep($place, $places, $p);
            }
        }
        $this->batchInsert(self::TABLE, ['id', 'efrsb_id', 'activity', 'title', 'full_title', 'inn', 'ogrn', 'reg_number', 'email', 'phone', 'website', 'status', 'created_at', 'updated_at'], $all_sro);
        $this->batchInsert('{{%organization}}', ['model', 'parent_id', 'activity', 'title', 'full_title', 'inn', 'ogrn', 'reg_number', 'email', 'phone', 'website', 'status', 'created_at', 'updated_at'], $organizations);
        $this->batchInsert('{{%place}}', ['model', 'parent_id', 'city', 'region_id', 'district', 'address', 'geo_lat', 'geo_lon', 'created_at', 'updated_at'], $places);
    }

    public function safeDown()
    {
        $db = \Yii::$app->db;
        $db->createCommand('DELETE FROM {{%place}} WHERE model=' . Organization::TYPE_SRO)->execute();
        $db->createCommand('DELETE FROM {{%organization}} WHERE model=' . Organization::TYPE_SRO)->execute();
        $db->createCommand('TRUNCATE TABLE '. self::TABLE .' CASCADE')->execute();
    }
}
