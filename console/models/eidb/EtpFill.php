<?php
namespace console\models\eidb;

use Yii;
use yii\base\Module;

use console\traits\Keeper;
use console\traits\District;

use common\models\db\Etp;
use common\models\db\Place;
use common\models\db\Organization;

class EtpFill extends Module
{
    const TABLE = '{{%etp}}';
    const OLD_TABLE = 'etp';
    
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
        
        $all_etp = [];
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
                'efrsb_id'   => $row['number'],
                'created_at' => $created_at,
                'updated_at' => $updated_at,
            ];
            $etp = new Etp($e);
            
            if (Keeper::validateAndKeep($etp, $all_etp, $e)) {
                
                // Organization
                $o = [
                    'model'      => Organization::TYPE_ETP,
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
                $organization->scenario = $organization::SCENARIO_MIGRATION;
                
                Keeper::validateAndKeep($organization, $organizations, $o);
                
                $city     = isset($row['city']) && $row['city'] ? $row['city'] : '';
                $district = District::districtConvertor($row['district']);
                $address  = isset($row['address']) && $row['address'] ? $row['address'] : '-';
                
                // Place
                $p = [
                    'model'       => Organization::TYPE_ETP,
                    'parent_id'   => $etp_id,
                    'city'        => $city,
                    'region_id'   => $row['regionId'],
                    'district_id' => $district,
                    'address'     => $address,
                    'geo_lat'     => null,
                    'geo_lon'     => null,
                    'created_at'  => $created_at,
                    'updated_at'  => $updated_at,
                ];
                $place = new Place($p);
                
                Keeper::validateAndKeep($place, $places, $p);
            }
        }

        return [
            'etp' =>            Yii::$app->db->createCommand()->batchInsert(self::TABLE, ['id', 'efrsb_id', 'created_at', 'updated_at'], $all_etp)->execute(),
            'organization' =>   Yii::$app->db->createCommand()->batchInsert('{{%organization}}', ['model', 'parent_id', 'activity', 'title', 'full_title', 'inn', 'ogrn', 'reg_number', 'email', 'phone', 'website', 'status', 'created_at', 'updated_at'], $organizations)->execute(),
            'place' =>          Yii::$app->db->createCommand()->batchInsert('{{%place}}', ['model', 'parent_id', 'city', 'region_id', 'district_id', 'address', 'geo_lat', 'geo_lon', 'created_at', 'updated_at'], $places)->execute()
        ];
    }

    public function remove()
    {
        $db = \Yii::$app->db;
        $result = [];

        $result['place']        = $db->createCommand('DELETE FROM {{%place}} WHERE model=' . Organization::TYPE_ETP)->execute();
        $result['organization'] = $db->createCommand('DELETE FROM {{%organization}} WHERE model=' . Organization::TYPE_ETP)->execute();

        if (Yii::$app->db->driverName === 'mysql') {
            $db->createCommand('SET FOREIGN_KEY_CHECKS = 0')-> execute();
            $result['etp']      = $db->createCommand('TRUNCATE TABLE '. self::TABLE)->execute();
            $db->createCommand('SET FOREIGN_KEY_CHECKS = 1')-> execute();
        } else {
            $result['etp']      = $db->createCommand('TRUNCATE TABLE '. self::TABLE .' CASCADE')->execute();
        }

        return $result;
    }
}