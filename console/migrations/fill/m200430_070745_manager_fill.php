<?php

use yii\db\Migration;
use common\models\db\Manager;
use common\models\db\Profile;
use common\models\db\Place;
use common\models\db\Organization;
use console\traits\Keeper;

/**
 * Class m200430_070745_manager_fill
 */
class m200430_070745_manager_fill extends Migration
{
    use Keeper;
    
    const TABLE = '{{%manager}}';

    public function safeUp()
    {
        // получение менеджеров из существующего справочника
        $db = isset(\Yii::$app->dbremote) ? \Yii::$app->dbremote : isset(\Yii::$app->db);
        $select = $db->createCommand(
            'SELECT * FROM "eiLot".managers ORDER BY "managers".id'
        );
        $rows = $select->queryAll();
        
        $managers = [];
        $manager_sro = [];
        $profiles = [];
        $organizations = [];
        $places = [];
        
        $convertor = [1 => 2, 3 => 1];
        // добавление управляющих торгами
        foreach($rows as $row) {

            $manager_id = $row['id'];
            $created_at = strtotime($row['createdAt']);
            $updated_at = strtotime($row['updatedAt']);
            $agent = $row['typeId'];
            $obj = json_decode($row['info']);
            
            $m = [
                'id'         => $manager_id,
                'agent'      => $convertor[$agent],
                'created_at' => $created_at,
                'updated_at' => $updated_at,
            ];
            $manager = new Manager($m);
            
            // добавление связи менеджера и СРО 
            if ($sro_id = $row['sroId'])
                $manager_sro[] = ['manager_id' => $manager_id, 'sro_id' => $sro_id];
                
            if ($this->validateAndKeep($manager, $managers, $m)) {
                
                $city     = isset($row['city']) && $row['city'] ? $row['city'] : '';
                $district = isset($row['district']) && $row['district'] ? $row['district'] : '';
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
                    
                    $this->validateAndKeep($profile, $profiles, $p);
                    
                    // Place
                    $p = [
                        'model'       => Manager::INT_CODE,
                        'parent_id'   => $manager_id,
                        'city'        => $city,
                        'region_id'   => $row['regionId'],
                        'district'    => $district,
                        'address'     => $address,
                        'geo_lat'     => $geo_lat,
                        'geo_lon'     => $geo_lon,
                        'created_at'  => $created_at,
                        'updated_at'  => $updated_at,
                    ];
                    $place = new Place($p);

                    $this->validateAndKeep($place, $places, $p);
                    
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
                    
                    $this->validateAndKeep($organization, $organizations, $o);

                    // Place
                    $p = [
                        'model'      => Manager::INT_CODE,
                        'parent_id'  => $manager_id,
                        'city'       => $city,
                        'region_id'  => $row['regionId'],
                        'district'   => $district,
                        'address'    => $address,
                        'geo_lat'    => $geo_lat,
                        'geo_lon'    => $geo_lon,
                        'created_at' => $created_at,
                        'updated_at' => $updated_at,
                    ];
                    $place = new Place($p);
                    
                    $this->validateAndKeep($place, $places, $p);
                }
            }
        }
        $this->batchInsert(self::TABLE, ['id', 'agent', 'created_at', 'updated_at'], $managers);
        $this->batchInsert('{{%manager_sro}}', ['manager_id', 'sro_id'], $manager_sro);
        $this->batchInsert('{{%profile}}', ['model', 'parent_id', 'activity', 'inn', 'gender', 'birthday', 'phone', 'first_name', 'last_name', 'middle_name', 'created_at', 'updated_at'], $profiles);
        $this->batchInsert('{{%organization}}', ['model', 'parent_id', 'activity', 'title', 'full_title', 'inn', 'ogrn', 'reg_number', 'email', 'phone', 'website', 'status', 'created_at', 'updated_at'], $organizations);
        $this->batchInsert('{{%place}}', ['model', 'parent_id', 'city', 'region_id', 'district', 'address', 'geo_lat', 'geo_lon', 'created_at', 'updated_at'], $places);
    }

    public function safeDown()
    {
        $db = \Yii::$app->db;
        $db->createCommand('DELETE FROM {{%place}} WHERE model=' . Manager::INT_CODE)->execute();
        $db->createCommand('DELETE FROM {{%organization}} WHERE model=' . Manager::INT_CODE)->execute();
        $db->createCommand('DELETE FROM {{%profile}} WHERE model=' . Manager::INT_CODE)->execute();
        $db->createCommand('SET FOREIGN_KEY_CHECKS = 0')->execute();
        $db->createCommand('TRUNCATE TABLE {{%manager_sro}}')->execute();
        $db->createCommand('TRUNCATE TABLE '. self::TABLE)->execute();
        $db->createCommand('SET FOREIGN_KEY_CHECKS = 1')->execute();
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
