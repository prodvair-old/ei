<?php
namespace console\models\eidb;

use Yii;
use yii\base\Module;

use console\traits\Keeper;

use common\models\db\Owner;
use common\models\db\Place;
use common\models\db\Organization;

class OwnerFill extends Module
{
    const TABLE = '{{%owner}}';
    const OLD_TABLE = 'owners';
    
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
        
        $owners = [];
        $organizations = [];
        $places = [];
        
        // добавление собственников
        foreach($rows as $row) {

            $owner_id   = $row['id'];
            $created_at = strtotime($row['createdAt']);
            $updated_at = strtotime($row['updatedAt']);
            
            // Owner
            $o = [
                'id'          => $owner_id,
                'slug'        => $row['linkEi'],
                'description' => $row['description'],
                'created_at'  => $created_at,
                'updated_at'  => $updated_at,
            ];
            $owner = new Owner($o);
            
            if (Keeper::validateAndKeep($owner, $owners, $o)) {
                
                // Organization
                $o = [
                    'model'      => Organization::TYPE_OWNER,
                    'parent_id'  => $owner_id,
                    'activity'   => Organization::ACTIVITY_SIMPLE,
                    'title'      => $row['title'],
                    'full_title' => '',
                    'inn'        => null,
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
                
                Keeper::validateAndKeep($organization, $organizations, $o);
                
                $city     = isset($row['city']) && $row['city'] ? $row['city'] : '';
                $district = isset($row['district']) && $row['district'] ? $row['district'] : '';
                $address  = isset($row['address']) && $row['address'] ? $row['address'] : '-';
                
                // Place
                $p = [
                    'model'      => Organization::TYPE_OWNER,
                    'parent_id'  => $owner_id,
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
                
                Keeper::validateAndKeep($place, $places, $p);
            }
        }
        
        return [
            'owner' =>          Yii::$app->db->createCommand()->batchInsert(self::TABLE, ['id', 'slug', 'description', 'created_at', 'updated_at'], $owners)->execute(),
            'organization' =>   Yii::$app->db->createCommand()->batchInsert('{{%organization}}', ['model', 'parent_id', 'activity', 'title', 'full_title', 'inn', 'ogrn', 'reg_number', 'email', 'phone', 'website', 'status', 'created_at', 'updated_at'], $organizations)->execute(),
            'place' =>          Yii::$app->db->createCommand()->batchInsert('{{%place}}', ['model', 'parent_id', 'city', 'region_id', 'district', 'address', 'geo_lat', 'geo_lon', 'created_at', 'updated_at'], $places)->execute()
        ];
    }

    public function remove()
    {
        $db = \Yii::$app->db;
        $result = [];

        $result['place']        = $db->createCommand('DELETE FROM {{%place}} WHERE model=' . Organization::TYPE_OWNER)->execute();
        $result['organization'] = $db->createCommand('DELETE FROM {{%organization}} WHERE model=' . Organization::TYPE_OWNER)->execute();

        if (Yii::$app->db->driverName === 'mysql') {
            $db->createCommand('SET FOREIGN_KEY_CHECKS = 0')-> execute();
            $result['owner']    = $db->createCommand('TRUNCATE TABLE '. self::TABLE)->execute();
            $db->createCommand('SET FOREIGN_KEY_CHECKS = 1')-> execute();
        } else {
            $result['owner']    = $db->createCommand('TRUNCATE TABLE '. self::TABLE .' CASCADE')->execute();
        }

        return $result;
    }
}