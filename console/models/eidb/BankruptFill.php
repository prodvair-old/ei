<?php
namespace console\models\eidb;

use Yii;
use yii\base\Module;

use console\traits\Keeper;
use console\traits\DistrictConsole;

use common\models\db\Bankrupt;
use common\models\db\Profile;
use common\models\db\Place;
use common\models\db\Organization;

class BankruptFill extends Module
{
    const TABLE = '{{%bankrupt}}';
    const OLD_TABLE = 'bankrupts';
    
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
        
        $bankrupts = [];
        $profiles = [];
        $organizations = [];
        $places = [];
        
        // добавление банкротов
        foreach($rows as $row) {

            $bankrupt_id = $row['id'];
            $created_at  = strtotime($row['createdAt']);
            $updated_at  = strtotime($row['updatedAt']);
            $agent = $row['typeId'];
            $obj = json_decode($row['info']);
            
            $b = [
                'id'         => $bankrupt_id,
                'agent'      => $agent,
                'created_at' => $created_at,
                'updated_at' => $updated_at,
            ];
            $bankrupt = new Bankrupt($b);
            
            if (Keeper::alidateAndKeep($bankrupt, $bankrupts, $b)) {
                
                $city     = isset($row['city']) && $row['city'] ? $row['city'] : '';
                $district = DistrictConsole::districtConvertor($row['district']);
                $address  = isset($row['address']) && $row['address'] ? $row['address'] : '-';
                $geo_lat  = (isset($obg->address->geo_lat) && $obg->address->geo_lat ? $obg->address->geo_lat : null);
                $geo_lon  = (isset($obg->address->geo_lon) && $obg->address->geo_lon ? $obg->address->geo_lon : null);
                $phone    = (isset($obj->contacts->phone) && $obj->contacts->phone) ? $obj->contacts->phone : '';
                
                if ($agent == Bankrupt::AGENT_PERSON) {
                    // Profile
                    $p = [
                        'model'       => Bankrupt::INT_CODE,
                        'parent_id'   => $bankrupt_id,
                        'activity'    => $row['categoryId'],
                        'inn'         => $row['inn'],
                        'gender'      => (isset($obj->polId) ? $obj->polId : null),
                        'birthday'    => (isset($obj->birthDay) && $obj->birthDay ? self::getBirthday($obj->birthDay) : null),
                        'phone'       => $phone,
                        'first_name'  => (isset($obj->firstName) ? $obj->firstName : '-'),
                        'last_name'   => (isset($obj->lastName) ? $obj->lastName : ''),
                        'middle_name' => (isset($obj->middleName) ? $obj->middleName : ''),
                        'created_at'  => $created_at,
                        'updated_at'  => $updated_at,
                    ];
                    $profile = new Profile($p);
                    
                    Keeper::alidateAndKeep($profile, $profiles, $p);
                    
                    // Place
                    $p = [
                        'model'       => Bankrupt::INT_CODE,
                        'parent_id'   => $bankrupt_id,
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

                    Keeper::alidateAndKeep($place, $places, $p);
                    
                } else {
                    // Organization
                    $o = [
                        'model'      => Bankrupt::INT_CODE,
                        'parent_id'  => $bankrupt_id,
                        'activity'   => ($row['categoryId'] == 16 ? 9 : $row['categoryId']),
                        'title'      => $row['name'],
                        'full_title' => (isset($json->fullName) ? $json->fullName : ''),
                        'inn'        => $row['inn'],
                        'ogrn'       => (isset($obj->ogrn) ? $obj->ogrn : ''),
                        'reg_number' => '',
                        'email'      => (isset($obj->contacts->email) ? $obj->contacts->email : ''),
                        'phone'      => $phone,
                        'website'    => (isset($obj->contacts->url) ? $obj->contacts->url : ''),
                        'status'     => Organization::STATUS_CHECKED,
                        'created_at' => $created_at,
                        'updated_at' => $updated_at,
                    ];
                    $organization = new Organization($o);
                    
                    Keeper::alidateAndKeep($organization, $organizations, $o);

                    // Place
                    $p = [
                        'model'       => Bankrupt::INT_CODE,
                        'parent_id'   => $bankrupt_id,
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
                    
                    Keeper::alidateAndKeep($place, $places, $p);
                }
            }
        }

        return [
            'bankrupt' =>       Yii::$app->db->createCommand()->batchInsert(self::TABLE, ['id', 'agent', 'created_at', 'updated_at'], $bankrupts)->execute(),
            'profile' =>        Yii::$app->db->createCommand()->batchInsert('{{%profile}}', ['model', 'parent_id', 'activity', 'inn', 'gender', 'birthday', 'phone', 'first_name', 'last_name', 'middle_name', 'created_at', 'updated_at'], $profiles)->execute(),
            'organization' =>   Yii::$app->db->createCommand()->batchInsert('{{%organization}}', ['model', 'parent_id', 'activity', 'title', 'full_title', 'inn', 'ogrn', 'reg_number', 'email', 'phone', 'website', 'status', 'created_at', 'updated_at'], $organizations)->execute(),
            'place' =>          Yii::$app->db->createCommand()->batchInsert('{{%place}}', ['model', 'parent_id', 'city', 'region_id', 'district_id', 'address', 'geo_lat', 'geo_lon', 'created_at', 'updated_at'], $places)->execute()
        ];
    }

    public function remove()
    {
        $db = \Yii::$app->db;
        $result = [];

        $result['place']        = $db->createCommand('DELETE FROM {{%place}} WHERE model=' . Bankrupt::INT_CODE)->execute();
        $result['organization'] = $db->createCommand('DELETE FROM {{%organization}} WHERE model=' . Bankrupt::INT_CODE)->execute();
        $result['profile']      = $db->createCommand('DELETE FROM {{%profile}} WHERE model=' . Bankrupt::INT_CODE)->execute();

        if (Yii::$app->db->driverName === 'mysql') {
            $db->createCommand('SET FOREIGN_KEY_CHECKS = 0')-> execute();
            $result['bankrupt']      = $db->createCommand('TRUNCATE TABLE '. self::TABLE)->execute();
            $db->createCommand('SET FOREIGN_KEY_CHECKS = 1')-> execute();
        } else {
            $result['bankrupt']      = $db->createCommand('TRUNCATE TABLE '. self::TABLE .' CASCADE')->execute();
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
            $a = explode('-', $birthday);
        return (count($a) == 3)
            ? (($time_stamp = strtotime($a[0] . '-' . $a[1] . '-' . $a[2])) > 0 
                ? $time_stamp 
                : (($time_stamp = strtotime($a[2] . '-' . $a[1] . '-' . $a[0])) > 0
                    ? $time_stamp
                    : null
                )
            )
            : null;
    }
}