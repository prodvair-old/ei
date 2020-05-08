<?php

use yii\db\Migration;
use common\models\db\Owner;
use common\models\db\Organization;
use common\models\db\Place;
use console\traits\Keeper;

/**
 * Class m200508_093817_owner_fill
 */
class m200508_093817_owner_fill extends Migration
{
    use Keeper;
    
    const TABLE = '{{%owner}}';

    public function safeUp()
    {
        // получение менеджеров из существующего справочника
        $db = \Yii::$app->db;
        $select = $db->createCommand(
            'SELECT * FROM "eiLot".owners ORDER BY "etp".id'
        );
        $rows = $select->queryAll();
        
        $owners = [];
        $organizations = [];
        $places = [];
        
        // добавление торговой компании
        foreach($rows as $row) {

            $owner_id   = $row['id'];
            $created_at = strtotime($row['createdAt']);
            $updated_at = strtotime($row['updatedAt']);
            $obj        = json_decode($row['info']);
            $template   = json_decode($row['template']);
            
            // Owner
            $o = [
                'id'         => $owner_id,
                'description' => $row['description'],
                'link'       => $row['linkEi'],
                'logo'       => $row['logo'],
                'bg'         => (isset($template->bg) ? $template->bg : ''),
                'color_btn'  => (isset($row['template']['color-1']) ? $row['template']['color-1'] : ''),
                'color_1'    => (isset($row['template']['color-2']) ? $row['template']['color-2'] : ''),
                'color_2'    => (isset($row['template']['color-3']) ? $row['template']['color-3'] : ''),
                'color_3'    => (isset($row['template']['color-4']) ? $row['template']['color-4'] : ''),
                'color_4'    => (isset($row['template']['color-5']) ? $row['template']['color-5'] : ''),
                'color_5'    => (isset($row['template']['color-6']) ? $row['template']['color-6'] : ''),
                'color_6'    => '',
                'created_at' => $created_at,
                'updated_at' => $updated_at,
            ];
            $owner = new Owner($o);
            
            if ($this->validateAndKeep($owner, $owners, $o)) {
                
                $city     = isset($row['city']) && $row['city'] ? $row['city'] : '';
                $district = isset($row['district']) && $row['district'] ? $row['district'] : '';
                $address  = isset($row['address']) && $row['address'] ? $row['address'] : '';
                $geo_lat  = (isset($obg->address->geo_lat) && $obg->address->geo_lat ? $obg->address->geo_lat : null);
                $geo_lon  = (isset($obg->address->geo_lon) && $obg->address->geo_lon ? $obg->address->geo_lon : null);

                // Organization
                $or = [
                    'model'      => (($row['typeId'] === 1)? Organization::TYPE_BANK : Organization::TYPE_OWNER),
                    'parent_id'  => $owner_id,
                    'activity'   => Organization::ACTIVITY_SIMPLE,
                    'title'      => $row['title'],
                    'full_title' => '',
                    'inn'        => $row['inn'],
                    'ogrn'       => (isset($obj->ogrn) ? $obj->ogrn : null),
                    'reg_number' => '',
                    'email'      => $row['email'],
                    'phone'      => $row['phone'],
                    'website'    => $row['url'],
                    'status'     => ($row['checked'] ? Organization::STATUS_CHECKED : Organization::STATUS_WAITING),
                    'created_at' => $created_at,
                    'updated_at' => $updated_at,
                ];
                $organization = new Organization($or);
                
                $this->validateAndKeep($organization, $organizations, $o);

                // Place
                $p = [
                    'model'      => (($row['typeId'] === 1)? Organization::TYPE_BANK : Organization::TYPE_OWNER),
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
        $this->batchInsert(self::TABLE, ['model', 'id', 'description', 'link', 'logo', 'inn', 'bg', 'color_btn', 'color_1', 'color_2', 'color_3', 'color_4', 'color_5', 'color_6', 'created_at', 'updated_at'], $etps);
        $this->batchInsert('{{%organization}}', ['model', 'parent_id', 'activity', 'title', 'full_title', 'inn', 'ogrn', 'reg_number', 'email', 'phone', 'website', 'status', 'created_at', 'updated_at'], $organizations);
        $this->batchInsert('{{%place}}', ['model', 'parent_id', 'city', 'region_id', 'district', 'address', 'geo_lat', 'geo_lon', 'created_at', 'updated_at'], $places);
    }

    public function safeDown()
    {
        $db = \Yii::$app->db;
        $db->createCommand('DELETE FROM {{%place}} WHERE model=' . Organization::TYPE_BANK . ' OR model=' . Organization::TYPE_OWNER)->execute();
        $db->createCommand('DELETE FROM {{%organization}} WHERE model=' . Organization::TYPE_BANK . ' OR model=' . Organization::TYPE_OWNER)->execute();
        $db->createCommand('TRUNCATE TABLE '. self::TABLE .' CASCADE')->execute();
    }
}
