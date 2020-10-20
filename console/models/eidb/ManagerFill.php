<?php
namespace console\models\eidb;

use Yii;

use console\traits\Keeper;
use console\traits\DistrictConsole;

use common\models\db\Manager;
use common\models\db\Profile;
use common\models\db\Place;
use common\models\db\Organization;

class ManagerFill
{
    const TABLE = '{{%manager}}';
    const OLD_TABLE = 'managers';

    private static $agentConvertor = [1 => 2, 3 => 1];
    
    public function getData($limit, $offset)
    {
        // получение менеджеров из существующего справочника
        $db = isset(\Yii::$app->dbremote) ? \Yii::$app->dbremote : \Yii::$app->db;
        $select = $db->createCommand(
            'SELECT * FROM "eiLot".'.self::OLD_TABLE.' WHERE "typeId" = 3 ORDER BY "'.self::OLD_TABLE.'".id ASC LIMIT '.$limit.' OFfSET '.$offset
        );
        $rows = $select->queryAll();

        if (!isset($rows[0])) {
            return false;
        }
        
        $managers = [];
        $manager_sro = [];
        $profiles = [];
        $organizations = [];
        $places = [];
        
        // добавление управляющих торгами
        foreach($rows as $row) {

            $manager_id = $row['id'];
            $created_at = strtotime($row['createdAt']);
            $updated_at = strtotime($row['updatedAt']);
            $agent = self::$agentConvertor[$row['typeId']];
            $obj = json_decode($row['info']);
            
            $m = [
                'id'         => $manager_id,
                'agent'      => $agent,
                'created_at' => $created_at,
                'updated_at' => $updated_at,
            ];
            $manager = new Manager($m);
            
            // добавление связи менеджера и СРО 
            if ($sro_id = $row['sroId'])
                $manager_sro[] = ['manager_id' => $manager_id, 'sro_id' => $sro_id];
                
            if (Keeper::validateAndKeep($manager, $managers, $m)) {
                
                $city     = isset($row['city']) && $row['city'] ? $row['city'] : '';
                $district = DistrictConsole::districtConvertor($row['district']);
                $address  = isset($row['address']) && $row['address'] ? $row['address'] : '';
                $geo_lat  = (isset($obg->address->geo_lat) && $obg->address->geo_lat ? $obg->address->geo_lat : null);
                $geo_lon  = (isset($obg->address->geo_lon) && $obg->address->geo_lon ? $obg->address->geo_lon : null);
                $phone    = (isset($obj->contacts->phone) && $obj->contacts->phone) ? $obj->contacts->phone : '';
                
                if ($agent == Manager::AGENT_PERSON) {
                    // Profile
                    $p = [
                        'model'       => Manager::INT_CODE,
                        'parent_id'   => $manager_id,
                        'activity'    => Profile::ACTIVITY_SIMPLE,
                        'inn'         => $row['inn'],
                        'gender'      => $obj->polId,
                        'birthday'    => (isset($obj->birthDay) && $obj->birthDay ? self::getBirthday($obj->birthDay) : null),
                        'phone'       => $phone,
                        'first_name'  => ($row['firstName'] ?: '-'),
                        'last_name'   => $row['lastName'],
                        'middle_name' => $row['middleName'],
                        'created_at'  => $created_at,
                        'updated_at'  => $updated_at,
                    ];
                    $profile = new Profile($p);
                    
                    Keeper::validateAndKeep($profile, $profiles, $p);
                    
                    // Place
                    $p = [
                        'model'       => Manager::INT_CODE,
                        'parent_id'   => $manager_id,
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

                    Keeper::validateAndKeep($place, $places, $p);
                    
                } else {
                    // Organization
                    $o = [
                        'model'      => Manager::INT_CODE,
                        'parent_id'  => $manager_id,
                        'activity'   => Organization::ACTIVITY_SIMPLE,
                        'title'      => $row['fullName'],
                        'full_title' => '',
                        'inn'        => $row['inn'],
                        'ogrn'       => (isset($obj->ogrn) ? $obj->ogrn : null),
                        'reg_number' => $row['regnum'],
                        'email'      => (isset($obj->contacts->email) ? $obj->contacts->email : ''),
                        'phone'      => $phone,
                        'website'    => (isset($obj->contacts->url) ? $obj->contacts->url : ''),
                        'status'     => ($row['checked'] ? Organization::STATUS_CHECKED : Organization::STATUS_WAITING),
                        'created_at' => $created_at,
                        'updated_at' => $updated_at,
                    ];
                    $organization = new Organization($o);
                    $organization->scenario = Organization::SCENARIO_MIGRATION;

                    Keeper::validateAndKeep($organization, $organizations, $o);

                    // Place
                    $p = [
                        'model'       => Manager::INT_CODE,
                        'parent_id'   => $manager_id,
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
                    
                    Keeper::validateAndKeep($place, $places, $p);
                }
            }
        }
        return [
            'manager' =>        Yii::$app->db->createCommand()->batchInsert(self::TABLE, ['id', 'agent', 'created_at', 'updated_at'], $managers)->execute(),
            'manager_sro' =>    Yii::$app->db->createCommand()->batchInsert('{{%manager_sro}}', ['manager_id', 'sro_id'], $manager_sro)->execute(),
            'profile' =>        Yii::$app->db->createCommand()->batchInsert('{{%profile}}', ['model', 'parent_id', 'activity', 'inn', 'gender', 'birthday', 'phone', 'first_name', 'last_name', 'middle_name', 'created_at', 'updated_at'], $profiles)->execute(),
            'organization' =>   Yii::$app->db->createCommand()->batchInsert('{{%organization}}', ['model', 'parent_id', 'activity', 'title', 'full_title', 'inn', 'ogrn', 'reg_number', 'email', 'phone', 'website', 'status', 'created_at', 'updated_at'], $organizations)->execute(),
            'place' =>          Yii::$app->db->createCommand()->batchInsert('{{%place}}', ['model', 'parent_id', 'city', 'region_id', 'district_id', 'address', 'geo_lat', 'geo_lon', 'created_at', 'updated_at'], $places)->execute()
        ];
    }

    public function remove()
    {
        $db = \Yii::$app->db;
        $result = [];

        $result['place']        = $db->createCommand('DELETE FROM {{%place}} WHERE model=' . Manager::INT_CODE)->execute();
        $result['organization'] = $db->createCommand('DELETE FROM {{%organization}} WHERE model=' . Manager::INT_CODE)->execute();
        $result['profile']      = $db->createCommand('DELETE FROM {{%profile}} WHERE model=' . Manager::INT_CODE)->execute();

        if (Yii::$app->db->driverName === 'mysql') {
            $db->createCommand('SET FOREIGN_KEY_CHECKS = 0')-> execute();
            $result['manager_sro']  = $db->createCommand('TRUNCATE TABLE {{%manager_sro}}')->execute();
            $result['manager']      = $db->createCommand('TRUNCATE TABLE '. self::TABLE)->execute();
            $db->createCommand('SET FOREIGN_KEY_CHECKS = 1')-> execute();
        } else {
            $result['manager_sro']  = $db->createCommand('TRUNCATE TABLE {{%manager_sro}} CASCADE')->execute();
            $result['manager']      = $db->createCommand('TRUNCATE TABLE '. self::TABLE .' CASCADE')->execute();
        }

        return $result;
    }

    /**
     * @param string $birthday - 13.05.2001
     * @return integer Unixtimestamp
     */
    public static function getBirthday($birthday)
    {
        $a = [];
        if ($birthday)
            $a = explode('.', $birthday);
        return (count($a) == 3)
            ? strtotime($a[2] . '-' . $a[1] . '-' . $a[0])
            : null;
    }
}