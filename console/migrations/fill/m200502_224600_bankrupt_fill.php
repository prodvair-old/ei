<?php

use yii\db\Migration;
use common\models\db\Bankrupt;
use common\models\db\Profile;
use common\models\db\Place;
use common\models\db\Organization;
use console\traits\Keeper;
use console\traits\District;

/**
 * Class m200502_224600_bankrupt_fill
 */
class m200502_224600_bankrupt_fill extends Migration
{
    use Keeper;
    use District;
    
    const TABLE = '{{%bankrupt}}';

    const LIMIT = 5000;

    public function safeUp()
    {
        $db = isset(\Yii::$app->dbremote) ? \Yii::$app->dbremote : \Yii::$app->db;
        
        $select = $db->createCommand(
            'SELECT count(id) FROM "eiLot".bankrupts'
        );
        $result = $select->queryAll();
        
        $offset = 0;
    
        while ($offset < $result[0]['count']) {

            $this->insertPoole($db, $offset);

            $offset = $offset + self::LIMIT;

            $sleep = rand(1, 3);
            sleep($sleep);
        }
    }

    private function insertPoole($db, $offset)
    {
        $query = $db->createCommand(
            'SELECT * FROM  "eiLot".bankrupts ORDER BY "bankrupts".id ASC LIMIT ' . self::LIMIT . ' OFFSET ' . $offset
        );

        $rows = $query->queryAll();
        
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
            
            if ($this->validateAndKeep($bankrupt, $bankrupts, $b)) {
                
                $city     = isset($row['city']) && $row['city'] ? $row['city'] : '';
                $district = $this->districtConvertor($row['district']);
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
                    
                    $this->validateAndKeep($profile, $profiles, $p);
                    
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

                    $this->validateAndKeep($place, $places, $p);
                    
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
                    
                    $this->validateAndKeep($organization, $organizations, $o);

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
                    
                    $this->validateAndKeep($place, $places, $p);
                }
            }
        }
        $this->batchInsert(self::TABLE, ['id', 'agent', 'created_at', 'updated_at'], $bankrupts);
        $this->batchInsert('{{%profile}}', ['model', 'parent_id', 'activity', 'inn', 'gender', 'birthday', 'phone', 'first_name', 'last_name', 'middle_name', 'created_at', 'updated_at'], $profiles);
        $this->batchInsert('{{%organization}}', ['model', 'parent_id', 'activity', 'title', 'full_title', 'inn', 'ogrn', 'reg_number', 'email', 'phone', 'website', 'status', 'created_at', 'updated_at'], $organizations);
        $this->batchInsert('{{%place}}', ['model', 'parent_id', 'city', 'region_id', 'district_id', 'address', 'geo_lat', 'geo_lon', 'created_at', 'updated_at'], $places);
    }

    public function safeDown()
    {
        $db = \Yii::$app->db;
        $db->createCommand('DELETE FROM {{%place}} WHERE model=' . Bankrupt::INT_CODE)->execute();
        $db->createCommand('DELETE FROM {{%organization}} WHERE model=' . Bankrupt::INT_CODE)->execute();
        $db->createCommand('DELETE FROM {{%profile}} WHERE model=' . Bankrupt::INT_CODE)->execute();
        if ($this->db->driverName === 'mysql') {
            $db->createCommand('SET FOREIGN_KEY_CHECKS = 0')-> execute();
            $db->createCommand('TRUNCATE TABLE '. self::TABLE)->execute();
            $db->createCommand('SET FOREIGN_KEY_CHECKS = 1')-> execute();
        } else
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
