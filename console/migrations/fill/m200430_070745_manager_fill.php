<?php

use yii\db\Migration;
use common\models\db\Manager;
use common\models\db\Profile;
use common\models\db\Place;
use common\models\db\Organization;

/**
 * Class m200430_070745_manager_fill
 */
class m200430_070745_manager_fill extends Migration
{
    const TABLE = '{{%manager}}';

    public function safeUp()
    {
        // получение менеджеров из существующего справочника
        $db = \Yii::$app->db;
        $select = $db->createCommand(
            'SELECT * FROM "eiLot".managers ORDER BY "managers".id'
        );
        $rows = $select->queryAll();
        
        $managers = [];
        $profiles = [];
        $organizations = [];
        $places = [];
        
        // добавление управляющих торгами
        foreach($rows as $row) {

            $created_at = strtotime($row['createdAt']);
            $updated_at = strtotime($row['updatedAt']);
            $type = $row['typeId'];
            $obj = json_decode($row['info']);
            
            $organizer_id = ($type == Manager::TYPE_ARBITR) ? $row['sroId'] : $row['id'];
            
            $a = [
                'id'           => $row['id'],
                'type'         => $row['typeId'],
                'organizer_id' => $organizer_id,
                'created_at'   => $created_at,
                'updated_at'   => $updated_at,
            ];
            $manager = new Manager($a);
            
            if ($manager->validate()) {
                $managers[] = $a;
                
                $city     = isset($row['city']) && $row['city'] ? $row['city'] : '';
                $district = isset($row['district']) && $row['district'] ? $row['district'] : '';
                $address  = isset($row['address']) && $row['address'] ? $row['address'] : '';
                $geo_lat  = (isset($obg->address->geo_lat) && $obg->address->geo_lat ? $obg->address->geo_lat : null);
                $geo_lon  = (isset($obg->address->geo_lon) && $obg->address->geo_lon ? $obg->address->geo_lon : null);
                $phone    = (isset($obj->contacts->phone) && $obj->contacts->phone) ? $obj->contacts->phone : '';
                
                if ($type == Manager::TYPE_ARBITR) {
                    // Profile
                    $p = [
                        'model'       => Manager::INT_CODE,
                        'parent_id'   => $row['id'],
                        'activity'    => Profile::ACTIVITY_SIMPLE,
                        'inn'         => $row['inn'],
                        'gender'      => $obj->polId,
                        'birthday'    => (isset($obj->birthDay) && $obj->birthDay ? self::getBirthday($obj->birthDay) : null),
                        'phone'       => $phone,
                        'first_name'  => $row['firstName'],
                        'last_name'   => $row['lastName'],
                        'middle_name' => $row['middleName'],
                        'created_at'  => $created_at,
                        'updated_at'  => $updated_at,
                    ];
                    $profile = new Profile($p);
                    
                    if ($profile->validate()) {
                        $profiles[] = $p;
                    }
                    
                    // Place
                    $p = [
                        'model'       => Manager::INT_CODE,
                        'parent_id'   => $row['id'],
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

                    if ($place->validate())
                        $places[] = $p;
                    
                } else {
                    // Organization
                    $o = [
                        'model'      => Manager::INT_CODE,
                        'parent_id'  => $row['id'],
                        'activity'   => Organization::ACTIVITY_SIMPLE,
                        'title'      => $row['fullName'],
                        'full_title' => '',
                        'inn'        => $row['inn'],
                        'ogrn'       => $obj->ogrn,
                        'reg_number' => $row['regnum'],
                        'email'      => $obj->contacts->email,
                        'phone'      => $phone,
                        'website'    => $obj->contacts->url,
                        'status'     => ($row['checked'] ? Organization::STATUS_CHECKED : Organization::STATUS_WAITING),
                        'created_at' => $created_at,
                        'updated_at' => $updated_at,
                    ];
                    $organization = new Organization($o);
                    
                    if ($organization->validate()) {
                        $organizations[] = $o;
                    }

                    // Place
                    $p = [
                        'model'      => Organization::INT_CODE,
                        'parent_id'  => $organization->id,
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
                    
                    if ($place->validate())
                        $places[] = $p;
                }
            }
        }
        $this->batchInsert(self::TABLE, ['id', 'type', 'organizer_id', 'created_at', 'updated_at'], $managers);
        $this->batchInsert('{{%profile}}', ['model', 'parent_id', 'activity', 'inn', 'gender', 'birthday', 'phone', 'first_name', 'last_name', 'middle_name', 'created_at', 'updated_at'], $profiles);
        $this->batchInsert('{{%organization}}', ['model', 'parent_id', 'activity', 'title', 'full_title', 'inn', 'ogrn', 'reg_number', 'email', 'phone', 'website', 'status', 'created_at', 'updated_at'], $organizations);
        $this->batchInsert('{{%place}}', ['model', 'parent_id', 'city', 'region_id', 'district', 'address', 'geo_lat', 'geo_lon', 'created_at', 'updated_at'], $places);
    }

    public function safeDown()
    {
        $db = \Yii::$app->db;
        $db->createCommand('DELETE FROM {{%place}} WHERE model=' . Organization::INT_CODE)->execute();
        $db->createCommand('DELETE FROM {{%organization}} WHERE model=' . Manager::INT_CODE)->execute();
        $db->createCommand('DELETE FROM {{%place}} WHERE model=' . Manager::INT_CODE)->execute();
        $db->createCommand('DELETE FROM {{%profile}} WHERE model=' . Manager::INT_CODE)->execute();
        $db->createCommand('TRUNCATE TABLE '. self::TABLE .' CASCADE')->execute();
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
