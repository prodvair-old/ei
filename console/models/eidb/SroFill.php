<?php
namespace console\models\eidb;

use Yii;
use yii\base\Module;

use console\traits\Keeper;
use console\traits\DistrictConsole;

use common\models\db\Sro;
use common\models\db\Organization;
use common\models\db\Place;

class SroFill extends Module
{
    const TABLE = '{{%sro}}';
    const OLD_TABLE = 'sro';
    
    public function getData($limit, $offset)
    {
        // получение само-регулируемых организаций из существующего справочника
        $db = isset(\Yii::$app->dbremote) ? \Yii::$app->dbremote : \Yii::$app->db;
        $select = $db->createCommand(
            'SELECT * FROM "eiLot".'.self::OLD_TABLE.' ORDER BY "'.self::OLD_TABLE.'".id ASC LIMIT '.$limit.' OFfSET '.$offset
        );
        $rows = $select->queryAll();

        if (!isset($rows[0])) {
            return false;
        }

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
            $s = [
                'id' => $sro_id,
                'efrsb_id' => $row['sroId'],
                'created_at' => $created_at,
                'updated_at' => $updated_at,
            ];
            $sro = new Sro($s);
            
            if (Keeper::validateAndKeep($sro, $all_sro, $s)) {
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
                $organization = new Organization($o);
            
                Keeper::validateAndKeep($organization, $organizations, $o);
                
                $city     = isset($row['city']) && $row['city'] ? $row['city'] : '';
                $district = DistrictConsole::districtConvertor($row['district']);
                $address  = isset($row['address']) && $row['address'] ? $row['address'] : '-';
                
                // Place
                $p = [
                    'model'       => Organization::TYPE_SRO,
                    'parent_id'   => $sro_id,
                    'city'        => $city,
                    'region_id'   => $row['regionId'],
                    'district_id' => $district,
                    'address'     => $address,
                    'geo_lat'     => (isset($obj->address->geo_lat) ? $obj->address->geo_lat : null),
                    'geo_lon'     => (isset($obj->address->geo_lon) ? $obj->address->geo_lon : null),
                    'created_at'  => $created_at,
                    'updated_at'  => $updated_at,
                ];
                $place = new Place($p);
                
                Keeper::validateAndKeep($place, $places, $p);
            }
        }

        return [
            'sro' =>            Yii::$app->db->createCommand()->batchInsert(self::TABLE, ['id', 'efrsb_id', 'created_at', 'updated_at'], $all_sro)->execute(),
            'organization' =>   Yii::$app->db->createCommand()->batchInsert('{{%organization}}', ['model', 'parent_id', 'activity', 'title', 'full_title', 'inn', 'ogrn', 'reg_number', 'email', 'phone', 'website', 'status', 'created_at', 'updated_at'], $organizations)->execute(),
            'place' =>          Yii::$app->db->createCommand()->batchInsert('{{%place}}', ['model', 'parent_id', 'city', 'region_id', 'district_id', 'address', 'geo_lat', 'geo_lon', 'created_at', 'updated_at'], $places)->execute()
        ];
    }
    
    public function remove()
    {
        $db = \Yii::$app->db;
        $result = [];

        $result['place']        = $db->createCommand('DELETE FROM {{%place}} WHERE model=' . Organization::TYPE_SRO)->execute();
        $result['organization'] = $db->createCommand('DELETE FROM {{%organization}} WHERE model=' . Organization::TYPE_SRO)->execute();

        if (Yii::$app->db->driverName === 'mysql') {
            $db->createCommand('SET FOREIGN_KEY_CHECKS = 0')-> execute();
            $result['sro'] = $db->createCommand('TRUNCATE TABLE '. self::TABLE)->execute();
            $db->createCommand('SET FOREIGN_KEY_CHECKS = 1')-> execute();
        } else {
            $result['sro'] = $db->createCommand('TRUNCATE TABLE '. self::TABLE .' CASCADE')->execute();
        }

        return $result;
    }
}